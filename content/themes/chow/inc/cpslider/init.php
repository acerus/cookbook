<?php
/*
Plugin Name: cpSlider
Plugin URI: http://www.purethemes.net/
Description: WYSIWYG fullscreen cpgraphy slider!
Version: 1.0
Author: purethemes.net
*/

/**
*
*/

require "inc/class.sliders.php";

class CP_Slider
{

    function __construct()
    {

      //  add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
        add_action( 'admin_menu', array( $this, 'add_menus' ) );
        if(is_admin()) {

            add_action( 'wp_ajax_get_cpslide_thumb', array($this, 'ajax_get_cpslide_thumb') );
        }
    }

    public function register_plugin_styles() {
        wp_register_style( 'cpslider-css', get_template_directory_uri().'/inc/cpslider/css/cpslider.css' );
        wp_enqueue_style( 'cpslider-css' );
    }

    public function register_plugin_scripts() {

        wp_register_script( 'cpslider-js',  get_template_directory_uri().'/inc/cpslider/js/cpslider.js' );
        wp_enqueue_script( 'cpslider-js' );
        if ( is_home() || is_page()) {
            if(ot_get_option('pp_slider_on') == 'on' ) {
                $slider = ot_get_option('pp_slider_select');
                if(is_page()) {
                    global $post;
                    $slider = get_post_meta($post->ID, 'pp_page_slider', true);
                }
                $sliderarray = get_option( 'cp_slider_'.$slider );
                
                if($sliderarray['autoPlay'] == "true") { $autoPlay = true; }  else { $autoPlay = false; }
                if($sliderarray['loop'] == "true") { $loop = true; }  else { $loop = false; }
                if($sliderarray['fadeinLoadedSlide'] == "true") { $fadeinLoadedSlide = true; }  else { $fadeinLoadedSlide = false; }
                
                if(isset($sliderarray['delay'])) {
                   $delay= $sliderarray['delay'];
                } else {
                    $delay = 3000;
                }
                wp_localize_script('cpslider-js', 'cpslidervars', array(
                            'autoPlay' => $autoPlay,
                            'delay' => $delay,
                            'loop' => $loop,
                            'numImagesToPreload' => $sliderarray['numImagesToPreload'],
                            'fadeinLoadedSlide' => $fadeinLoadedSlide,
                            'imageScaleMode' => $sliderarray['imageScaleMode'],
                            'transitionType' => $sliderarray['transitionType'],
/*                            'transitionSpeed' => $sliderarray['transitionSpeed'],*/
                        )
                    );
            }
        }
    }

