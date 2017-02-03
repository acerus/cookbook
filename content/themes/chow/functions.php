<?php
/**
 * Chow functions and definitions
 *
 * @package Chow
 */

add_theme_support( 'woocommerce' );
/**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter( 'ot_show_pages', '__return_false' );


/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Show New Layout
 */
add_filter( 'ot_show_new_layout', '__return_false' );


/**
 * Custom Theme Option page
 */
add_filter( 'ot_use_theme_options', '__return_true' );

/**
 * Meta Boxes
 */
add_filter( 'ot_meta_boxes', '__return_true' );

/**
 * Loads the meta boxes for post formats
 */
add_filter( 'ot_post_formats', '__return_true' );

add_filter( 'ot_recognized_post_format_meta_boxes', 'expand_ot_post_formats_to_recipes' );
function expand_ot_post_formats_to_recipes(){
	return array(
        ot_meta_box_post_format_gallery('post,recipe'),
        ot_meta_box_post_format_link('post,recipe'),
        ot_meta_box_post_format_quote('post,recipe'),
        ot_meta_box_post_format_video('post,recipe'),
        ot_meta_box_post_format_audio('post,recipe'),
      );
}
/**
 * Required: include OptionTree.
 */
require( trailingslashit( get_template_directory() ) . 'option-tree/ot-loader.php' );

/**
 * Theme Options
 */
load_template( trailingslashit( get_template_directory() ) . 'inc/theme-options.php' );

/**
 * Meta Boxes
 */
load_template( trailingslashit( get_template_directory() ) . 'inc/meta-boxes.php' );


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 860; /* pixels */
}

