<?php
/**
 * Navigation class.
 *
 * Provides the custom mega-menu Walker, registers Customizer controls
 * for nav settings, and handles the fallback primary nav.
 *
 * @package HKDEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class HKDEV_Navigation
 */
class HKDEV_Navigation {

	/** @var HKDEV_Navigation|null */
	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'customize_register', array( $this, 'customizer_settings' ) );
	}

	public function customizer_settings( $wp_customize ) {
		$wp_customize->add_section(
			'hkdev_nav',
			array(
				'title'    => esc_html__( 'Navigation', 'hkdev' ),
				'priority' => 40,
			)
		);

		// Top bar text
		$wp_customize->add_setting(
			'hkdev_top_bar_text',
			array(
				'default'           => __( 'Free Shipping on Orders Over $50 | Use Code: HKDEV10', 'hkdev' ),
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'hkdev_top_bar_text',
			array(
				'label'   => esc_html__( 'Top Bar Announcement Text', 'hkdev' ),
				'section' => 'hkdev_nav',
				'type'    => 'textarea',
			)
		);
	}
}

// ─────────────────────────────────────────────────────────────────────────────
// Mega Menu Walker
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Class HKDEV_Mega_Menu_Walker
 *
 * Extends Walker_Nav_Menu to support mega-menu column layouts.
 * Add the CSS class "mega-menu" to a top-level menu item in
 * Appearance > Menus to enable the mega-menu wrapper for that item.
 */
class HKDEV_Mega_Menu_Walker extends Walker_Nav_Menu {

	/** @var bool Whether current item is a mega-menu parent. */
	private $is_mega = false;

	/** @var int Depth at which mega columns start. */
	private $mega_depth = 0;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string $output
	 * @param int    $depth
	 * @param object $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $this->is_mega && $depth === 1 ) {
			$output .= '<div class="mega-menu-wrapper">';
			return;
		}
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @param string $output
	 * @param int    $depth
	 * @param object $args
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $this->is_mega && $depth === 1 ) {
			$output .= '</div>';
			return;
		}
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	/**
	 * Start the element output (open <li> tag).
	 *
	 * @param string $output
	 * @param object $item
	 * @param int    $depth
	 * @param object $args
	 * @param int    $id
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Detect mega-menu at top level
		if ( $depth === 0 && in_array( 'mega-menu', (array) $item->classes, true ) ) {
			$this->is_mega    = true;
			$this->mega_depth = $depth;
		}

		// For second-level items inside a mega-menu, wrap in column divs
		if ( $this->is_mega && $depth === 1 ) {
			$output .= '<div class="mega-menu-column">';
			// Output the column heading as <h4>
			$output .= '<h4>' . esc_html( $item->title ) . '</h4>';
			$output .= '<ul>';
			return;
		}

		// Default Walker behaviour
		parent::start_el( $output, $item, $depth, $args, $id );
	}

	/**
	 * End the element output (close <li> tag).
	 *
	 * @param string $output
	 * @param object $item
	 * @param int    $depth
	 * @param object $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $this->is_mega && $depth === 1 ) {
			$output .= '</ul></div>';
			return;
		}

		parent::end_el( $output, $item, $depth, $args );

		// Reset mega state after top-level item closes
		if ( $depth === 0 ) {
			$this->is_mega = false;
		}
	}
}

// ─────────────────────────────────────────────────────────────────────────────
// Primary Nav Fallback
// ─────────────────────────────────────────────────────────────────────────────

if ( ! function_exists( 'hkdev_primary_nav_fallback' ) ) {
	/**
	 * Fallback for when no primary menu is assigned.
	 * Outputs a list of pages as a simple menu.
	 */
	function hkdev_primary_nav_fallback() {
		wp_page_menu(
			array(
				'menu_class' => 'nav-menu',
				'before'     => '',
				'after'      => '',
				'link_before' => '',
				'link_after'  => '',
			)
		);
	}
}
