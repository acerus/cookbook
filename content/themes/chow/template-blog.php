<?php
/**
 * Template Name: Blog Template
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage chow
 * @since chow 1.0
 */

get_header(); 

$slidername = get_post_meta($post->ID, 'pp_page_slider', true);

if($slidername) {
    $slider = new CP_Slider;
    $slider->getCPslider($slidername);
}
$revslidername = get_post_meta($post->ID, 'pp_rev_slider', true);

if($revslidername) {
        echo '<div class="container"><div class="sixteen columns">'; 
            if ( function_exists( 'putRevSlider' ) ) :
                putRevSlider($revslidername); 
            endif;
        echo "</div></div>";
    }
?>

<div class="container">


<?php
$layout = ot_get_option('pp_blog_layout','right-sidebar-grid'); 
if($layout == 'left-sidebar-grid' || $layout == 'right-sidebar-grid' || $layout == 'masonry') { ?>
    <!-- Masonry -->
        <?php
        
        if($layout == 'left-sidebar-grid' ) {
            echo '<div class="twelve columns left-sidebar-class">';
        } else if ($layout == 'right-sidebar-grid') {
            echo '<div class="twelve columns">';
        } else if ($layout == 'masonry') {
            echo '<div class="full-grid">';
        } else {
            echo '<div class="twelve columns">';
        } ?>

        <?php if( $layout == 'masonry'){ echo '<div class="sixteen columns">'; } ?>
            <!-- Headline -->
            
                <h3 class="headline"><?php echo ot_get_option('pp_blog_title','Latest Posts');?></h3>
                <span class="line rb margin-bottom-35"></span>
                <div class="clearfix"></div>
            
            <?php the_archive_description( '<div class="margin-bottom-20">', '</div>') ?>
        <?php if($layout == 'masonry'){
            echo '</div><div class="clearfix"></div>';
        } ?>
            <!-- Isotope -->

            <?php if ( have_posts() ) : ?>

            <?php /* Start the Loop */ ?>
            <div class="isotope">
                <?php 
                  $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1; // get current page number
                  $args = array(
                    'posts_per_page' => get_option('posts_per_page'), // the value from Settings > Reading by default
                    'paged'          => $current_page // current page
                  );
                  query_posts( $args );
                  $wp_query->is_archive = true;
                  $wp_query->is_home = false;
                  
                  while ( have_posts() ) : the_post(); ?>

                <?php
                            /* Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            
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

                <?php else : ?>

                <?php get_template_part( 'content', 'none' ); ?>

            <?php endif; ?>

            </div><!-- #Masonry -->
<?php } else { // list layout ?>
        <!-- Masonry -->
        <div class="twelve columns <?php if($layout == 'left-sidebar') { echo "left-sidebar-class"; } ?>">

            <!-- Headline -->
            <?php if( is_archive() || is_tag() || is_tax() || is_category()){ } else { ?> 
                 <h3 class="headline"><?php echo ot_get_option('pp_blog_title','Latest Recipes');?></h3>
                <span class="line rb margin-bottom-35"></span>
                <div class="clearfix"></div>
            <?php } ?>
            <?php the_archive_description( '<div class="margin-bottom-20">', '</div>') ?>
            <!-- Isotope -->

            <?php if ( have_posts() ) : ?>

            <?php /* Start the Loop */ ?>
            <!-- Isotope -->
            <div class="list-style">
                <?php
                 $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1; // get current page number
                $args = array(
                    'posts_per_page' => get_option('posts_per_page'), // the value from Settings > Reading by default
                    'paged'          => $current_page // current page
                );
                query_posts( $args );
                $wp_query->is_archive = true;
                $wp_query->is_home = false;
                  
                while ( have_posts() ) : the_post(); ?>

                <?php
                            /* Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'list/content', get_post_format() );
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

                <?php else : ?>

                <?php get_template_part( 'content', 'none' ); ?>

            <?php endif; ?>

            </div><!-- #Masonry -->
<?php }?>

<?php if($layout != 'masonry') { get_sidebar(); } ?>
</div>
<?php get_footer(); ?>