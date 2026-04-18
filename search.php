<?php
/**
 * The template for displaying search results pages.
 *
 * @package HKDEV
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="hkdev-container">

		<?php hkdev_breadcrumbs(); ?>

		<header class="page-header" style="margin-bottom:24px;">
			<h1 class="page-title">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Search Results for: %s', 'hkdev' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<div class="site-content <?php echo is_active_sidebar( 'sidebar-1' ) ? 'has-sidebar' : 'full-width'; ?>">
			<div id="content" class="content-area">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'search' );
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
