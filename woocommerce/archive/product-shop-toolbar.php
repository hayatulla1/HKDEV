<?php
/**
 * WooCommerce: Product archive / shop page toolbar.
 * Overrides woocommerce/archive-product.php header area.
 *
 * Place in: woocommerce/archive/
 * Hooked via: woocommerce_before_shop_loop
 *
 * @package HKDEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="hkdev-shop-toolbar">

	<div class="shop-view-toggle" role="group" aria-label="<?php esc_attr_e( 'Product view', 'hkdev' ); ?>">
		<button data-view="grid" class="is-active" aria-label="<?php esc_attr_e( 'Grid view', 'hkdev' ); ?>" title="<?php esc_attr_e( 'Grid view', 'hkdev' ); ?>">
			<?php echo hkdev_get_svg_icon( 'grid' ); ?>
		</button>
		<button data-view="list" aria-label="<?php esc_attr_e( 'List view', 'hkdev' ); ?>" title="<?php esc_attr_e( 'List view', 'hkdev' ); ?>">
			<?php echo hkdev_get_svg_icon( 'list' ); ?>
		</button>
	</div>

	<p class="woocommerce-result-count">
		<?php woocommerce_result_count(); ?>
	</p>

	<div class="shop-sort">
		<?php woocommerce_catalog_ordering(); ?>
	</div>

</div>

<!-- Price range filter -->
<?php
$max_price = (int) ceil( (float) apply_filters( 'woocommerce_price_filter_widget_max_amount', wc_get_max_price_in_range( 0, PHP_INT_MAX ) ) );
$min_price = (int) floor( (float) apply_filters( 'woocommerce_price_filter_widget_min_amount', 0 ) );
$curr_min  = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $min_price;
$curr_max  = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $max_price;
?>
<div class="price-range-filter">
	<label><?php esc_html_e( 'Filter by Price', 'hkdev' ); ?></label>
	<div class="price-slider-wrap">
		<div class="price-range-track">
			<div class="price-range-fill"></div>
		</div>
		<input type="range" id="price-range-min" class="price-slider" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" value="<?php echo esc_attr( $curr_min ); ?>" step="1">
		<input type="range" id="price-range-max" class="price-slider" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" value="<?php echo esc_attr( $curr_max ); ?>" step="1">
	</div>
	<div class="price-inputs">
		<span><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>
		<input type="number" id="price-min-input" value="<?php echo esc_attr( $curr_min ); ?>" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>">
		<span>—</span>
		<input type="number" id="price-max-input" value="<?php echo esc_attr( $curr_max ); ?>" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>">
		<button type="button" class="btn btn-primary" style="padding:6px 14px;font-size:0.85rem;" id="price-filter-apply"><?php esc_html_e( 'Filter', 'hkdev' ); ?></button>
	</div>
</div>
