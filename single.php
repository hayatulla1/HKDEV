<?php
/**
 * The template for displaying all single posts.
 *
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
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'single' );

					the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'hkdev' ) . '</span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'hkdev' ) . '</span> <span class="nav-title">%title</span>',
						)
					);

					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

				endwhile;
				?>
			</div><!-- #content -->

			<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>

		</div>
	</div>
</main>

<?php
get_footer();
