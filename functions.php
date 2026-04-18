<?php
/**
 * HKDEV Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package HKDEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// Autoload modular class files
// ─────────────────────────────────────────────────────────────────────────────
$hkdev_includes = array(
	'/inc/class-hkdev-woocommerce.php',
	'/inc/class-hkdev-elementor.php',
	'/inc/class-hkdev-ajax.php',
	'/inc/class-hkdev-navigation.php',
	'/inc/class-hkdev-mini-cart.php',
);

foreach ( $hkdev_includes as $file ) {
	$filepath = get_template_directory() . $file;
	if ( file_exists( $filepath ) ) {
		require_once $filepath;
	}
}

// ─────────────────────────────────────────────────────────────────────────────
// Main Theme Class
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Class HKDEV_Theme
 *
 * Bootstraps the theme: constants, setup, scripts, and sub-module instantiation.
 */
final class HKDEV_Theme {

	/** @var string Current theme version. */
	const VERSION = '1.0.0';

	/** @var HKDEV_Theme|null Singleton instance. */
	private static $instance = null;

	/**
	 * Returns the singleton instance.
	 *
	 * @return HKDEV_Theme
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/** Private constructor – use get_instance(). */
	private function __construct() {
		$this->define_constants();
		$this->init_hooks();
		$this->boot_modules();
	}

	// ── Constants ─────────────────────────────────────────────────────────────

	private function define_constants() {
		define( 'HKDEV_VERSION', self::VERSION );
		define( 'HKDEV_DIR',     get_template_directory() );
		define( 'HKDEV_URI',     get_template_directory_uri() );
		define( 'HKDEV_INC',     HKDEV_DIR . '/inc' );
		define( 'HKDEV_ASSETS',  HKDEV_URI . '/assets' );
	}

	// ── Hooks ─────────────────────────────────────────────────────────────────

