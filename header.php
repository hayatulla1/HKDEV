<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'hkdev' ); ?></a>

<?php
// ── Top Bar ──────────────────────────────────────────────────────────────────
$top_bar_text = get_theme_mod( 'hkdev_top_bar_text', __( 'Free Shipping on Orders Over $50 | Use Code: HKDEV10 for 10% Off', 'hkdev' ) );
if ( $top_bar_text ) :
?>
<div class="top-bar" role="banner">
	<div class="hkdev-container">
		<div class="top-bar-inner">
			<span class="top-bar-message"><?php echo wp_kses_post( $top_bar_text ); ?></span>
			<div class="top-bar-links">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'top-bar-menu',
						'container'      => false,
						'menu_class'     => 'top-bar-nav',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
// ── Try Elementor Header first ────────────────────────────────────────────────
$elementor_header_id = get_theme_mod( 'hkdev_elementor_header_id' );
if ( $elementor_header_id && class_exists( '\Elementor\Plugin' ) ) :
	echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $elementor_header_id );
else :
?>
<header id="masthead" class="site-header" role="banner">
	<div class="hkdev-container">
		<div class="site-header-inner">

			<!-- Branding / Logo -->
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<p class="site-title"><?php bloginfo( 'name' ); ?></p>
					<?php
					$description = get_bloginfo( 'description', 'display' );
					if ( $description ) :
					?>
					<p class="site-description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
				</a>
				<?php endif; ?>
			</div><!-- .site-branding -->

			<!-- Mobile Toggle -->
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'hkdev' ); ?>">
				<?php echo hkdev_get_svg_icon( 'menu' ); ?>
			</button>

			<!-- Primary Navigation -->
			<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'hkdev' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'walker'         => class_exists( 'HKDEV_Mega_Menu_Walker' ) ? new HKDEV_Mega_Menu_Walker() : null,
						'fallback_cb'    => 'hkdev_primary_nav_fallback',
					)
				);
				?>
			</nav>

			<!-- Header Actions (Search, Account, Cart) -->
			<div class="header-actions">

				<!-- Search Toggle -->
				<button class="header-search-toggle" aria-label="<?php esc_attr_e( 'Search', 'hkdev' ); ?>" aria-expanded="false">
					<?php echo hkdev_get_svg_icon( 'search' ); ?>
				</button>

				<?php if ( class_exists( 'WooCommerce' ) ) : ?>

				<!-- Account -->
				<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="header-account" aria-label="<?php esc_attr_e( 'My Account', 'hkdev' ); ?>">
					<?php echo hkdev_get_svg_icon( 'user' ); ?>
				</a>

				<!-- Mini Cart Toggle -->
				<button class="header-cart-toggle" aria-label="<?php esc_attr_e( 'Cart', 'hkdev' ); ?>" aria-expanded="false">
					<?php echo hkdev_get_svg_icon( 'cart' ); ?>
					<span class="cart-count hkdev-cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
				</button>

				<?php endif; ?>
			</div><!-- .header-actions -->

		</div><!-- .site-header-inner -->
	</div><!-- .hkdev-container -->

	<!-- Header Search Dropdown -->
	<div class="header-search-panel" id="header-search-panel" aria-hidden="true">
		<form role="search" method="get" class="header-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="header-search-input"><?php esc_html_e( 'Search for:', 'hkdev' ); ?></label>
			<input type="search" id="header-search-input" name="s" placeholder="<?php esc_attr_e( 'Search products, categories…', 'hkdev' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off">
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<button type="submit" aria-label="<?php esc_attr_e( 'Submit Search', 'hkdev' ); ?>">
				<?php echo hkdev_get_svg_icon( 'search' ); ?>
			</button>
		</form>
	</div>
</header><!-- #masthead -->
<?php endif; ?>

<?php
// ── Mobile Navigation Overlay ─────────────────────────────────────────────────
?>
<div class="mobile-nav-overlay" id="mobile-nav-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation Menu', 'hkdev' ); ?>">
	<div class="mobile-nav-panel">
		<div class="mobile-nav-header">
			<strong><?php bloginfo( 'name' ); ?></strong>
			<button class="mobile-nav-close" aria-label="<?php esc_attr_e( 'Close navigation', 'hkdev' ); ?>">
				<?php echo hkdev_get_svg_icon( 'close' ); ?>
			</button>
		</div>
		<nav class="mobile-nav-menu" aria-label="<?php esc_attr_e( 'Mobile Menu', 'hkdev' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'mobile-menu',
					'container'      => false,
					'fallback_cb'    => function() {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'fallback_cb'    => false,
							)
						);
					},
				)
			);
			?>
		</nav>
		<div class="mobile-nav-footer">
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e( 'My Account', 'hkdev' ); ?></a>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Cart', 'hkdev' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
// ── Ajax Mini Cart Slide-out Panel ────────────────────────────────────────────
if ( class_exists( 'WooCommerce' ) ) :
?>
<div class="mini-cart-overlay" id="mini-cart-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Shopping Cart', 'hkdev' ); ?>">
	<div class="mini-cart-panel">
		<div class="mini-cart-header">
			<span><?php esc_html_e( 'Shopping Cart', 'hkdev' ); ?></span>
			<button class="mini-cart-close" aria-label="<?php esc_attr_e( 'Close cart', 'hkdev' ); ?>">
				<?php echo hkdev_get_svg_icon( 'close' ); ?>
			</button>
		</div>
		<div class="mini-cart-body" id="mini-cart-body">
			<div class="mini-cart-loading" id="mini-cart-loading">
				<span class="hkdev-spinner"></span>
			</div>
			<?php do_action( 'woocommerce_before_mini_cart' ); ?>
			<div class="mini-cart-contents hkdev-mini-cart-fragment">
				<?php
				if ( WC()->cart && ! WC()->cart->is_empty() ) :
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
							$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
							$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 64, 64 ) ), $cart_item, $cart_item_key );
							$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<div class="mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<?php echo $thumbnail; ?>
								</a>
								<div class="mini-cart-item-info">
									<div class="mini-cart-item-name">
										<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_name ); ?></a>
									</div>
									<div class="mini-cart-item-qty"><?php echo esc_html( $cart_item['quantity'] ); ?> &times;</div>
									<div class="mini-cart-item-price"><?php echo $product_price; ?></div>
								</div>
								<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="mini-cart-remove" aria-label="<?php esc_attr_e( 'Remove item', 'hkdev' ); ?>"><?php echo hkdev_get_svg_icon( 'close' ); ?></a>
							</div>
							<?php
						endif;
					endforeach;
				else :
					echo '<p class="mini-cart-empty">' . esc_html__( 'Your cart is empty.', 'hkdev' ) . '</p>';
				endif;
				?>
			</div>
			<?php do_action( 'woocommerce_after_mini_cart' ); ?>
		</div><!-- .mini-cart-body -->
		<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>
		<div class="mini-cart-footer">
			<div class="mini-cart-subtotal">
				<span><?php esc_html_e( 'Subtotal:', 'hkdev' ); ?></span>
				<span class="hkdev-cart-subtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
			</div>
			<div class="mini-cart-actions">
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn-view-cart"><?php esc_html_e( 'View Cart', 'hkdev' ); ?></a>
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-checkout"><?php esc_html_e( 'Checkout', 'hkdev' ); ?></a>
			</div>
		</div>
		<?php endif; ?>
	</div><!-- .mini-cart-panel -->
</div><!-- .mini-cart-overlay -->
<?php endif; ?>
