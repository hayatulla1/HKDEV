<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package HKDEV
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="hkdev-container">
		<div class="error-404 not-found">
			<div class="error-code">404</div>
			<h1><?php esc_html_e( 'Page Not Found', 'hkdev' ); ?></h1>
			<p><?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'hkdev' ); ?></p>
			<div class="error-actions" style="margin-top:24px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Go to Homepage', 'hkdev' ); ?></a>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Shop Now', 'hkdev' ); ?></a>
				<?php endif; ?>
			</div>
			<div style="margin-top:32px;max-width:480px;margin-left:auto;margin-right:auto;">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
