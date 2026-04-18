<?php
/**
 * Template part: content for posts.
 *
 * @package HKDEV
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

	<?php hkdev_post_thumbnail(); ?>

	<div class="entry-header">
		<?php if ( is_singular() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
			<h2 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h2>
		<?php endif; ?>

		<div class="entry-meta">
			<?php hkdev_posted_on(); hkdev_posted_by(); ?>
		</div>
	</div>

	<div class="entry-content">
		<?php
		if ( is_singular() ) {
			the_content(
				sprintf(
					wp_kses(
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'hkdev' ),
						array( 'span' => array( 'class' => array() ) )
					),
					wp_kses_post( get_the_title() )
				)
			);
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hkdev' ),
					'after'  => '</div>',
				)
			);
		} else {
			the_excerpt();
		}
		?>
	</div>

	<footer class="entry-footer">
		<?php hkdev_entry_footer(); ?>
	</footer>

</article>