	private function init_hooks() {
		add_action( 'after_setup_theme',  array( $this, 'theme_setup' ) );
		add_action( 'widgets_init',       array( $this, 'widgets_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Remove Gutenberg block styles when Elementor is active
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_block_styles' ), 100 );

		// Body classes
		add_filter( 'body_class', array( $this, 'body_classes' ) );

		// Excerpt length
		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 999 );
		add_filter( 'excerpt_more',   array( $this, 'excerpt_more' ) );

		// Title separator
		add_filter( 'document_title_separator', array( $this, 'title_separator' ) );

		// Ping-back header
		add_action( 'wp', array( $this, 'add_pingback_header' ) );
	}

	// ── Module boot ───────────────────────────────────────────────────────────

	private function boot_modules() {
		if ( class_exists( 'HKDEV_WooCommerce' ) ) {
			HKDEV_WooCommerce::get_instance();
		}
		if ( class_exists( 'HKDEV_Elementor' ) ) {
			HKDEV_Elementor::get_instance();
		}
		if ( class_exists( 'HKDEV_Ajax' ) ) {
			HKDEV_Ajax::get_instance();
		}
		if ( class_exists( 'HKDEV_Navigation' ) ) {
			HKDEV_Navigation::get_instance();
		}
		if ( class_exists( 'HKDEV_Mini_Cart' ) ) {
			HKDEV_Mini_Cart::get_instance();
		}
	}

	// ── Theme Setup ───────────────────────────────────────────────────────────

	/**
	 * Fires on `after_setup_theme`. Registers all theme supports and nav menus.
	 */
	public function theme_setup() {

		// Translations
		load_theme_textdomain( 'hkdev', HKDEV_DIR . '/languages' );

		// Core supports
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );

		// HTML5 markup
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Custom logo
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 80,
				'width'       => 200,
				'flex-width'  => true,
				'flex-height' => true,
				'header-text' => array( 'site-title', 'site-description' ),
			)
		);

		// Custom background
		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'ffffff',
			)
		);

		// Image sizes
		add_image_size( 'hkdev-thumbnail',   300, 300, true );
		add_image_size( 'hkdev-medium',      600, 600, true );
		add_image_size( 'hkdev-large',       900, 600, false );
		add_image_size( 'hkdev-banner',     1280, 520, true );
		add_image_size( 'hkdev-category',    200, 200, true );

		// Navigation menus
		register_nav_menus(
			array(
				'primary'      => esc_html__( 'Primary / Mega Menu', 'hkdev' ),
				'mobile-menu'  => esc_html__( 'Mobile App-Style Menu', 'hkdev' ),
				'top-bar-menu' => esc_html__( 'Top Bar Menu', 'hkdev' ),
				'footer-menu'  => esc_html__( 'Footer Menu', 'hkdev' ),
			)
		);

		// ── WooCommerce Supports ────────────────────────────────────────────
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 300,
				'single_image_width'    => 600,
				'product_grid'          => array(
					'default_rows'    => 3,
					'min_rows'        => 1,
					'max_rows'        => 10,
					'default_columns' => 4,
					'min_columns'     => 2,
					'max_columns'     => 5,
				),
			)
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	// ── Widgets ───────────────────────────────────────────────────────────────

	public function widgets_init() {
		$sidebars = array(
			array(
				'id'   => 'sidebar-1',
				'name' => esc_html__( 'Main Sidebar', 'hkdev' ),
			),
			array(
				'id'   => 'sidebar-shop',
				'name' => esc_html__( 'Shop Sidebar', 'hkdev' ),
			),
			array(
				'id'   => 'footer-1',
				'name' => esc_html__( 'Footer Column 1', 'hkdev' ),
			),
			array(
				'id'   => 'footer-2',
				'name' => esc_html__( 'Footer Column 2', 'hkdev' ),
			),
			array(
				'id'   => 'footer-3',
				'name' => esc_html__( 'Footer Column 3', 'hkdev' ),
			),
			array(
				'id'   => 'footer-4',
				'name' => esc_html__( 'Footer Column 4', 'hkdev' ),
			),
		);

		$defaults = array(
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		);

		foreach ( $sidebars as $sidebar ) {
			register_sidebar(
				array_merge(
					$defaults,
					array(
						'id'   => $sidebar['id'],
						'name' => $sidebar['name'],
					)
				)
			);
		}
	}

	// ── Styles ────────────────────────────────────────────────────────────────

	public function enqueue_styles() {
		// Main theme stylesheet
		wp_enqueue_style(
			'hkdev-style',
			get_stylesheet_uri(),
			array(),
			HKDEV_VERSION
		);

		// Swiper CSS (only on pages that use carousels)
		if ( $this->needs_swiper() ) {
			wp_enqueue_style(
				'swiper-css',
				HKDEV_ASSETS . '/vendor/swiper/swiper-bundle.min.css',
				array(),
				'11.1.4'
			);
		}

		// Google Fonts (optional – swap or remove as needed)
		wp_enqueue_style(
			'hkdev-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
			array(),
			null
		);

		// Dashicons on front-end for icon usage
		wp_enqueue_style( 'dashicons' );
	}

	// ── Scripts ───────────────────────────────────────────────────────────────

	public function enqueue_scripts() {
		// Main JS bundle (depends on jquery which WP provides)
		wp_enqueue_script(
			'hkdev-main',
			HKDEV_ASSETS . '/js/main.js',
			array( 'jquery' ),
			HKDEV_VERSION,
			true
		);

		// Navigation JS
		wp_enqueue_script(
			'hkdev-navigation',
			HKDEV_ASSETS . '/js/navigation.js',
			array(),
			HKDEV_VERSION,
			true
		);

		// Swiper JS
		if ( $this->needs_swiper() ) {
			wp_enqueue_script(
				'swiper-js',
				HKDEV_ASSETS . '/vendor/swiper/swiper-bundle.min.js',
				array(),
				'11.1.4',
				true
			);
			wp_enqueue_script(
				'hkdev-carousels',
				HKDEV_ASSETS . '/js/carousels.js',
				array( 'swiper-js' ),
				HKDEV_VERSION,
				true
			);
		}

		// Single product gallery script
		if ( is_singular( 'product' ) ) {
			wp_enqueue_script(
				'hkdev-product-gallery',
				HKDEV_ASSETS . '/js/product-gallery.js',
				array( 'jquery' ),
				HKDEV_VERSION,
				true
			);
		}

		// Comment reply script
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Localized data for JS (Ajax URL, nonces, i18n strings)
		wp_localize_script(
			'hkdev-main',
			'HKDEV',
			array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'hkdev_nonce' ),
				'wc_ajax_url'   => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
				'cart_url'      => class_exists( 'WooCommerce' ) ? wc_get_cart_url() : '',
				'checkout_url'  => class_exists( 'WooCommerce' ) ? wc_get_checkout_url() : '',
				'currency'      => class_exists( 'WooCommerce' ) ? get_woocommerce_currency_symbol() : '',
				'i18n'          => array(
					'added_to_cart'    => esc_html__( 'Added to cart!', 'hkdev' ),
					'adding'           => esc_html__( 'Adding…', 'hkdev' ),
					'remove'           => esc_html__( 'Remove', 'hkdev' ),
					'view_cart'        => esc_html__( 'View Cart', 'hkdev' ),
					'checkout'         => esc_html__( 'Checkout', 'hkdev' ),
					'empty_cart'       => esc_html__( 'Your cart is empty.', 'hkdev' ),
				),
			)
		);
	}

	/**
	 * Determine if Swiper is needed on the current request.
	 *
	 * @return bool
	 */
	private function needs_swiper() {
		return is_front_page() || is_shop() || is_product_category() || is_page();
	}

	/**
	 * Dequeue Gutenberg block styles when Elementor renders a page.
	 */
	public function dequeue_block_styles() {
		if ( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return;
		}
		// Optionally dequeue heavy WooCommerce default styles and replace with ours.
		// Uncomment below if you have complete custom WooCommerce CSS:
		// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
	}

	// ── Body Classes ──────────────────────────────────────────────────────────

	public function body_classes( $classes ) {
		if ( is_singular() && ! is_front_page() ) {
			$classes[] = 'singular';
		}

		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'has-sidebar';
		} else {
			$classes[] = 'no-sidebar';
		}

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$classes[] = 'elementor-theme';
		}

		return $classes;
	}

	// ── Misc filters ──────────────────────────────────────────────────────────

	public function excerpt_length() {
		return 20;
	}

	public function excerpt_more() {
		return '&hellip; <a class="read-more" href="' . get_permalink() . '">' . esc_html__( 'Read More', 'hkdev' ) . '</a>';
	}

	public function title_separator() {
		return '|';
	}

	public function add_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}

