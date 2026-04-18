<?php
/**
 * Template part: single post content.
 *
 * @package HKDEV
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry single-entry' ); ?>>

	<?php hkdev_post_thumbnail(); ?>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="entry-meta">
			<?php hkdev_posted_on(); hkdev_posted_by(); ?>
			<?php
			$categories = get_the_category_list( ', ' );
			if ( $categories ) {
				echo '<span class="cat-links">' . $categories . '</span>';
			}
			?>
		</div>
	</header>

	<div class="entry-content">
		<?php
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
		?>
	</div>

	<footer class="entry-footer">
		<?php hkdev_entry_footer(); ?>
	</footer>

</article>
