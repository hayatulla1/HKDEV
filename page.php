<?php
/**
 * The template for displaying all pages.
 *
 * @package HKDEV
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="hkdev-container">

		<?php hkdev_breadcrumbs(); ?>

		<div class="site-content full-width">
			<div id="content" class="content-area">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'page' );

					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
				?>
			</div>
		</div>

	</div>
</main>

<?php
get_footer();
