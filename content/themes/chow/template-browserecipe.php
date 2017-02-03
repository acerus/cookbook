<?php
/**
 * Template Name: Browse Recipe Template
 *
 * A custom page template without sidebar to browse recipes.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage trizzy
 * @since trizzy 1.0
 */

get_header(); ?>
<!-- Content
================================================== -->
<!-- Titlebar
================================================== -->
<section id="titlebar" class="browse-all">
    <!-- Container -->
    <div class="container">
        <div class="eight columns">
            <h2><?php _e('Browse Recipes','chow') ?></h2>
        </div>
    </div>
    <!-- Container / End -->
</section>

<?php get_template_part( 'searchformadv' ); ?>
<div class="margin-top-35"></div>

<div class="container">
    <div class="full-grid">
        <div class="sixteen columns">
           <h3 class="headline"><?php _e('All Recipes','chow') ?></h3>
           <span class="line  margin-bottom-35"></span>
           <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

       <div class="isotope">
            <?php
                    if(is_front_page()){
                        $paged = ( get_query_var('page') ) ? get_query_var('page') : 1; 
                    } else {
                        global $paged;
                    }
                    global $wp_query;
                    $temp = $wp_query;
                    $wp_query = null;
                    
                    $options = get_option( 'chow_option',array());
                    if(is_array($options) && !empty($options['post_type'])) {
                        $post_type = 'recipe';
                    } else {
                        $post_type = 'post';
                    }
                    $args = array(
                        'posts_per_page' => 8,
                        'post_type' => $post_type,
                        'paged' => $paged,
                        'meta_key' => 'cookingpressinstructions',
                        'meta_value' => ' ',
                        'meta_compare' => '!=',
                       
                    );
                    $wp_query = new WP_Query($args);
              
                   while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

                   <?php
                    /* Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    $format = get_post_format();
                    if( false === $format  )  $format = 'standard';
                    get_template_part( 'grid/content', get_post_format() );
            ?>

            <?php endwhile; ?>
        </div>
        <div class="clearfix"></div>
        
        <div class="pagination-container masonry">
        <?php if(function_exists('wp_pagenavi')) { ?>
                <nav class="pagination wp-pagenavi">
                    <?php wp_pagenavi(); ?>
                </nav>
                <?php
            } else {
                if ( get_next_posts_link() ||  get_previous_posts_link() ) : ?>
                <nav class="pagination">
                    <ul>
                        <?php if ( get_previous_posts_link() ) : ?>
                        <li id="next"><?php previous_posts_link(''); ?></li>
                        <?php endif;
                            if ( get_next_posts_link() ) : ?>
                        <li id="prev"><?php next_posts_link(''); ?></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif;
            } ?>
        </div>

    <?php wp_reset_postdata(); ?>

            <?php $wp_query = null;
            $wp_query = $temp;  ?>
    </div><!-- #Masonry -->
</div><!-- #Masonry -->

<?php get_footer(); ?>
