<?php
/**
 * The template for displaying the footer.
 *
 * @package HKDEV
 */
?>

<?php
// Try Elementor footer first
$elementor_footer_id = get_theme_mod( 'hkdev_elementor_footer_id' );
if ( $elementor_footer_id && class_exists( '\Elementor\Plugin' ) ) :
	echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $elementor_footer_id );
else :
?>
<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="hkdev-container">

		<div class="footer-widgets">

			<!-- Column 1: About -->
			<div class="footer-widget footer-about-col">
				<h3 class="footer-widget-title"><?php bloginfo( 'name' ); ?></h3>
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php else : ?>
					<p class="footer-about"><?php echo esc_html( get_bloginfo( 'description' ) ?: __( 'Your one-stop shop for quality products. We are dedicated to providing the best shopping experience.', 'hkdev' ) ); ?></p>
					<div class="footer-socials">
						<?php
						$socials = array(
							'facebook'  => get_theme_mod( 'hkdev_social_facebook', '#' ),
							'twitter'   => get_theme_mod( 'hkdev_social_twitter', '#' ),
							'instagram' => get_theme_mod( 'hkdev_social_instagram', '#' ),
							'youtube'   => get_theme_mod( 'hkdev_social_youtube', '#' ),
						);
						foreach ( $socials as $name => $url ) :
							if ( $url && $url !== '#' ) :
						?>
						<a href="<?php echo esc_url( $url ); ?>" class="footer-social-link" aria-label="<?php echo esc_attr( ucfirst( $name ) ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( strtoupper( substr( $name, 0, 2 ) ) ); ?>
						</a>
						<?php
							endif;
						endforeach;
						?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Column 2 -->
			<div class="footer-widget">
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<?php dynamic_sidebar( 'footer-2' ); ?>
				<?php else : ?>
					<h4 class="footer-widget-title"><?php esc_html_e( 'Quick Links', 'hkdev' ); ?></h4>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				<?php endif; ?>
			</div>

			<!-- Column 3 -->
			<div class="footer-widget">
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<?php dynamic_sidebar( 'footer-3' ); ?>
				<?php else : ?>
					<h4 class="footer-widget-title"><?php esc_html_e( 'Customer Service', 'hkdev' ); ?></h4>
					<ul>
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e( 'My Account', 'hkdev' ); ?></a></li>
						<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop', 'hkdev' ); ?></a></li>
						<li><a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Checkout', 'hkdev' ); ?></a></li>
						<li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Cart', 'hkdev' ); ?></a></li>
						<?php endif; ?>
						<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact Us', 'hkdev' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>

			<!-- Column 4 -->
			<div class="footer-widget">
				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
					<?php dynamic_sidebar( 'footer-4' ); ?>
				<?php else : ?>
					<h4 class="footer-widget-title"><?php esc_html_e( 'Contact Us', 'hkdev' ); ?></h4>
					<ul>
						<?php $address = get_theme_mod( 'hkdev_contact_address' ); if ( $address ) : ?>
						<li><?php echo esc_html( $address ); ?></li>
						<?php endif; ?>
						<?php $phone = get_theme_mod( 'hkdev_contact_phone' ); if ( $phone ) : ?>
						<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
						<?php endif; ?>
						<?php $email = get_theme_mod( 'hkdev_contact_email' ); if ( $email ) : ?>
						<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div>

		</div><!-- .footer-widgets -->

	</div><!-- .hkdev-container -->

	<div class="footer-bottom">
		<div class="hkdev-container">
			<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
				<span>
					<?php
					printf(
						/* translators: 1: year, 2: site name */
						esc_html__( '© %1$s %2$s. All Rights Reserved.', 'hkdev' ),
						esc_html( gmdate( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</span>
				<span>
					<?php
					printf(
						/* translators: %s: WordPress */
						esc_html__( 'Powered by %s', 'hkdev' ),
						'<a href="https://wordpress.org" target="_blank" rel="noopener">WordPress</a>'
					);
					?>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					&amp; <a href="https://woocommerce.com" target="_blank" rel="noopener">WooCommerce</a>
					<?php endif; ?>
				</span>
			</div>
		</div>
	</div>

</footer><!-- #colophon -->
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
