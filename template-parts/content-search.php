<?php
/**
 * Template part: search result item.
 *
 * @package HKDEV
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry search-entry' ); ?>>

	<?php hkdev_post_thumbnail(); ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<div class="entry-meta">
			<?php if ( 'post' === get_post_type() ) : ?>
				<?php hkdev_posted_on(); ?>
			<?php endif; ?>
		</div>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>

	<footer class="entry-footer">
		<a href="<?php the_permalink(); ?>" class="btn btn-primary" style="font-size:0.85rem;padding:8px 16px;"><?php esc_html_e( 'Read More', 'hkdev' ); ?></a>
	</footer>

</article>
