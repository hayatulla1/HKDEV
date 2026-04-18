<?php
/**
 * Elementor integration class.
 *
 * Registers widget categories, declares theme support, and provides
 * helpers for Elementor-controlled header/footer.
 *
 * @package HKDEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class HKDEV_Elementor
 */
class HKDEV_Elementor {

	/** @var HKDEV_Elementor|null */
	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return;
		}
		$this->hooks();
	}

	private function hooks() {
		// Register a custom HKDEV widget category in Elementor
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_widget_categories' ) );

		// After Elementor init: register custom widgets if any
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Customizer: expose header/footer page selection
		add_action( 'customize_register', array( $this, 'customizer_settings' ) );

		// Body class when Elementor page is full-width
		add_filter( 'body_class', array( $this, 'elementor_page_body_class' ) );

		// Hide title on Elementor pages
		add_filter( 'the_title', array( $this, 'hide_title_on_elementor_page' ), 10, 2 );
	}

	// ── Widget Category ───────────────────────────────────────────────────────

	public function register_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'hkdev',
			array(
				'title' => esc_html__( 'HKDEV Theme', 'hkdev' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	// ── Custom Widgets ────────────────────────────────────────────────────────

	public function register_widgets( $widgets_manager ) {
		// Register custom Elementor widgets here when created.
		// Example:
		// require_once HKDEV_INC . '/elementor/class-widget-product-carousel.php';
		// $widgets_manager->register( new HKDEV_Widget_Product_Carousel() );
	}

	// ── Customizer ───────────────────────────────────────────────────────────

	public function customizer_settings( $wp_customize ) {
		$wp_customize->add_section(
			'hkdev_elementor',
			array(
				'title'    => esc_html__( 'Elementor Theme Builder', 'hkdev' ),
				'priority' => 30,
			)
		);

		// Header page ID
		$wp_customize->add_setting(
			'hkdev_elementor_header_id',
			array(
				'default'           => '',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'hkdev_elementor_header_id',
			array(
				'label'       => esc_html__( 'Elementor Header Page ID', 'hkdev' ),
				'description' => esc_html__( 'Enter the ID of the page/template built with Elementor to use as the header.', 'hkdev' ),
				'section'     => 'hkdev_elementor',
				'type'        => 'number',
			)
		);

		// Footer page ID
		$wp_customize->add_setting(
			'hkdev_elementor_footer_id',
			array(
				'default'           => '',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'hkdev_elementor_footer_id',
			array(
				'label'       => esc_html__( 'Elementor Footer Page ID', 'hkdev' ),
				'description' => esc_html__( 'Enter the ID of the page/template built with Elementor to use as the footer.', 'hkdev' ),
				'section'     => 'hkdev_elementor',
				'type'        => 'number',
			)
		);
	}

	// ── Body Class ────────────────────────────────────────────────────────────

	public function elementor_page_body_class( $classes ) {
		if ( is_singular() ) {
			$document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
			if ( $document && $document->is_built_with_elementor() ) {
				$classes[] = 'elementor-page';
				$classes[] = 'full-width';
			}
		}
		return $classes;
	}

	// ── Hide Title ────────────────────────────────────────────────────────────

	public function hide_title_on_elementor_page( $title, $id ) {
		if ( is_singular() && $id === get_the_ID() ) {
			$document = \Elementor\Plugin::$instance->documents->get( $id );
			if ( $document && $document->is_built_with_elementor() ) {
				$hide = get_post_meta( $id, '_elementor_page_settings', true );
				if ( is_array( $hide ) && isset( $hide['hide_title'] ) && 'yes' === $hide['hide_title'] ) {
					return '';
				}
			}
		}
		return $title;
	}
}
