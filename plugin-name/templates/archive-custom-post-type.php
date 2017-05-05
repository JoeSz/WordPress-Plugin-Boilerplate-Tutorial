<?php
/**
 * The template for displaying custom post type archive pages in plugin.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Exopite
 */

get_header();

/*
 * Theme Hook Allance Hook
 *
 * @link https://github.com/zamoose/themehookalliance
 */
tha_content_before(); ?>
	<div class="container">
		<div class="row">
			<div id="primary" class="col-md-12">
				<main id="main" class="site-main" role="main">
				<?php

                // Theme Hook Allance Hook
                tha_content_top();

				if ( have_posts() ) :

                    ?>
					<header class="page-header">
						<h1 class="page-title">TITLE</h1>
					</header><!-- .page-header -->
					<?php

                    // Theme Hook Allance Hook
					tha_content_while_before();

					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         *
                         * $templates->get_template_part required Gamajo_Template_Loader, see in tutorial file:
                         * add_custom_get-template-part_to_load_template_from_plugin.php
						 */
						$templates->get_template_part( 'content', 'custom-post-type' );

					endwhile;

                    // Theme Hook Allance Hook
					tha_content_while_after();

					the_posts_navigation();

				else :

                    /*
                     * Use wordPress original get_template_part. This will load template-parts/content-none.php
                     * form [child]theme directory not from the plugin.
                     */
					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
				<?php

                // Theme Hook Allance Hook
                tha_content_bottom();

                ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .row -->
	</div><!-- .container -->
	<?php

// Theme Hook Allance Hook
tha_content_after();

get_footer();
