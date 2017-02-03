<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Chow
 */

if (!function_exists('chow_number_to_width')) :
function chow_number_to_width($width) {
    switch ($width) {
        case '1':
        return "one";
        break;
        case '2':
        return "two";
        break;
        case '3':
        return "three";
        break;
        case '4':
        return "four";
        break;
        case '5':
        return "five";
        break;
        case '6':
        return "six";
        break;
        case '7':
        return "seven";
        break;
        case '8':
        return "eight";
        break;
        case '9':
        return "nine";
        break;
        case '10':
        return "ten";
        break;
        case '11':
        return "eleven";
        break;
        case '12':
        return "twelve";
        break;
        case '13':
        return "thirteen";
        break;
        case '14':
        return "fourteen";
        break;
        case '15':
        return "fifteen";
        break;
        case '16':
        return "sixteen";
        break;
        case '1/3':
        return "one-third";
        break;        
        case '2/3':
        return "two-thirds";
        break;
        default:
        return "sixteen";
        break;
    }
}
endif;

if ( ! function_exists( 'chow_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function chow_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'chow' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'chow' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'chow' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'chow_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function chow_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h3 class="screen-reader-text"><?php _e( 'Post navigation', 'chow' ); ?></h3>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'chow' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link', 'chow' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'chow_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function chow_posted_on() {
	if(is_single()) {
	    $metas = ot_get_option('pp_meta_single',array());
	    
	    if (in_array("author", $metas)) {
	        echo '<span itemscope itemtype="http://data-vocabulary.org/Person">';
	        echo '<i class="fa fa-user"></i>'. __('By','chow'). ' <a class="author-link" itemprop="url" rel="author" href="'.get_author_posts_url(get_the_author_meta('ID' )).'">'; the_author_meta('display_name'); echo'</a>';
	        echo '</span>';
	    }

	    if (in_array("cat", $metas)) {
	      if(has_category()) { echo '<span><i class="fa fa-file"></i>'; the_category(', '); echo '</span>'; }
	    }

	    if (in_array("tags", $metas)) {
	      if(has_tag()) { echo '<span><i class="fa fa-tag"></i>'; the_tags('',', '); echo '</span>'; }
	    }

	    if (in_array("com", $metas)) {
	      echo '<span><i class="fa fa-comment"></i>'; 
	      comments_popup_link( esc_html__('With 0 comments','chow'), esc_html__('With 1 comment','chow'), esc_html__('With % comments','chow'), 'comments-link', esc_html__('Comments are off','chow'));
	       echo '</span>';
	    }

		if (in_array("date", $metas)) {
	    	$time_string = '<span><i class="fa fa-calendar"></i> <time class="entry-date published" datetime="%1$s">%2$s</time></span>';
	        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
	            $time_string = '<span><i class="fa fa-calendar"></i> <time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time></span>';
	        }

	        $time_string = sprintf( $time_string,
	            esc_attr( get_the_date( 'c' ) ),
	            esc_html( get_the_date() ),
	            esc_attr( get_the_modified_date( 'c' ) ),
	            esc_html( get_the_modified_date() )
	        );
	        echo $time_string;
	    }
  } 
}
endif;

if ( ! function_exists( 'chow_meta_author' ) ) :
    function chow_meta_author() {
        echo  '<div class="recipe-meta author vcard"><i class="fa fa-user"></i> '.__('by','chow').' <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></div>';
    }
endif;

if ( ! function_exists( 'chow_meta_recipe_time' ) ) :
    function chow_meta_recipe_time() {
        global $post;
        $time = get_the_term_list( $post->ID, 'timeneeded', ' ', ', ', '  ' );
        if(!empty($time)) {
            echo '<div class="recipe-meta"><i class="fa fa-clock-o"></i> '.$time.'</div>';
        }
    }
endif;

if ( ! function_exists( 'chow_meta_servings' ) ) :
    function chow_meta_servings() {
        global $post;
        $serving = get_the_term_list( $post->ID, 'serving', ' ', ', ', '  ' );
        if(!empty($serving)) {
            echo '<div class="recipe-meta"><i class="fa fa-cutlery"></i> '.$serving.'</div>';
        }
    }
endif;

if ( ! function_exists( 'chow_meta_level' ) ) :
    function chow_meta_level() {
        global $post;
        $level = get_the_term_list( $post->ID, 'level', ' ', ', ', '  ' );
        if(!empty($level)) {
            echo '<div class="recipe-meta"><i class="fa fa-tasks"></i> '.$level.'</div>';
        }
    }
endif;

if ( ! function_exists( 'chow_meta_allergens' ) ) :
    function chow_meta_allergens() {
        global $post;
        $allergens = get_the_term_list( $post->ID, 'allergen', ' ', ', ', '  ' );
        if(!empty($allergens)) {
            echo '<div class="recipe-meta"><i class="fa fa-warning"></i> '.$allergens.'</div>';
        }
    }
endif;

if ( ! function_exists( 'chow_meta_date' ) ) :
    function chow_meta_date() {
        global $post;
        $time_string = '<div class="recipe-meta"><i class="fa fa-calendar"></i> <time class="entry-date published" datetime="%1$s">%2$s</time></div>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<div class="recipe-meta"><i class="fa fa-calendar"></i> <time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time></div>';
        }

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );
        echo $time_string;
    }
