<?php
/**
 * Ajax handler class.
 *
 * Registers generic Ajax endpoints for the theme
 * (search suggestions, wishlist, etc.).
 *
 * @package HKDEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class HKDEV_Ajax
 */
class HKDEV_Ajax {

	/** @var HKDEV_Ajax|null */
	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->hooks();
	}

	private function hooks() {
		// Live product search
		add_action( 'wp_ajax_hkdev_search',        array( $this, 'search' ) );
		add_action( 'wp_ajax_nopriv_hkdev_search', array( $this, 'search' ) );

		// Price filter (AJAX shop reload)
		add_action( 'wp_ajax_hkdev_price_filter',        array( $this, 'price_filter' ) );
		add_action( 'wp_ajax_nopriv_hkdev_price_filter', array( $this, 'price_filter' ) );
	}

	// ── Live Search ───────────────────────────────────────────────────────────

	/**
	 * Return JSON product suggestions for the live search input.
	 */
	public function search() {
		check_ajax_referer( 'hkdev_nonce', 'nonce' );

		$query = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';

		if ( strlen( $query ) < 2 ) {
			wp_send_json_success( array( 'results' => array() ) );
		}

		$post_type = class_exists( 'WooCommerce' ) ? 'product' : 'post';

		$results_query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				's'              => $query,
				'posts_per_page' => 8,
				'no_found_rows'  => true,
			)
		);

		$results = array();
		if ( $results_query->have_posts() ) {
			while ( $results_query->have_posts() ) {
				$results_query->the_post();
				$item = array(
					'id'        => get_the_ID(),
					'title'     => esc_html( get_the_title() ),
					'permalink' => esc_url( get_permalink() ),
					'thumbnail' => esc_url( get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ),
				);

				if ( class_exists( 'WooCommerce' ) ) {
					$product         = wc_get_product( get_the_ID() );
					$item['price']   = $product ? wp_strip_all_tags( $product->get_price_html() ) : '';
					$item['in_stock'] = $product ? $product->is_in_stock() : false;
				}

				$results[] = $item;
			}
			wp_reset_postdata();
		}

		wp_send_json_success( array( 'results' => $results ) );
	}

	// ── Price Filter ─────────────────────────────────────────────────────────

	/**
	 * Filter products by price range via Ajax.
	 */
	public function price_filter() {
		check_ajax_referer( 'hkdev_nonce', 'nonce' );

		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( array( 'message' => 'WooCommerce not active.' ) );
		}

		$min_price = isset( $_POST['min_price'] ) ? floatval( $_POST['min_price'] ) : 0;
		$max_price = isset( $_POST['max_price'] ) ? floatval( $_POST['max_price'] ) : PHP_INT_MAX;

		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => intval( get_option( 'posts_per_page' ) ),
			'meta_query'     => array(
				array(
					'key'     => '_price',
					'value'   => array( $min_price, $max_price ),
					'compare' => 'BETWEEN',
					'type'    => 'NUMERIC',
				),
			),
		);

		$q = new WP_Query( $args );

		$items = array();
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$product   = wc_get_product( get_the_ID() );
				$items[] = array(
					'id'        => get_the_ID(),
					'title'     => esc_html( get_the_title() ),
					'permalink' => esc_url( get_permalink() ),
					'price'     => $product ? wp_strip_all_tags( $product->get_price_html() ) : '',
					'thumbnail' => esc_url( get_the_post_thumbnail_url( get_the_ID(), 'hkdev-thumbnail' ) ),
				);
			}
			wp_reset_postdata();
		}

		wp_send_json_success( array( 'items' => $items ) );
	}
}
