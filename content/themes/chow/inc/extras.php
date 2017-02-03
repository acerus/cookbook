<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Chow
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function chow_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'chow_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function chow_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
 /*   if(is_page_template( 'template-boxed.php' )) {
            $classes[] = 'boxed';
    }
*/
	return $classes;
}
add_filter( 'body_class', 'chow_body_classes' );

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function chow_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'chow' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'chow_wp_title', 10, 2 );
endif;

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function chow_render_title() {
		echo '<title>' . wp_title( '|', false, 'right' ) . "</title>\n";
	}
	add_action( 'wp_head', 'chow_render_title' );
endif;

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function chow_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'chow_setup_author' );

//customize the PageNavi HTML before it is output
add_filter( 'wp_pagenavi', 'chow_pagination', 10, 2 );
function chow_pagination($html) {
    $out = '';
    //wrap a's and span's in li's
    $out = str_replace("<a","<li><a",$html);
    $out = str_replace("</a>","</a></li>",$out);
    $out = str_replace("<span","<li><span",$out);
    $out = str_replace("</span>","</span></li>",$out);
    $out = str_replace("<div class='wp-pagenavi'>","",$out);
    $out = str_replace("</div>","",$out);
    return '<div class="paging"><ul>'.$out.'</ul></div>';
}

if ( ! function_exists( 'chow_get_rating_class' ) ) :
function chow_get_rating_class($average) {
	switch ($average) {
		case $average >= 1 and $average < 1.5:
			$class="one-stars";
			break;
		case $average >= 1.5 and $average < 2:
			$class="one-and-half-stars";
			break;
		case $average >= 2 and $average < 2.5:
			$class="two-stars";
			break;
		case $average >= 2.5 and $average < 3:
			$class="two-and-half-stars";
			break;
		case $average >= 3 and $average < 3.5:
			$class="three-stars";
			break;
		case $average >= 3.5 and $average < 4:
			$class="three-and-half-stars";
			break;
		case $average >= 4 and $average < 4.5:
			$class="four-stars";
			break;
		case $average >= 4.5 and $average < 5:
			$class="four-and-half-stars";
			break;
		case $average >= 5:
			$class="five-stars";
			break;
		default:
			$class="no-rating";
			break;
	}
	return $class;
}
endif;




function chow_advanced_search_query($query) {
    if($query->is_search()) {

// tag included
        if (isset($_GET['include_ing']) && is_array($_GET['include_ing'])) {
            if($_GET['relation']=='all') {
                $query->set('tax_query', array(                     //(array) - use taxonomy parameters (available with Version 3.1).
                    'relation' => 'AND',
                        array(
                            'taxonomy' => 'ingredients',
                            'field' => 'slug',
                            'terms' =>  $_GET['include_ing'],
                            'include_children' => false,
                            'operator' => 'AND'
                        )
                    )
                );
            } else {
                $query->set('tax_query', array(                     //(array) - use taxonomy parameters (available with Version 3.1).
                    'relation' => 'AND',
                        array(
                            'taxonomy' => 'ingredients',
                            'field' => 'slug',
                            'terms' =>  $_GET['include_ing'],
                            'include_children' => false,
                            'operator' => 'IN'
                        )
                    )
                );
            }
        }

// relation


// categories
        if (isset($_GET['cat']) && is_array($_GET['cat'])) {
           
            $query->set('cat', $_GET['cat'] );
        }

        if (isset($_GET['cats']) && is_array($_GET['cats'])) {
          
            $query->set('category__in', $_GET['cats'] );
        }
//level
        if (isset($_GET['level']) && is_array($_GET['level'])) {
            $query->set('tax_query',array(
                array(
                    'taxonomy' => 'level',
                    'field'    => 'slug',
                    'terms'    => $_GET['level']
                    )
                )
            );
        }
//serving
        if (isset($_GET['serving']) && is_array($_GET['serving'])) {
            $query->set('tax_query',array(
                array(
                    'taxonomy' => 'serving',
                    'field'    => 'slug',
                    'terms'    => $_GET['serving']
                    )
                )
            );
        }
//time needed
        if (isset($_GET['timeneeded']) && is_array($_GET['timeneeded'])) {
            $query->set('tax_query',array(
                array(
                    'taxonomy' => 'timeneeded',
                    'field'    => 'slug',
                    'terms'    => $_GET['timeneeded']
                    )
                )
            );
        }
//allergens
        if (isset($_GET['allergens']) && is_array($_GET['allergens'])) {
            $query->set('tax_query',array(
                array(
                    'taxonomy' => 'allergen',
                    'field'    => 'slug',
                    'terms'    => $_GET['allergens']
                    )
                )
            );
        }
//exclude
        if (isset($_GET['exclude_ing']) && is_array($_GET['exclude_ing'])) {

            $query->set('tax_query',array(
                array(
                 'taxonomy' => 'ingredients',
                 'field' => 'slug',
                 'terms' => $_GET['exclude_ing'],
                 'operator' => 'NOT IN'
                 )
                )
            );

        }

    /*     $query->set('tax_query',array(
            array(
               'taxonomy' => 'category',
               'field'    => 'slug',
               'terms'    => 'ukryte',
               'operator' => 'NOT IN',
            )
            ));
    */

        return $query;
}

}
add_action('pre_get_posts', 'chow_advanced_search_query', 1000);