endif;

if ( ! function_exists( 'chow_meta_comments' ) ) :
    function chow_meta_comments() {
       echo '<div class="recipe-meta"><i class="fa fa-comments-o"></i> ';
        comments_popup_link( __( 'Leave a comment', 'chow' ), __( '1 Comment', 'chow' ), __( '% Comments', 'chow' ) );
        echo '</div>';
    }
endif;

if ( ! function_exists( 'chow_meta_categories' ) ) :
    function chow_meta_categories() {
       echo '<div class="recipe-meta"><i class="fa fa-file-text"></i> ';
        the_category(', ');
        echo '</div>';
    }
endif;

if ( ! function_exists( 'chow_meta_tags' ) ) :
    function chow_meta_tags() {
       echo '<div class="recipe-meta"><i class="fa fa-tag"></i> ';
        the_tags( '', ',  ', ' ');
        echo '</div>';
    }
endif;

//todo     cat tags
function chow_postmeta_tags(){
    $elements = ot_get_option('pp_list_meta',array());
    foreach ($elements as $key => $value) {
        add_action('list-post-meta','chow_meta_'.$value);
    }

    $elements = ot_get_option('pp_grid_meta',array());
    foreach ($elements as $key => $value) {
        add_action('grid-post-meta','chow_meta_'.$value);
    }

    $elements = ot_get_option('pp_meta_no_recipe',array());
    foreach ($elements as $key => $value) {
        add_action('no-recipe-post-meta','chow_meta_'.$value);
    }

    
}

