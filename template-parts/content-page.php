<?php
/**
 * Template part: page content.
 *
 * @package HKDEV
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry page-entry' ); ?>>

	<?php if ( ! is_front_page() ) : ?>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>
	<?php endif; ?>

	<?php hkdev_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hkdev' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>

	<footer class="entry-footer">
		<?php edit_post_link( esc_html__( 'Edit Page', 'hkdev' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>

</article>
