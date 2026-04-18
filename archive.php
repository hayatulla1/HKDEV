<?php
/**
 * The template for displaying archive pages.
 *
 * @package HKDEV
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="hkdev-container">

		<?php hkdev_breadcrumbs(); ?>

		<?php if ( have_posts() ) : ?>
		<header class="page-header" style="margin-bottom:24px;">
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>
		<?php endif; ?>

		<div class="site-content <?php echo is_active_sidebar( 'sidebar-1' ) ? 'has-sidebar' : 'full-width'; ?>">
			<div id="content" class="content-area">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', get_post_type() );
					endwhile;
					the_posts_pagination();
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
			</div>
			<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
		</div>

	</div>
</main>

<?php
get_footer();