if ( ! function_exists( 'chow_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function chow_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() || 'recipe' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'chow' ) );
		if ( $categories_list && chow_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'chow' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'chow' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'chow' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'chow' ), __( '1 Comment', 'chow' ), __( '% Comments', 'chow' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'chow' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'chow' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'chow' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'chow' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'chow' ), get_the_date( _x( 'Y', 'yearly archives date format', 'chow' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'chow' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'chow' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'chow' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'chow' ) ) );
	} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
		$title = _x( 'Asides', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
		$title = _x( 'Galleries', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
		$title = _x( 'Images', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
		$title = _x( 'Videos', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
		$title = _x( 'Quotes', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
		$title = _x( 'Links', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
		$title = _x( 'Statuses', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
		$title = _x( 'Audio', 'post format archive title', 'chow' );
	} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
		$title = _x( 'Chats', 'post format archive title', 'chow' );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'chow' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'chow' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'chow' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function chow_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'chow_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'chow_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so chow_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so chow_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in chow_categorized_blog.
 */
function chow_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'chow_categories' );
}
add_action( 'edit_category', 'chow_category_transient_flusher' );
add_action( 'save_post',     'chow_category_transient_flusher' );


if ( ! function_exists( 'chow_get_posts_page' ) ) :

function chow_get_posts_page($info) {
  if( get_option('show_on_front') == 'page') {
    $posts_page_id = get_option( 'page_for_posts');
    $posts_page = get_page( $posts_page_id);
    $posts_page_title = $posts_page->post_title;
    $posts_page_url = get_page_uri($posts_page_id  );
  }
  else $posts_page_title = $posts_page_url = '';

  if ($info == 'url') {
    return $posts_page_url;
  } elseif ($info == 'title') {
    return $posts_page_title;
  } else {
    return false;
  }
}
endif;

/**
 * The Breadcrumbs function
*/
if ( ! function_exists( 'dimox_breadcrumbs' ) ) :
  function dimox_breadcrumbs() {
    $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = ''; // delimiter between crumbs
    $introtext =  __('You are here','chow');
    $home = __('Home','chow'); // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<li class="current_element">'; // tag before the current crumb
    $after = '</li>'; // tag after the current crumb

    global $post;
    $homeLink = home_url();
    $frontpageuri = chow_get_posts_page('url');
    $frontpagetitle = ot_get_option('pp_blog_page');
    $output = '';
    if (is_home() || is_front_page()) {
      if ($showOnHome == 1)
        echo '<nav id="breadcrumbs"><ul>';
    	echo '<li>'.$introtext.'</li>';
        echo '<li><a href="' . esc_url($homeLink) . '">' . $home . '</a></li>';
        echo '<li>' . $frontpagetitle . '</li>';
        echo '</ul></nav>';
    } else {

      $output .= '<nav id="breadcrumbs"><ul><li>'.$introtext.'</li><li><a href="' . $homeLink . '">' . $home . '</a>' . $delimiter . '</li> ';
      if(function_exists('is_shop')) {
        if(is_shop()) {
          $shop_page_id = wc_get_page_id( 'shop' );
          $output .= '<li><a href="'.get_permalink( $shop_page_id) .'">'.__('Shop','chow').'</a></li>';
        }
      }
      if ( is_category() ) {
        $thisCat = get_category(get_query_var('cat'), false);
        if ($thisCat->parent != 0) $output .= '<li>'.get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ').'<li>';
        $output .= $before . __('Archive by category','chow').' "' . single_cat_title('', false) . '"' . $after;

      } elseif ( is_search() ) {
        $output .= $before . __('Search results for','chow').' "' . get_search_query() . '"' . $after;

      } elseif ( is_day() ) {
        $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
        $output .= '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . '</li> ';
        $output .= $before . get_the_time('d') . $after;

      } elseif ( is_month() ) {
        $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' </li>';
        $output .= $before . get_the_time('F') . $after;

      } elseif ( is_year() ) {
        $output .= $before . get_the_time('Y') . $after;

      } elseif ( is_single() && !is_attachment() ) {
        if ( get_post_type() != 'post' ) {
          $post_type = get_post_type_object(get_post_type());
          $slug = $post_type->rewrite;
          $output .= '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li>';
          if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
        } else {
          $cat = get_the_category(); $cat = $cat[0];
          $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
          if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
          $output .= '<li>'.$cats.'</li>';
          if ($showCurrent == 1) $output .= $before . get_the_title() . $after;
        }
      
      } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
        $post_type = get_post_type_object(get_post_type());
        $output .= $before . $post_type->labels->singular_name . $after;

      } elseif ( is_attachment() ) {
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID); $cat = $cat[0];
        //$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        $output .= '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
        if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

      } elseif ( is_page() && !$post->post_parent ) {
        if ($showCurrent == 1) $output .= $before . get_the_title() . $after;

      } elseif ( is_page() && $post->post_parent ) {
        $parent_id  = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
          $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
          $output .= $breadcrumbs[$i];
          if ($i != count($breadcrumbs)-1) $output .= ' ' . $delimiter . ' ';
        }
        if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

      } elseif ( is_tag() ) {
        $output .= $before . __('Posts tagged','chow').' "' . single_tag_title('', false) . '"' . $after;

      } elseif ( is_author() ) {
       global $author;
       $userdata = get_userdata($author);
       $output .= $before . __('Articles posted by ','chow') . $userdata->display_name . $after;

     } elseif ( is_404() ) {
      $output .= $before .  __('Error 404','chow') . $after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ' (';
        $output .= '<li>'.__('Page','chow') . ' ' . get_query_var('paged').'</li>';
        if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ')';
  }

  $output .= '</ul></nav>';
  return $output;
  }
  } // end dimox_breadcrumbs()
endif;


/**
 * Limits number of words from string
 *
 * @since astrum 1.0
 */
if ( ! function_exists( 'string_limit_words' ) ) :
function string_limit_words($string, $word_limit) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
        //add a ... at last article when more than limit word count
        return implode(' ', $words) ;
    } else {
        //otherwise
        return implode(' ', $words);
    }
}
endif;



