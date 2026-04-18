<?php
/**
 * WooCommerce integration class.
 *
 * Handles Ajax add-to-cart, fragments, cart mini-widget,
 * template overrides and product display customisations.
 *
 * @package HKDEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class HKDEV_WooCommerce
 */
class HKDEV_WooCommerce {

	/** @var HKDEV_WooCommerce|null */
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

	// ── Hooks ─────────────────────────────────────────────────────────────────

	private function hooks() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Remove default WooCommerce wrappers so we control the layout
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );

		// Add our own wrappers
		add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content',  array( $this, 'wrapper_end' ), 10 );

		// Shop sidebar (after content wrapper)
		add_action( 'woocommerce_sidebar', 'get_sidebar', 10 );

		// Remove default breadcrumbs (we use ours)
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// Cart fragments for Ajax mini-cart update
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_count_fragment' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_subtotal_fragment' ) );

		// Product loop: customise output
		add_filter( 'woocommerce_loop_add_to_cart_args',  array( $this, 'loop_add_to_cart_args' ), 10, 2 );

		// Single product: move elements
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 25 );

		// Stock status text filter
		add_filter( 'woocommerce_get_availability_text', array( $this, 'availability_text' ), 10, 2 );

		// Pagination args
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		// Product columns
		add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );

		// Products per page
		add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ), 20 );

		// Ajax add to cart handler
		add_action( 'wp_ajax_hkdev_add_to_cart',        array( $this, 'ajax_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_hkdev_add_to_cart', array( $this, 'ajax_add_to_cart' ) );

		// Ajax get cart fragment
		add_action( 'wp_ajax_hkdev_get_cart_fragment',        array( $this, 'ajax_get_cart_fragment' ) );
		add_action( 'wp_ajax_nopriv_hkdev_get_cart_fragment', array( $this, 'ajax_get_cart_fragment' ) );

		// Ensure WooCommerce scripts load (needed for fragments)
		add_action( 'wp_enqueue_scripts', array( $this, 'ensure_wc_scripts' ) );
	}

	// ── Layout Wrappers ───────────────────────────────────────────────────────

	public function wrapper_start() {
		echo '<div class="hkdev-container"><div class="site-content has-sidebar"><div id="content" class="content-area">';
	}

	public function wrapper_end() {
		echo '</div>';
	}

	// ── Cart Fragments ────────────────────────────────────────────────────────

	/**
	 * Update cart count badge via WC fragment system.
	 *
	 * @param array $fragments
	 * @return array
	 */
	public function cart_count_fragment( $fragments ) {
		$count = WC()->cart ? intval( WC()->cart->get_cart_contents_count() ) : 0;
		$fragments['.hkdev-cart-count'] = '<span class="cart-count hkdev-cart-count">' . esc_html( $count ) . '</span>';
		return $fragments;
	}

	/**
	 * Update cart subtotal via WC fragment system.
	 *
	 * @param array $fragments
	 * @return array
	 */
	public function cart_subtotal_fragment( $fragments ) {
		$subtotal = WC()->cart ? WC()->cart->get_cart_subtotal() : '';
		$fragments['.hkdev-cart-subtotal'] = '<span class="hkdev-cart-subtotal">' . $subtotal . '</span>';
		return $fragments;
	}

	// ── Product Loop ──────────────────────────────────────────────────────────

	public function loop_add_to_cart_args( $args, $product ) {
		$args['class'] = implode(
			' ',
			array_filter(
				array(
					'btn-add-cart',
					'add_to_cart_button',
					$product->is_type( 'simple' ) ? 'ajax_add_to_cart' : '',
					$product->is_purchasable() && $product->is_in_stock() ? '' : 'btn-secondary',
				)
			)
		);
		return $args;
	}

	public function loop_columns() {
		return 4;
	}

	public function products_per_page() {
		return 16;
	}

	// ── Availability ─────────────────────────────────────────────────────────

	public function availability_text( $text, $product ) {
		if ( $product->is_in_stock() ) {
			return esc_html__( 'In Stock', 'hkdev' );
		}
		return esc_html__( 'Out of Stock', 'hkdev' );
	}

	// ── Pagination ───────────────────────────────────────────────────────────

	public function pagination_args( $args ) {
		$args['prev_text'] = '&laquo;';
		$args['next_text'] = '&raquo;';
		return $args;
	}

	// ── Scripts ───────────────────────────────────────────────────────────────

	public function ensure_wc_scripts() {
		if ( function_exists( 'WC' ) ) {
			wp_enqueue_script( 'wc-cart-fragments' );
		}
	}

	// ── Ajax: Add to Cart ─────────────────────────────────────────────────────

	/**
	 * Handle Ajax add-to-cart request.
	 * Verifies nonce, adds product, returns fragments and updated counts.
	 */
	public function ajax_add_to_cart() {
		check_ajax_referer( 'hkdev_nonce', 'nonce' );

		$product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$quantity     = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$variation    = isset( $_POST['variation'] ) ? (array) $_POST['variation'] : array();

		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product.', 'hkdev' ) ) );
		}

		// Sanitize variation data
		$variation = array_map( 'sanitize_text_field', $variation );

		$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

		if ( $added ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			// Return fragments
			ob_start();
			// Cart count
			$count = WC()->cart->get_cart_contents_count();

			wp_send_json_success(
				array(
					'count'     => $count,
					'subtotal'  => WC()->cart->get_cart_subtotal(),
					'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array() ),
					'cart_hash' => WC()->cart->get_cart_hash(),
					'message'   => __( 'Product added to cart!', 'hkdev' ),
				)
			);
		} else {
			$notices = wc_get_notices( 'error' );
			wc_clear_notices();
			$message = ! empty( $notices ) ? $notices[0]['notice'] : __( 'Could not add to cart.', 'hkdev' );
			wp_send_json_error( array( 'message' => wp_strip_all_tags( $message ) ) );
		}
	}

	// ── Ajax: Get Cart Fragment ───────────────────────────────────────────────

	public function ajax_get_cart_fragment() {
		check_ajax_referer( 'hkdev_nonce', 'nonce' );

		wp_send_json_success(
			array(
				'count'    => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
				'subtotal' => WC()->cart ? WC()->cart->get_cart_subtotal() : '',
				'items'    => $this->get_cart_items_data(),
			)
		);
	}

	/**
	 * Return structured cart items array for JS rendering.
	 *
	 * @return array
	 */
	private function get_cart_items_data() {
		$items = array();
		if ( ! WC()->cart ) {
			return $items;
		}
		foreach ( WC()->cart->get_cart() as $key => $item ) {
			$product = $item['data'];
			if ( ! $product ) {
				continue;
			}
			$items[] = array(
				'key'       => esc_attr( $key ),
				'name'      => esc_html( $product->get_name() ),
				'qty'       => intval( $item['quantity'] ),
				'price'     => WC()->cart->get_product_price( $product ),
				'image'     => wp_get_attachment_url( $product->get_image_id() ),
				'permalink' => esc_url( $product->get_permalink() ),
				'remove'    => esc_url( wc_get_cart_remove_url( $key ) ),
			);
		}
		return $items;
	}
}