add_filter( 'request', 'chow_request_filter' );
function chow_request_filter( $query_vars ) {
    if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
        $query_vars['s'] = " ";
    }
    return $query_vars;
}


add_filter('wp_list_categories', 'add_span_cat_count');

function add_span_cat_count($links) {
    $links = str_replace('</a> (', ' <span>(', $links);
    $links = str_replace(')', ')</span></a> ', $links);
    return $links;
}


       
function check_post_rating(){
    global $post;
    $overall_ratings = 0;
    $count_ratings = 0;

    $args = array(
        'post_id' => $post->ID,
        'status' => 'approve',
        'meta_key' => 'foodiepress-rating',
        'meta_compare' => '>',
        'meta_value' => '0'
    );

    $ratings = get_comments( $args );
    $count_ratings = count($ratings);

    return $count_ratings;
}


function chow_login_redirect( $url, $request, $user ){
    if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if( $user->has_cap( 'administrator' ) ) {
            $url = admin_url();
        } else {
            $myacount_id = ot_get_option('pp_account_page'); 
            if(!empty($myacount_id)) {
                $url = get_permalink($myacount_id);
            } else {
                $url = home_url();
            }
        }
    }
    return $url;
}

add_filter('login_redirect', 'chow_login_redirect', 10, 3 );

function check_if_recipe(){
    global $post;
    if( has_shortcode( $post->post_content, 'purerecipe') || has_shortcode( $post->post_content, 'foodiepress') ) {
        $recipe = true;
    } else {
        $recipe = false;
    }

    $ingredients = get_post_meta($post->ID, 'cookingpressingridients', true);
    if(!empty($ingredients)) {
        $recipe = true;
    }
    return $recipe;
}

/* add a custom tab to show user pages */
add_filter('um_profile_tabs', 'chow_recipes_pages_tab', 1000 );
function chow_recipes_pages_tab( $tabs ) {
    $tabs['recipes'] = array(
        'name' => 'Favourite Recipes',
        'icon' => 'fa fa-heart-o',
        'custom' => true
    );  
    return $tabs;
}

/* Tell the tab what to display */
add_action('um_profile_content_recipes_default', 'um_profile_chow_fav_recipes');
function um_profile_chow_fav_recipes( $args ) {
    global $ultimatemember;
    $favourite_posts = get_user_meta(um_profile_id(), 'foodiepress-fav-posts', true);
    if(!empty($favourite_posts)) { 
        $loop = $ultimatemember->query->make(array(
                'post__in' => $favourite_posts, 
                'posts_per_page'=> -1, 
                'orderby' => 'post__in', 
                'ignore_sticky_posts' => 1,
            ));
            ?>
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
            $nonce = wp_create_nonce("foodiepress_remove_fav_nonce");

        while ($loop->have_posts()) { 

            $loop->the_post(); $post_id = get_the_ID(); ?>
            <tr class="recipe">
                <td class="recipe-thumb">
                  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('widgets-thumb'); ?></a>
                </td>
                 <td class="recipe-title" >
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </td>
               
                <td class="recipe-metas" >
                    <?php $time = get_the_term_list( $post_id, 'timeneeded', ' ', ', ', '  ' );
                    if(!empty($time)) {
                        echo '<i class="fa fa-clock-o"></i> '.$time.'';
                    } ?>        
                </td>
                <td class="recipe-metas" >
                    <?php $serving = get_the_term_list( $post_id, 'serving', ' ', ', ', '  ' );
                    if(!empty($serving)) {
                        echo '<i class="fa fa-cutlery"></i> '.$serving.'';
                    } ?>        
                </td>
                
                <td class="recipe-actions">
                    <a href="<?php the_permalink(); ?>" class="button view small color"><?php _e('View','chow') ?></a> 
                    <?php
                    $user_id = get_current_user_id();
                    if($user_id === um_profile_id()) {
                        $link = admin_url('admin-ajax.php?action=remove_fav&post_id='.$post_id.'&nonce='.$nonce);
                        echo '<a class="button small foodiepress-remove-fav" data-nonce="' . $nonce . '" data-post_id="' . $post_id . '" href="' . $link . '">'.__('Remove','chow').'</a>';
                    }
                    ?>                 
                </td>
            </tr>
        <?php } ?>
                </tbody>
            </table>
        <?php
    }
}