// ─────────────────────────────────────────────────────────────────────────────
// Bootstrap
// ─────────────────────────────────────────────────────────────────────────────
HKDEV_Theme::get_instance();

// ─────────────────────────────────────────────────────────────────────────────
// Template Helpers (global functions for use inside templates)
// ─────────────────────────────────────────────────────────────────────────────

if ( ! function_exists( 'hkdev_posted_on' ) ) {
	/**
	 * Print posted-on meta with schema.
	 */
	function hkdev_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		printf(
			'<span class="posted-on"><a href="%1$s" rel="bookmark">%2$s</a></span>',
			esc_url( get_permalink() ),
			$time_string
		);
	}
}

if ( ! function_exists( 'hkdev_posted_by' ) ) {
	/**
	 * Print posted-by meta.
	 */
	function hkdev_posted_by() {
		printf(
			'<span class="byline"> %1$s <span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span></span>',
			esc_html__( 'by', 'hkdev' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
}

if ( ! function_exists( 'hkdev_entry_footer' ) ) {
	/**
	 * Print categories, tags, and edit link.
	 */
	function hkdev_entry_footer() {
		if ( 'post' !== get_post_type() ) {
			return;
		}

		$categories = get_the_category_list( esc_html__( ', ', 'hkdev' ) );
		if ( $categories ) {
			printf( '<span class="cat-links">%1$s %2$s</span>', esc_html__( 'Posted in', 'hkdev' ), $categories );
		}

		$tags = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'hkdev' ) );
		if ( $tags ) {
			printf( '<span class="tags-links">%1$s %2$s</span>', esc_html__( 'Tagged', 'hkdev' ), $tags );
		}

		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'hkdev' ),
					array( 'span' => array( 'class' => array() ) )
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

if ( ! function_exists( 'hkdev_post_thumbnail' ) ) {
	/**
	 * Display post thumbnail or placeholder.
	 */
	function hkdev_post_thumbnail() {
		if ( ! has_post_thumbnail() || is_attachment() ) {
			return;
		}

		if ( is_singular() ) {
			echo '<div class="post-thumbnail">';
			the_post_thumbnail( 'hkdev-large' );
			echo '</div>';
		} else {
			echo '<a class="post-thumbnail" href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">';
			the_post_thumbnail( 'hkdev-medium', array( 'alt' => '' ) );
			echo '</a>';
		}
	}
}

if ( ! function_exists( 'hkdev_get_svg_icon' ) ) {
	/**
	 * Return an inline SVG icon by name for use in templates.
	 *
	 * @param string $name  Icon name (cart, search, user, heart, etc.).
	 * @param string $class Additional CSS classes.
	 * @return string
	 */
	function hkdev_get_svg_icon( $name, $class = '' ) {
		$icons = array(
			'cart'   => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
			'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
			'user'   => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
			'heart'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
			'menu'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
			'close'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
			'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>',
			'star'   => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
			'grid'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
			'list'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
		);

		if ( ! isset( $icons[ $name ] ) ) {
			return '';
		}

		$svg = $icons[ $name ];
		if ( $class ) {
			$svg = str_replace( '<svg', '<svg class="' . esc_attr( $class ) . '"', $svg );
		}
		return $svg;
	}
}

if ( ! function_exists( 'hkdev_breadcrumbs' ) ) {
	/**
	 * Output breadcrumb trail. Integrates with WooCommerce if active.
	 */
	function hkdev_breadcrumbs() {
		// Let WooCommerce handle breadcrumbs on WC pages
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			return;
		}

		$sep   = '<span class="sep">›</span>';
		$trail = array();

		$trail[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'hkdev' ) . '</a>';

		if ( is_category() || is_single() ) {
			$categories = get_the_category();
			if ( $categories ) {
				$trail[] = '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
			}
			if ( is_single() ) {
				$trail[] = '<span>' . esc_html( get_the_title() ) . '</span>';
			}
		} elseif ( is_page() ) {
			$trail[] = '<span>' . esc_html( get_the_title() ) . '</span>';
		} elseif ( is_search() ) {
			$trail[] = '<span>' . sprintf( esc_html__( 'Search: "%s"', 'hkdev' ), get_search_query() ) . '</span>';
		} elseif ( is_404() ) {
			$trail[] = '<span>' . esc_html__( '404 Not Found', 'hkdev' ) . '</span>';
		} elseif ( is_archive() ) {
			$trail[] = '<span>' . esc_html( get_the_archive_title() ) . '</span>';
		}

		if ( count( $trail ) > 1 ) {
			echo '<nav aria-label="' . esc_attr__( 'Breadcrumb', 'hkdev' ) . '" class="hkdev-breadcrumbs">';
			echo implode( ' ' . $sep . ' ', $trail );
			echo '</nav>';
		}
	}
}
