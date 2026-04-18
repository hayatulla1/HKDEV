<?php
/**
 * Template part: no content found.
 *
 * @package HKDEV
 */
?>
<section class="no-results not-found" style="text-align:center;padding:60px 20px;">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'hkdev' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'hkdev' ),
						array( 'a' => array( 'href' => array() ) )
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, no results were found. Try a different search query.', 'hkdev' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we cannot find what you are looking for. Try searching.', 'hkdev' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