/* add a custom tab to show user pages */
add_filter('um_profile_tabs', 'chow_myrecipes_pages_tab', 1000 );
function chow_myrecipes_pages_tab( $tabs ) {
    $tabs['myrecipes'] = array(
        'name' => 'My Recipes',
        'icon' => 'fa fa-cutlery',
        'custom' => true
    );  
    return $tabs;
}

/* Tell the tab what to display */
add_action('um_profile_content_myrecipes_default', 'um_profile_chow_my_recipes');
function um_profile_chow_my_recipes( $args ) {
    global $ultimatemember;
  
        $loop = $ultimatemember->query->make(array(
                    'posts_per_page'=> -1, 
                    'author' => um_user('ID'),
                    'post_status' => array(
                        'publish',
                        'pending',
                        'draft',
                        'private',
                        'trash'
                    ),
                ));
            ?>
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
            $nonce = wp_create_nonce("foodiepress_remove_fav_nonce");

        while ($loop->have_posts()) { 

            $loop->the_post(); $post_id = get_the_ID(); ?>
             <tr class="recipe">
                        <td class="recipe-thumb">
                          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('widgets-thumb'); ?></a>
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
                                $user_id = get_current_user_id();
                                if($user_id === um_profile_id()) {
                                $edit_post = add_query_arg('edit_post_id', get_the_ID(), get_permalink($edit_page_id)); ?>
                                <a href="<?php echo $edit_post ?>" class="button view small color"><?php _e('Edit','chow') ?></a> 
                            <?php }
                            } ?>
                            
                        </td>
                    </tr>
        <?php } ?>
                </tbody>
            </table>
        <?php
    
}


/*
// Add term page
function chow_category_add_new_meta_field() {
    // this will add the custom meta field to the add new term page
    ?>
    <div class="form-field">
        <label for="term_meta[upload_header]"><?php esc_html_e( 'Background image for category header', 'workscout' ); ?></label>
        <input type="text" name="term_meta[upload_header]" id="term_meta[upload_header]" value="">
        <p class="description"><?php esc_html_e( 'Similar to the single jobs you can add image to the category header. It should be 1920px wide','workscout' ); ?></p>
    </div>

    
        
<?php
}
add_action( 'category_add_form_fields', 'chow_category_add_new_meta_field', 10, 2 );



// Edit term page
function chow_category_edit_meta_field($term) {
 
    // put the term ID into a variable
    $t_id = $term->term_id;
 
    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option( "taxonomy_$t_id" ); 
     ?>
   
    <tr class="form-field">
        <th scope="row" valign="top"><label for="term_meta[upload_header]"><?php esc_html_e( 'Background image for category header', 'workscout' ); ?></label></th>
        <td>
            <input type="text" name="term_meta[upload_header]" id="term_meta[upload_header]" value="<?php echo esc_attr( $term_meta['upload_header'] ) ? esc_attr( $term_meta['upload_header'] ) : ''; ?>">
            <p class="description"><?php esc_html_e( 'Similar to the single jobs you can add image to the category header. Put here direct link to the image. It should be 1920px wide','workscout' ); ?></p>
        </td>
    </tr>
<?php
}
add_action( 'category_edit_form_fields', 'chow_category_edit_meta_field', 10, 2 );


// Save extra taxonomy fields callback function.
function chow_save_taxonomy_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        // Save the option array.
        update_option( "taxonomy_$t_id", $term_meta );
    }
}  
add_action( 'edited_category', 'chow_save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_category', 'chow_save_taxonomy_custom_meta', 10, 2 );
*/


// display featured post thumbnails in WordPress feeds
function chow_post_thumbnails_in_feeds( $content ) {
    global $post;
    if( has_post_thumbnail( $post->ID ) ) {
        $thumb = '<p>' . get_the_post_thumbnail( $post->ID ) . '</p>';
    } else {
        $thumb = '';
    }
    $options = get_option( 'chow_option',array());
    if(!empty($options['place'])) {
        switch ($options['place']) {
            case 'before':
                return $thumb . do_shortcode('[foodiepress]') . get_the_content() ;
                break;
            case 'after':
                return $thumb . get_the_content() . do_shortcode('[foodiepress]') ;
                break;
            
            default:
                return $thumb . do_shortcode('[foodiepress]') . get_the_content() ;
                break;
        }
    } else {
        return $thumb.$content;
    }
        
    return $content;
}
add_filter( 'the_excerpt_rss', 'chow_post_thumbnails_in_feeds' );
add_filter( 'the_content_feed', 'chow_post_thumbnails_in_feeds' );