<?php
/**
 * WooCommerce single product gallery override.
 * Provides a custom gallery with main image + thumbnails.
 *
 * Replaces: woocommerce/single-product/product-image.php
 *
 * @package HKDEV
 */

defined( 'ABSPATH' ) || exit;

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>
<div class="product-gallery <?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>"
	data-columns="<?php echo esc_attr( $columns ); ?>">

	<!-- Main Image -->
	<div class="gallery-main woocommerce-product-gallery__trigger-wrapper">
		<?php
		if ( $post_thumbnail_id ) {
			$full_size_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
			$thumbnail       = get_the_post_thumbnail_url( $product->get_id(), 'hkdev-medium' );
			?>
			<a href="<?php echo esc_url( $full_size_image[0] ?? $thumbnail ); ?>" class="woocommerce-product-gallery__trigger" data-src="<?php echo esc_url( $full_size_image[0] ?? $thumbnail ); ?>">
				<img src="<?php echo esc_url( $thumbnail ); ?>"
					alt="<?php echo esc_attr( $product->get_name() ); ?>"
					width="600"
					height="600"
					loading="eager"
					class="wp-post-image">
			</a>
			<?php
		} else {
			echo '<img src="' . esc_url( wc_placeholder_img_src( 'hkdev-medium' ) ) . '" alt="' . esc_attr__( 'Placeholder', 'hkdev' ) . '" class="wp-post-image">';
		}
		?>
	</div>

	<!-- Thumbnails -->
	<?php
	$attachment_ids = $product->get_gallery_image_ids();

	if ( $post_thumbnail_id ) {
		array_unshift( $attachment_ids, $post_thumbnail_id );
	}

	if ( count( $attachment_ids ) > 1 ) :
	?>
	<div class="gallery-thumbs woocommerce-product-gallery__wrapper">
		<?php foreach ( $attachment_ids as $index => $attachment_id ) :
			$thumb_src = wp_get_attachment_image_src( $attachment_id, 'hkdev-thumbnail' );
			$full_src  = wp_get_attachment_image_src( $attachment_id, 'full' );
			?>
			<div class="gallery-thumb <?php echo 0 === $index ? 'is-active' : ''; ?>"
				data-full="<?php echo esc_url( $full_src ? $full_src[0] : '' ); ?>">
				<img src="<?php echo esc_url( $thumb_src ? $thumb_src[0] : wc_placeholder_img_src() ); ?>"
					alt="<?php echo esc_attr( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ); ?>"
					width="72"
					height="72"
					loading="lazy">
			</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

</div>
