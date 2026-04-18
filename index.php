<?php
/**
 * The main template file.
 *
 * Used as a fallback when no more-specific template is found.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package HKDEV
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="hkdev-container">

		<?php hkdev_breadcrumbs(); ?>

		<div class="site-content <?php echo is_active_sidebar( 'sidebar-1' ) ? 'has-sidebar' : 'full-width'; ?>">

			<div id="content" class="content-area">
				<?php
				if ( have_posts() ) :

					if ( is_home() && ! is_front_page() ) :
						?>
						<header class="page-header">
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
						<?php
					endif;

					/* Start the Loop */
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', get_post_type() );
					endwhile;

					the_posts_pagination(
						array(
							'prev_text'          => esc_html__( '&laquo; Previous', 'hkdev' ),
							'next_text'          => esc_html__( 'Next &raquo;', 'hkdev' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'hkdev' ) . ' </span>',
						)
					);

				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
			</div><!-- #content -->

			<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>

		</div><!-- .site-content -->
	</div><!-- .hkdev-container -->
</main><!-- #primary -->

<?php
get_footer();