if ( ! function_exists( 'chow_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since astrum 1.0
 */
function chow_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' :
  ?>
  <li class="post pingback">
    <p><?php _e( 'Pingback:', 'chow' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'chow' ), ' ' ); ?></p>
  <?php
      break;
    default :
    $allowed_tags = wp_kses_allowed_html( 'post' );
  ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
       <div id="comment-<?php comment_ID(); ?>" class="comment">
       <?php echo get_avatar( $comment, 70 ); ?>
        <div class="comment-content"><div class="arrow-comment"></div>
            <div class="comment-by"><?php printf( '<strong>%s</strong>', get_comment_author_link() ); ?>  <span class="date"> <?php printf( __( '%1$s at %2$s', 'chow' ), get_comment_date(), get_comment_time() ); ?></span>
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => wp_kses(__('<i class="fa fa-reply"></i> Reply','chow'), $allowed_tags ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
             
            </div>
            <?php comment_text(); ?>

        </div>
        </div>
  <?php
      break;
  endswitch;
}
endif; // ends check for chow_comment()



/**
 * Limits number of words from string
 *
 * @since astrum 1.0
 */
if ( ! function_exists( 'chow_related_posts' ) ) :
function chow_related_posts($post) {
    $orig_post = $post;
    global $post;
    $categories = get_the_category($post->ID);
    echo '<div class="related-posts">';
    if ($categories) {
        $category_ids = array();
        foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
        $args=array(
            'category__in' => $category_ids,
            'post__not_in' => array($post->ID),
            'meta_key'    => '_thumbnail_id',
            'posts_per_page'=> 3, // Number of related posts that will be shown.
            'ignore_sticky_posts'=>1
        );
        $my_query = new wp_query( $args );
        if( $my_query->have_posts() ) { ?>
        <h3 class="headline"><?php _e('You may also like','chow'); ?></h3>
            <span class="line margin-bottom-35"></span>
            <div class="clearfix"></div>
        <?php
            while( $my_query->have_posts() ) {
                $my_query->the_post();
$is_recipe = false;
$ingredients = get_post_meta(get_the_ID(), 'cookingpressingridients', true);
if(!empty($ingredients)) {
	$is_recipe = true;
}
                ?>
            <div class="four recipe-box columns">
                <!-- Thumbnail -->
                <div class="thumbnail-holder">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(); ?>
                        <div class="hover-cover"></div>
                        <div class="hover-icon"><?php _e('View Recipe','chow') ?></div>
                    </a>
                </div>

                <!-- Content -->
                <div class="recipe-box-content">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                   <?php do_action('foodiepress-rating'); ?>
	
					<?php if($is_recipe) { do_action('grid-post-meta'); } else { do_action('no-recipe-post-meta'); } ?>

                    <div class="clearfix"></div>
                </div>
            </div>
                <?php
            }

        }
    }
    $post = $orig_post;
    wp_reset_query();
    echo '</div>';
}
endif;


function chow_tags_multiselect($id,$def,$title) {

    $tags = get_terms( 'ingredients',  array( 'orderby' => 'count', 'order' => 'DESC' ) );
    $html = '<select data-placeholder="'.$title.'" id="'.$id.'" name="'.$id.'[]" class="chosen-select" multiple="true" >';
    foreach ($tags as $tag) {
        $tag_link = get_tag_link($tag->term_id);
        $html .= '<option value="'.$tag->slug.'"';
        if($def) {
            if (in_array($tag->slug, $def)) {
                $html .= ' selected="selected" ';
            }
        }

        $html .= ">{$tag->name}</option>";
    }

    $html .= '</select>';
    return $html;
}


//Adding the Open Graph in the Language Attributes
function chow_add_opengraph_doctype( $output ) {
    return $output . '  prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml"';
  }
add_filter('language_attributes', 'chow_add_opengraph_doctype');

//Lets add Open Graph Meta Info

function  chow_insert_fb_in_head() {
  global $post;
  if ( !is_singular()) //if it is not a post or a page
    return;

        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="Your Site NAME Goes HERE"/>';
    if(has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
    }
  echo "
";
}
add_action( 'wp_head', 'chow_insert_fb_in_head', 5 );

?>
