<?php
/**
 * The template for displaying custom post type archive pages in plugin.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Exopite
 */

get_header(); ?>
	<?php tha_content_before(); ?>
	<div class="container">
		<div class="row">
			<div id="primary" class="col-md-12">
				<main id="main" class="site-main" role="main">
				<?php tha_content_top(); ?>
				<?php
				if ( have_posts() ) : ?>

					<header class="page-header">
						<h1 class="page-title">TITLE</h1>
					</header><!-- .page-header -->
					<?php
					tha_content_while_before();
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						$templates->get_template_part( 'content', 'custom-post-type' );

					endwhile;
					tha_content_while_after();
					the_posts_navigation();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
				<?php tha_content_bottom(); ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .row -->
	</div><!-- .container -->
	<?php tha_content_after(); ?>
<?php
get_footer();
