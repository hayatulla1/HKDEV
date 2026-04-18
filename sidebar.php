<?php
/**
 * Sidebar template.
 *
 * @package HKDEV
 */

$sidebar_id = is_woocommerce() ? 'sidebar-shop' : 'sidebar-1';

if ( ! is_active_sidebar( $sidebar_id ) && ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

$active_sidebar = is_active_sidebar( $sidebar_id ) ? $sidebar_id : 'sidebar-1';
?>

<aside id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( $active_sidebar ); ?>
</aside><!-- #secondary -->