    public function add_menus() {

        if ( array_key_exists( 'page', $_GET ) && 'cp-slider' == $_GET['page'] )  {

            wp_register_style( 'cpslider-admin-css', get_template_directory_uri() . '/inc/cpslider/css/cpslider.admin.css' );
            wp_enqueue_style( 'cpslider-admin-css' );


            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'wp-ajax-response' );

            wp_enqueue_media();
            wp_enqueue_script( 'postbox' );

            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style("wp-jquery-ui-dialog");

            wp_register_script(
                'cp-slider-js',                                         /* handle */
                get_template_directory_uri().'/inc/cpslider/js/cpslider.admin.js',   /* src */
                array(
                    'jquery', 'jquery-ui-draggable', 'jquery-ui-droppable',
                    'jquery-ui-sortable'
                    ),                                                          /* deps */
                date("YmdHis", @filemtime(  get_template_directory_uri().'/inc/cpslider/js/cpslider.admin.js'  ) ),            /* ver */
                true                                                        /* in_footer */
                );
            wp_enqueue_script( 'cp-slider-js' );
            wp_localize_script( 'cp-slider-js', 'CPVars', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'getthumb' => wp_create_nonce( 'cp_getthumb_ajax_nonce' ),
                ));
        }

        /* Top-level menu page */
        add_menu_page(

            __( 'Chow Slider', 'purepress' ),                                 /* title of options page */
            __( 'Chow Slider', 'purepress' ),                                 /* title of options menu item */
            'edit_theme_options',                               /* permissions level */
            'cp-slider',                                                 /* menu slug */
            array( $this, 'print_slide_groups_page' ),             /* callback to print the page to output */
            'dashicons-slides',    /* icon file */
            null                                                            /* menu position number */
            );

        /* First child, 'Slide Groups' */


    }

    /***********    // !Print functions for each page  ***********/

    public static function print_slide_groups_page() {

        require( dirname( __FILE__ ) . '/inc/admin.sliders.php' );

    }

    public static function print_slides_page() {

        require( dirname( __FILE__ ) . '/inc/admin.slides.php' );

    }

    function ajax_get_cpslide_thumb() {
        //check_ajax_referer('custom_nonce');
        if(isset($_POST['thumbid'])) {
            $image = wp_get_attachment_image_src( $_POST['thumbid'] );
            echo $image[0];
        }
        die();
    }
    function get_cpslide_thumb($id) {
        //check_ajax_referer('custom_nonce');
        $image = wp_get_attachment_image_src( $id );
        return $image[0];
    }

    public function getCPslider($name) {
        $slider = get_option( 'cp_slider_'.$name );
   
        if(!empty($slider)) {
            switch ($slider['slidertype']) {
                case 'postssel':
                    $args= array(
                        'post_type' =>  array('recipe','post','page'),
                        'post__in' => $slider['posts'],
                        'meta_key'    => '_thumbnail_id',
                        'post__not_in' => get_option( 'sticky_posts' ),
                        'orderby' => 'post__in'
                        );
                break;

                case 'posts':
                    if($slider['posts_type'] == 'random') {
                        $orderby = 'rand';
                    } else {
                        $orderby = $slider['posts_order'];
                    }
                    switch ($slider['content_type']) {
                        case 'posts':
                            $content =   array('post');
                            break;                        
                        case 'recipes':
                            $content =  array('recipe');
                            break;                        
                        case 'both':
                            $content =  array('recipe','post');
                            break;
                        
                        default:
                            $content =  array('recipe','post');
                            break;
                    }

                    $args= array(
                        'post_type' =>  $content,
                        'posts_per_page'   => $slider['posts_number'],
                        'orderby' => $orderby,
                        'meta_key'    => '_thumbnail_id',
                        'post__not_in' => get_option( 'sticky_posts' ),
                        'category__in' => $slider['cats'],
                        'tag__and' => $slider['tags']
                        );

                break;

                default:
                # code...
                break;
            }

            ?>
            <?php if($slider['style'] == 'below') { ?>
            <div id="homeSlider" class="royalSlider rsDefaultInv">
                <?php
                    $cpslider_query = new WP_Query($args);
                    while ($cpslider_query->have_posts()) : $cpslider_query->the_post();
                    $selected_image_id = get_post_meta($cpslider_query->post->ID, 'pp_post_slider_img', true);
                    $selected_image   = wp_get_attachment_image_src( $selected_image_id, 'slider-big' );
                    $thumb = wp_get_attachment_image_src ( get_post_thumbnail_id (), 'slider-big' );
                ?>
                <!-- Slide #1 -->
                <div class="rsContent">
                    <?php if(empty($selected_image[0])){ ?>
                    <a class="rsImg" href="<?php echo esc_url($thumb[0]); ?>"></a>
                    <?php } else { ?>
                    <a class="rsImg" href="<?php echo esc_url($selected_image[0]); ?>"></a>
                    <?php } ?>
                    <i class="rsTmb"><?php the_title()?></i>

                    <!-- Slide Caption -->
                    <div class="SlideTitleContainer rsABlock">
                    <div class="CaptionAlignment">
                    <?php if(isset($slider['dis_categories']) && empty($slider['dis_categories'])) { ?>
                        <div class="rsSlideTitle tags">
                            <?php echo get_the_category_list(); ?>
                            <div class="clearfix"></div>
                        </div>
                    <?php } ?>
                        <h2 class="rsSlideTitle title"><a href="<?php the_permalink(); ?>"><?php the_title()?></a></h2>

                        <div class="rsSlideTitle details">
                            <ul>
                                <?php $id = get_the_ID(); ?>
                                <?php do_action('chow-slider-before-details'); ?>
                                
                                <?php  
                                if(isset($slider['dis_servings']) && empty($slider['dis_servings'])) {
                                    $serving = get_the_term_list( $id, 'serving', ' ', ', ', '  ' );
                                    if(!empty($serving)) { ?>
                                    <li><i class="fa fa-cutlery"></i> <?php echo $serving; ?></li>
                                    <?php } 
                                } ?>
                                
                                <?php  
                                if(isset($slider['dis_time']) && empty($slider['dis_time'])) {
                                    $time = get_the_term_list( $id, 'timeneeded', ' ', ', ', '  ' );
                                    if(!empty($time)) { ?>
                                    <li><i class="fa fa-clock-o"></i> <?php echo $time; ?></li>
                                    <?php } 
                                }
                                ?>
                                <?php  
                                if(isset($slider['dis_author']) && empty($slider['dis_author'])) { ?>
                                <li><i class="fa fa-user"></i> <?php _e('by','chow'); ?> <?php echo get_the_author_link(); ?></li>
                                <?php } ?>
                                <?php do_action('chow-slider-after-details'); ?>
                            </ul>
                        </div>

                        <a href="<?php the_permalink(); ?>" class="rsSlideTitle button">
                            <?php 
                            if(check_if_recipe()){
                                _e('View Recipe','chow'); 
                            } else {
                                _e('View Post','chow'); 
                            }
                            ?>
                        </a>
                    </div>
                    </div>
                </div>
                <?php endwhile; wp_reset_query();?>
            </div>

            <?php } else { ?>
<div class="container">
    <div class="sixteen columns">
            <div id="homeSliderAlt" class="royalSlider homeSliderAlt rsDefault">
               <?php
                    $cpslider_query = new WP_Query($args);
                    while ($cpslider_query->have_posts()) : $cpslider_query->the_post();
                    $selected_image_id = get_post_meta($cpslider_query->post->ID, 'pp_post_slider_img', true);
                    $selected_image   = wp_get_attachment_image_src( $selected_image_id, 'slider-big' );
                    $thumb = wp_get_attachment_image_src ( get_post_thumbnail_id (), 'slider-big' );
                ?>
                <div class="rsContent">
                    <?php if(empty($selected_image[0])){ ?>
                        <a class="rsImg" href="<?php echo $thumb[0]; ?>"></a>
                    <?php } else { ?>
                        <a class="rsImg" href="<?php echo $selected_image[0]; ?>"></a>
                    <?php } ?>
                        <div class="rsTmb">
                            <h5><?php the_title()?></h5>
                        </div>
                    </a>
                    <a href="<?php the_permalink(); ?>" class="rsABlock blockTitle"><?php the_title()?></a>
                </div>
                <?php endwhile; wp_reset_query();?>
            </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="margin-top-45"></div>

            <?php } ?>

        <?php
        }
    }
}
?>