if ( ! function_exists( 'chow_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function chow_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Chow, use a find and replace
	 * to change 'chow' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'chow', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'chow' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array('gallery','video') );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'chow_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	chow_postmeta_tags();
}
endif; // chow_setup
add_action( 'after_setup_theme', 'chow_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function chow_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'chow' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="headline">',
		'after_title'   => '</h4><span class="line margin-bottom-30"></span><div class="clearfix"></div>',
		) );
	register_sidebar( array(
		'name'          => __( 'Shop', 'chow' ),
		'id'            => 'shop',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="headline">',
		'after_title'   => '</h4><span class="line margin-bottom-30"></span><div class="clearfix"></div>',
		) );

	register_sidebar(array(
		'id' => 'footer1',
		'name' => 'Footer 1st Column',
		'description' => '1st column for widgets in Footer.',
		'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</div>',
		'before_title'  => '<h3 class="headline footer">',
		'after_title'   => '</h3><span class="line"></span><div class="clearfix"></div>',
		));
	register_sidebar(array(
		'id' => 'footer2',
		'name' => 'Footer 2nd Column',
		'description' => '2nd column for widgets in Footer.',
		'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</div>',
		'before_title'  => '<h3 class="headline footer">',
		'after_title'   => '</h3><span class="line"></span><div class="clearfix"></div>',
		));
	register_sidebar(array(
		'id' => 'footer3',
		'name' => 'Footer 3rd Column',
		'description' => '3rd column for widgets in Footer.',
		'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</div>',
		'before_title'  => '<h3 class="headline footer">',
		'after_title'   => '</h3><span class="line"></span><div class="clearfix"></div>',
		));
	register_sidebar(array(
		'id' => 'footer4',
		'name' => 'Footer 4th Column',
		'description' => '4th column for widgets in Footer.',
		'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</div>',
		'before_title'  => '<h3 class="headline footer">',
		'after_title'   => '</h3><span class="line"></span><div class="clearfix"></div>',
		));
         //custom sidebars:
	if (ot_get_option('incr_sidebars')):
		$pp_sidebars = ot_get_option('incr_sidebars');
		foreach ($pp_sidebars as $pp_sidebar) {
			register_sidebar(array(
				'name' => $pp_sidebar["title"],
				'id' => $pp_sidebar["id"],
				'before_widget' => '<div id="%1$s" class="widget widget %2$s">',
				'after_widget' => '</div>',
				'before_title'  => '<h4 class="headline footer">',
				'after_title'   => '</h4><span class="line margin-bottom-30"></span><div class="clearfix"></div>',
				));
		}
	endif;
}
add_action( 'widgets_init', 'chow_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function chow_scripts() {
	wp_enqueue_style( 'chow-style', get_stylesheet_uri() );
	wp_enqueue_style( 'chow-print', get_template_directory_uri().'/css/print.css', array(), '20140612','print' );
    wp_deregister_style('wp-pagenavi',10);

	wp_enqueue_script( 'chow-chosen', get_template_directory_uri() . '/js/chosen.jquery.min.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-superfish', get_template_directory_uri() . '/js/jquery.superfish.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-hoverIntent', get_template_directory_uri() . '/js/hoverIntent.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-royalslider', get_template_directory_uri() . '/js/jquery.royalslider.min.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-isotope', get_template_directory_uri() . '/js/isotope.pkgd.min.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-gmaps', get_template_directory_uri() . '/js/jquery.gmaps.min.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-tooltip', get_template_directory_uri() . '/js/jquery.tooltips.min.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-responsive-nav', get_template_directory_uri() . '/js/responsive-nav.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-dropzone', get_template_directory_uri() . '/js/dropzone.js', array(), '20140612', true );
	wp_enqueue_script( 'chow-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '20140612', true );
	wp_enqueue_script( 'jquery-ui-sortable' );
	if( is_page_template('template-submitrecipe.php')) {
		$tags = get_terms('ingredients', array('hide_empty' => false));
		$tags_arr = array();
		foreach ($tags as $tag) {
			array_push($tags_arr, $tag->name);
		}
		wp_enqueue_script( 'chow-submit', get_template_directory_uri() . '/js/submit.js', array('jquery'), '20140612', true );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_localize_script('chow-submit', 'foodiepress_script_vars', array(
				'availabletags' => json_encode($tags_arr),
				)
			);
	}
	wp_enqueue_script( 'chow-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_localize_script( 'chow-custom', 'chow',
    array(
        'logo'=> ot_get_option('pp_logo_upload'),
        'retinalogo'=> ot_get_option('pp_logo_retina_upload'),
        'menulabel'=> __('Menu','chow'),
        'isotope'=> ot_get_option('pp_isotope_mode','masonry'),
       
        )
    );
}
add_action( 'wp_enqueue_scripts', 'chow_scripts' );

add_action( 'wp_enqueue_scripts', 'load_chow_wc_scripts' );

function load_chow_wc_scripts() {
        if ( class_exists( 'WooCommerce' ) ) {
            global $wp_scripts;
            $gallerytype = ot_get_option('pp_product_default_gallery','off');
            if($gallerytype == 'off') {
                $wp_scripts->registered[ 'wc-add-to-cart-variation' ]->src = get_template_directory_uri() . '/woocommerce/js/add-to-cart-variation.js';
            }
        }
    }


/**
 * Implement the Custom Header feature.
 */

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Widgets.
 */
require_once get_template_directory() . '/inc/widgets.php';

/**
 * Load Shortcodes.
 */
require_once get_template_directory() . '/inc/shortcodes.php';

/**
 * Load Shortcodes
 */
require_once( get_template_directory() . '/inc/ptshortcodes.php' );

/**
 * Custom functions that act independently of the theme templates
 */
require_once( get_template_directory() . '/inc/tgmpa.php' );

/**
 * WooCommerce related code
 */
require_once( get_template_directory() . '/inc/woocommerce.php' );

/**
 * Load submit post file .
 */
require get_template_directory() . '/inc/submitpost.php';


/**
 * Load bbPress compatibility
 */
require_once( get_template_directory() . '/inc/bbpress.php' );

/**
 * Load AQ_Resize
 */
require get_template_directory() . '/inc/aq_resize.php';


require_once( 'inc/cpslider/init.php'); // Typoslider
$cpslider = new CP_Slider();


add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size(420, 400, true); //size of thumbs
add_image_size('thumb-list', 580, 510, true);     //slider
add_image_size('thumb-grid', 420, 0, true);     //slider
add_image_size('header-bg', 1920, 300, true);     //slider
add_image_size('slider-big', 1920, 590, true);     //slider
add_image_size('slider', 860, 380, true);     //slider
add_image_size('slider-small', 725, 0, true);     //slider
add_image_size('widgets-thumb', 460, 163, true);     //slider



function my_own_themes( $array ){
	 unset($array['tearedh']);
	 unset($array['teared']);
	 unset($array['elegant']);
	 unset($array['minimal']);
    return $array;
}
add_filter( 'foodiepress_themes', 'my_own_themes',10);


add_filter( 'widget_text', 'do_shortcode' ); 


add_action( 'pre_get_posts', 'chow_add_recipies_to_query' );

function chow_add_recipies_to_query( $query ) {
	$options = get_option( 'chow_option',array());
	if(is_array($options) && !empty($options['post_type'])) {
	    
	    if ( ! is_admin() && $query->is_home() && $query->is_main_query() ) {
	        $query->set( 'post_type', array( 'recipe' ) );
	    }
	}
    return $query;
}


add_filter('pre_get_posts', 'chow_add_recipies_to_category_query');
function chow_add_recipies_to_category_query($query) {
	$options = get_option( 'chow_option',array());
	if(is_array($options) && !empty($options['post_type'])) {
		if (
			$query->is_main_query()
			&& empty( $query->query_vars['suppress_filters'] )
			&& ( is_tag() || is_category() )
			
			) {
			// Get all your post types
			$post_types = get_post_types();

			$query->set( 'post_type', $post_types );
				return $query;
			}
		}
	return $query;
}

/**
 * Load shortcodes.
 */
require get_template_directory() . '/envato_setup/envato_setup.php';

// Please don't forgot to change filters tag.
// It must start from your theme's name.
add_filter('chow_theme_setup_wizard_username', 'chow_set_theme_setup_wizard_username', 10);
if( ! function_exists('chow_set_theme_setup_wizard_username') ){
    function chow_set_theme_setup_wizard_username($username){
        return 'purethemes';
    }
}

add_filter('chow_theme_setup_wizard_oauth_script', 'chow_set_theme_setup_wizard_oauth_script', 10);
if( ! function_exists('chow_set_theme_setup_wizard_oauth_script') ){
    function chow_set_theme_setup_wizard_oauth_script($oauth_url){
        return 'http://purethemes.net/envato/api/server-script.php';
    }
}

?>