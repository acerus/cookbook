<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Chow
 */


get_header(); ?>




<!-- Titlebar
    ================================================== -->
    <section id="titlebar">
        <!-- Container -->
        <div class="container">

            <div class="eight columns">

                <h1><?php _e( 'Oops! That page can&rsquo;t be found.', 'chow' ); ?></h1>

            </div>

            <div class="eight columns">
                    <?php if(ot_get_option('pp_breadcrumbs','on') == 'on') echo dimox_breadcrumbs(); ?>
            </div>

        </div>
        <!-- Container / End -->
    </section>

    <!-- Container -->
    <div class="container">
        <?php

       
                $width = 'sixteen';
       
        $post_classes = $width.' columns'; ?>
        <div id="post-404-error" <?php post_class($post_classes); ?>>
            <article>
            <div class="columns sixteen alpha omega margin-bottom-40">
               <p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'chow' ); ?></p>
               <?php get_search_form(); ?>

               </div>

				<div class="columns one-third alpha">
					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>
				</div>
				<div class="columns one-third">
					<?php if ( chow_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
					<div class="widget widget_categories">
						<h2 class="widget-title"><?php _e( 'Most Used Categories', 'chow' ); ?></h2>
						<ul>
						<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
						?>
						</ul>
					</div><!-- .widget -->
					<?php endif; ?>
				</div>
				<div class="columns one-third omega">
					<?php
						/* translators: %1$s: smiley */
						$archive_content = '<p>' . sprintf( __( 'Try looking in the monthly archives. %1$s', 'chow' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
					?>

					<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
					</div>

            </article>
            <?php
            wp_link_pages( array(
                'before' => '<div class="page-links">' . __( 'Pages:', 'chow' ),
                'after'  => '</div>',
                ) );
                ?>
                <div class="clearfix"></div>
                <footer class="entry-footer">
                    <?php edit_post_link( __( 'Edit', 'chow' ), '<span class="edit-link">', '</span>' ); ?>
                </footer><!-- .entry-footer -->

           
        </div><!-- #post-## -->
        
    </div><!-- .entry-content -->

    


    <?php get_footer(); ?>