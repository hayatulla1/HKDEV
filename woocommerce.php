<?php
/**
 * WooCommerce main wrapper template.
 *
 * Overrides the default WooCommerce page wrapping so our theme
 * controls the layout rather than WooCommerce's default wrappers.
 *
 * @package HKDEV
 */

get_header( 'shop' );
?>

<main id="primary" class="site-main woocommerce-main">
	<?php
	/**
	 * Hook: woocommerce_before_main_content.
	 * Opens our custom wrappers (handled via HKDEV_WooCommerce::wrapper_start).
	 */
	do_action( 'woocommerce_before_main_content' );

	woocommerce_content();

	/**
	 * Hook: woocommerce_after_main_content.
	 * Closes custom wrappers (handled via HKDEV_WooCommerce::wrapper_end).
	 */
	do_action( 'woocommerce_after_main_content' );

	/**
	 * Hook: woocommerce_sidebar.
	 * Outputs the WooCommerce sidebar.
	 */
	do_action( 'woocommerce_sidebar' );
	?>
	</div><!-- .site-content -->
</main><!-- #primary -->

<?php
get_footer( 'shop' );
