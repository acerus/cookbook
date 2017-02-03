<?php
/**
 * Template Name: Chow User Account Template
 *
 * A custom page template to show fav and added posts.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage chow
 * @since chow 1.0
 */

get_header(); ?>


<?php while ( have_posts() ) : the_post();

$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);
if(empty($layout)) { $layout = 'full-width'; }
?>


<!-- Titlebar
    ================================================== -->
    <section id="titlebar">
        <!-- Container -->
        <div class="container">

            <div class="eight columns">
                <h2><?php the_title(); ?></h2>
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
            <?php if ( is_user_logged_in() ): ?>
                <h6 class="myaccount_user">
                    <?php
                    printf(
                        __( 'Hello <strong>%1$s</strong> <a class="button small" style="float:right" href="%2$s">Sign out</a><h6>', 'chow' ) . ' ',
                        $current_user->display_name,
                        wp_logout_url() 
                    ); ?>
                </h6>
                <p class="chow_account_user">
                    <?php
                    echo __( "From your account dashboard you can edit recipes you've added and manage list of favourites posts .", 'chow' );  ?>
                </p>
            


            <?php $favourite_posts = get_user_meta($current_user->ID, 'foodiepress-fav-posts', true);
            if(!empty($favourite_posts)) { ?>
            <h3 class="headline" ><?php _e('Your favourite posts') ?></h3><span class="line" style="margin-bottom:35px;"></span><div class="clearfix"></div>
            <table class="basic-table fav-recipes responsive-table">

                <thead>
                    <tr>
                        <th class="recipe-thumb"><?php _e('Recipe','chow'); ?></th>
                        <th class="recipe-title"></th>
                        <th class="recipe-cat"><?php _e('Time','chow'); ?></th>
                        <th class="recipe-cat"><?php _e('Serving','chow'); ?></th>
                        <th class="recipe-actions">&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                $query = 
                array(
                    'post__in' => $favourite_posts, 
                    'posts_per_page'=> -1, 
                    'post_type' => array('post','recipe'),
                    'orderby' => 'post__in', 
                );
                // custom post type support can easily be added with a line of code like below.
                // $qry['post_type'] = array('post','page');
                query_posts($query);
                $nonce = wp_create_nonce("foodiepress_remove_fav_nonce");

                while ( have_posts() ) : the_post(); ?>
                       <tr class="recipe">
                        <td class="recipe-thumb">
                          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('widgets-thumb'); ?></a>
                        </td>
                         <td class="recipe-title" >
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </td>
                       
                        <td class="recipe-metas" >
                            <?php $time = get_the_term_list( $post->ID, 'timeneeded', ' ', ', ', '  ' );
                            if(!empty($time)) {
                                echo '<i class="fa fa-clock-o"></i> '.$time.'';
                            } ?>        
                        </td>
                        <td class="recipe-metas" >
                            <?php $serving = get_the_term_list( $post->ID, 'serving', ' ', ', ', '  ' );
                            if(!empty($serving)) {
                                echo '<i class="fa fa-cutlery"></i> '.$serving.'';
                            } ?>        
                        </td>
                        
                        <td class="recipe-actions">
                            <a href="<?php the_permalink(); ?>" class="button view small color"><?php _e('View','chow') ?></a> 
                            <?php
                                $link = admin_url('admin-ajax.php?action=remove_fav&post_id='.$post->ID.'&nonce='.$nonce);
                                echo '<a class="button small foodiepress-remove-fav" data-nonce="' . $nonce . '" data-post_id="' . $post->ID . '" href="' . $link . '">'.__('Remove','chow').'</a>';
                            ?>                 
                        </td>
                    </tr>
                <?php endwhile;  wp_reset_query(); ?>
               </tbody>
            </table>    
            <?php } ?>



            <h3 class="headline" ><?php _e('Your posts') ?></h3><span class="line" style="margin-bottom:35px;"></span><div class="clearfix"></div>
            <table class="basic-table fav-recipes responsive-table">
                <thead>
                    <tr>
                        <th class="recipe-thumb"><?php _e('Recipe','chow'); ?></th>
                        <th class="recipe-title"></th>
                        <th class="recipe-added"><?php _e('Added','chow'); ?></th>
                        <th class="recipe-status"><?php _e('Status','chow'); ?></th>
                        <th class="recipe-actions">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $query = 
                array(
                    'post_type' => array('post','recipe'),
                    'posts_per_page'=> -1, 
                    'orderby' => 'post__in', 
                    'author' => $current_user->ID,
                    'post_status' => array(
                        'publish',
                        'pending',
                        'draft',
                        'private',
                        'trash'
                    ),
                );
                // custom post type support can easily be added with a line of code like below.
                // $qry['post_type'] = array('post','page');
                query_posts($query);
                if ( have_posts() ) :
                while ( have_posts() ) : the_post(); ?>
                       <tr class="recipe">
                        <td class="recipe-thumb">
                            <?php if ( has_post_thumbnail() ) { ?>
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('widgets-thumb'); ?></a>
                            <?php } ?>
                        </td>
                         <td class="recipe-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </td>
                        <td class="recipe-date">
                        <?php $time_string = '<span><i class="fa fa-calendar"></i> <time class="entry-date published" datetime="%1$s">%2$s</time></span>';
                                if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
                                    $time_string = '<span><i class="fa fa-calendar"></i> <time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time></span>';
                                }

                                $time_string = sprintf( $time_string,
                                    esc_attr( get_the_date( 'c' ) ),
                                    esc_html( get_the_date() ),
                                    esc_attr( get_the_modified_date( 'c' ) ),
                                    esc_html( get_the_modified_date() )
                                );
                                echo $time_string; ?>
                        </td>
                        <td class="recipe-status"><?php echo get_post_status( get_the_ID() ) ?></td>
                        <td class="recipe-actions">
                            <a href="<?php the_permalink(); ?>" class="button view small color"><?php _e('View','chow') ?></a> 
                            <?php 

                            $edit_page_id = ot_get_option('pp_edit_page'); 
                            if(!empty($edit_page_id)){
                                $edit_post = add_query_arg('edit_post_id', get_the_ID(), get_permalink($edit_page_id)); ?>
                                <a href="<?php echo $edit_post ?>" class="button view small color"><?php _e('Edit','chow') ?></a> 
                            <?php } ?>
                            
                        </td>
                    </tr>
                <?php endwhile;  wp_reset_query(); endif;
                 ?>
               </tbody>
            </table>    
            <div class="margin-top-50"></div>
           </article>
           <?php 
            else :

                get_template_part( 'loginform');

            endif;
             ?>
        </div><!-- #post-## -->
        <?php if($layout !== "full-width") { get_sidebar(); } ?>
    </div><!-- .entry-content -->

    <?php endwhile; // end of the loop. ?>


    <?php get_footer(); ?>
