<?php
/**
 * Template Name: Page Template with Chow Slider
 *
 * A custom page template with Slider
 *
 *
 * @package WPVoyager
 */





get_header(); 
$slidername = get_post_meta($post->ID, 'pp_page_slider', true);

if($slidername) {
    $slider = new CP_Slider;
    $slider->getCPslider($slidername);
}

 while ( have_posts() ) : the_post();

$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);
if(empty($layout)) { $layout = 'full-width'; }
?>


<!-- Titlebar
    ================================================== -->
    <section id="titlebar">
        <!-- Container -->
        <div class="container">

            <div class="eight columns">

                <h1><?php the_title(); ?></h1>

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

        switch ($layout) {
            case 'full-width':
                $width = 'sixteen';
                break;
            case 'left-sidebar':
                $width = 'twelve left-sidebar-class';
                break;
            case 'right-sidebar':
                $width = 'twelve';
                break;
            default:
                $width = 'sixteen';
                break;
        }
        $post_classes = $width.' columns'; ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
            <article>
                <?php the_content(); ?>
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

            <?php
                if(ot_get_option('pp_pagecomments','off') == 'on') {
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                }
            ?>
        </div><!-- #post-## -->
        <?php if($layout !== "full-width") { get_sidebar(); } ?>
    </div><!-- .entry-content -->

    <?php endwhile; // end of the loop. ?>


    <?php get_footer(); ?>
