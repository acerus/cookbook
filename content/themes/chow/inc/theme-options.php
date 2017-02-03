<?php
/**
 * Initialize the custom Theme Options.
 */
add_action( 'admin_init', 'custom_theme_options' );

/**
 * Build the custom settings & update OptionTree.
 *
 * @return    void
 * @since     2.0
 */
function custom_theme_options() {
   global $wpdb;
   $revsliders = array();
   /**
   * Get a copy of the saved settings array.
   */
    $saved_settings = get_option( ot_settings_id(), array() );
 $current_sliders = get_option( 'cp_sliders');

    // Iterate over the sliders
    if($current_sliders) {
        foreach($current_sliders as $key => $item) {
          $cpsliders[] = array(
            'label' => $item->name,
            'value' => $item->slug
            );
      }
    } else {
        $cpsliders[] = array(
          'label' => 'No Sliders Found',
          'value' => ''
          );
    }


    $table_name = $wpdb->prefix . "revslider_sliders";
    // Get sliders

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
      $sliders = $wpdb->get_results( "SELECT alias, title FROM $table_name" );
    } else {
      $sliders = '';
    }

    if($sliders) {
      foreach($sliders as $key => $item) {
        $revsliders[] = array(
          'label' => $item->title,
          'value' => $item->alias
          );
      }
    } else {
      $revsliders[] = array(
        'label' => 'No Sliders Found',
        'value' => ''
        );
    }
  /**
   * Custom settings array that will eventually be
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array(
    'contextual_help' => array(
      'content'       => array(
        array(
          'id'        => 'option_types_help',
          'title'     => __( 'Option Types', 'chow' ),
          'content'   => '<p>' . __( 'Help content goes here!', 'chow' ) . '</p>'
        )
      ),
      'sidebar'       => '<p>' . __( 'Sidebar content goes here!', 'chow' ) . '</p>'
    ),
    'sections'        => array(
      array(
        'id'          => 'slider',
        'title'       => __( 'Slider', 'chow' )
      ),
      array(
        'title'       => 'General',
        'id'          => 'general_default'
        ), 
      array(
        'title'       => 'Typography',
        'id'          => 'typography'
        ),
      array(
        'id'          => 'header',
        'title'       => __( 'Header', 'chow' )
      ),
      array(
        'id'          => 'blog',
        'title'       => __( 'Blog', 'chow' )
      ),      
      array(
        'id'          => 'footer',
        'title'       => __( 'Footer', 'chow' )
      ),
      array(
        'id'          => 'shop',
        'title'       => __( 'Shop', 'chow' )
      ),
      array(
        'id'          => 'sidebars',
        'title'       => __( 'Sidebars', 'chow' )
      ),    
   
    ),

    'settings'        => array(
        
        array(
            'label'       => 'Enable Chow Slider on homepage',
            'id'          => 'pp_slider_on',
            'type'        => 'on_off',
            'desc'        => 'Show slider on homepage',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'slider'
            ),   
        array(
            'label'       => 'Use RevolutionSlider as homepage slider',
            'id'          => 'pp_revslider_on',
            'type'        => 'on_off',
            'desc'        => 'Available only if you have <a href="http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380?ref=purethemes">RevolutionSlider</a> installed ',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'slider'
        ),
        array(
          'label'       => 'Choose Revolution Slider for homepage',
          'id'          => 'pp_revo_slider',
          'type'        => 'select',
          'desc'        => '',
          'choices'     => $revsliders,
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'condition'   => 'pp_revslider_on:is(on)',
          'section'     => 'slider'
        ),
        array(
            'label'       => 'Select slider',
            'id'          => 'pp_slider_select',
            'type'        => 'select',
            'desc'        => 'Select slider',
            'choices'     => $cpsliders,
            'std'         => 'true',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'slider'
            ),
        array(
          'label'       => 'Logo area width',
          'id'          => 'pp_logo_area_width',
          'type'        => 'select',
          'desc'        => 'Full width of top area is 16 columns. Logo area by default is 13 columns, while icons and contact details area is 3 columns wide. If you want to have bigger logo, please change here number of columns for logo. ',
          'choices'     => array(
            array('label'  => '1 column','value' => '1'),
            array('label'  => '2 columns','value' => '2'),
            array('label'  => '3 columns','value' => '3'),
            array('label'  => '4 columns','value' => '4'),
            array('label'  => '5 columns','value' => '5'),
            array('label'  => '6 columns','value' => '6'),
            array('label'  => '7 columns','value' => '7'),
            array('label'  => '8 columns','value' => '8'),
            array('label'  => '9 columns','value' => '9'),
            array('label'  => '10 columns','value' => '10'),
            array('label'  => '11 columns','value' => '11'),
            array('label'  => '12 columns','value' => '12'),
            array('label'  => '16 columns','value' => '16'),
            ),
          'std'         => '3',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
        ),
        array(
          'label'       => 'Center logo and menu?',
          'id'          => 'pp_center_header',
          'type'        => 'on_off',
          'desc'        => 'Works only if logo width is set to 16 columns',
          'std'         => 'off',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'condition'   => 'pp_logo_area_width:is(16)',
          'section'     => 'header'
        ),
        array(
            'label'       => 'Upload logo',
            'id'          => 'pp_logo_upload',
            'type'        => 'upload',
            'desc'        => 'The logo will be used as it is so please resize it before uploading ',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'header'
        ),
        array(
            'label'       => 'Upload Retina logo',
            'id'          => 'pp_logo_retina_upload',
            'type'        => 'upload',
            'desc'        => 'Double sized logo version. You can either double the amount of pixels, or the dpi, it’s the same thing. So if your logo.png file is 200×100, make the @2x file 400×200, or just double the dpi (from 72 to 144 for example.)',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'header'
        ),
        array(
          'label'       => 'Logo top margin',
          'id'          => 'pp_logo_top_margin',
          'type'        => 'measurement',
          'desc'        => 'Set top margin for logo image',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
          ),
        array(
          'label'       => 'Logo bottom margin',
          'id'          => 'pp_logo_bottom_margin',
          'type'        => 'measurement',
          'desc'        => 'Set bottom margin for logo image',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
        ),
        array(
          'label'       => 'Menu top margin',
          'id'          => 'pp_menu_top_margin',
          'type'        => 'measurement',
          'desc'        => 'Set top margin for menu',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
          ),        
        array(
          'label'       => 'Tagline top margin',
          'id'          => 'pp_tagline_margin',
          'type'        => 'measurement',
          'desc'        => 'Set bottom margin for tagline (blog description)',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
        ),
 
        
        array(
          'label'       => 'Enable breadcrumbs',
          'id'          => 'pp_breadcrumbs',
          'type'        => 'on_off',
          'desc'        => '',
          'std'         => 'on',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'general_default'
        ),
       array(
            'label'       => 'Enable "Add to Favourites" under posts',
            'id'          => 'pp_add_to_fav_status',
            'type'        => 'on_off',
            'desc'        => '',
            'std'         => 'on',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'general_default'
        ),
        array(
            'label'       => 'Choose "Edit Page" - the page needs to use template named "Edit Recipe Template"',
            'id'          => 'pp_edit_page',
            'type'        => 'page_select',
            'desc'        => 'This is page to which contributors will be redirected',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'general_default'
        ),        
        array(
            'label'       => 'Choose "My Account Page" - the page needs to use template named "Chow User Account Template"',
            'id'          => 'pp_account_page',
            'type'        => 'page_select',
            'desc'        => 'This is page which acts as a dashboard for users',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'general_default'
        ),
        array(
          'label'       => 'Comments on pages',
          'id'          => 'pp_pagecomments',
          'type'        => 'on_off',
          'desc'        => 'You can disable globaly comments on all pages with this option, or you can do it per page in Page editor',
          'std'         => 'off',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'general_default'
        ),
        array(
              'id'          => 'pp_custom_css',
              'label'       => 'Custom CSS',
              'desc'        => 'To prevent problems with theme update, write here any custom css (or use child themes)',
              'std'         => '',
              'type'        => 'textarea-simple',
              'section'     => 'general_default',
              'rows'        => '',
              'post_type'   => '',
              'taxonomy'    => '',
              'class'       => ''
        ),
        array(
            'id'          => 'pp-fonts',
            'label'       => __( 'Google Fonts', 'chow' ),
            'desc'        => '',
            'std'         => array( 
                array(
                    'family'    => 'opensans',
                    'variants'  => array( '300', '400', '600', '700', '800' ),
                    'subsets'   => array( 'latin' )
                ),
                array(
                    'family'    => 'Arvo',
                    'variants'  => array( 'regular', '700'),
                    'subsets'   => array( 'latin')
                )
            ),
            'type'        => 'google-fonts',
            'section'     => 'typography',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => '',
            'operator'    => 'and'
        ),
        array(
          'label'       => 'Body Font',
          'id'          => 'chow_body_font',
          'type'        => 'typography',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),
        array(
          'label'       => 'Menu Font',
          'id'          => 'chow_menu_font',
          'type'        => 'typography',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),
        array(
          'label'       => 'Logo Font',
          'id'          => 'chow_logo_font',
          'type'        => 'typography',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),     
        array(
          'label'       => 'Headers (h1..h6) Font',
          'id'          => 'chow_headers_font',
          'type'        => 'typography',
          'desc'        => 'Size and related to it settings will be ignored here.',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),
        array(
          'label'       => 'Slider Headers Font',
          'id'          => 'chow_slider_font',
          'type'        => 'typography',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
        ),
        array(
            'label'       => 'Blog layout',
            'id'          => 'pp_blog_layout',
            'type'        => 'radio-image',
            'desc'        => 'Choose sidebar side on blog.',
            'std'         => 'right-sidebar',
            'rows'        => '',
            'post_type'   => '',
            'choices'     => array(
                array(
                    'value'   => 'left-sidebar',
                    'label'   => 'Left Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
                    ),
                array(
                    'value'   => 'right-sidebar',
                    'label'   => 'Right Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
                    ),
                array(
                    'value'   => 'left-sidebar-grid',
                    'label'   => 'left Sidebar Grid',
                    'src'     => OT_URL . '/assets/images/layout/left-sidebar-grid.png'
                    ),
                array(
                    'value'   => 'right-sidebar-grid',
                    'label'   => 'right Sidebar Grid',
                    'src'     => OT_URL . '/assets/images/layout/right-sidebar-grid.png'
                    ),
                array(
                    'value'   => 'masonry',
                    'label'   => 'Masonry',
                    'src'     => OT_URL . '/assets/images/layout/masonry.png'
                    ),

                ),
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
            'label'       => 'Recipes blog layout',
            'id'          => 'pp_recipes_layout',
            'type'        => 'radio-image',
            'desc'        => 'Choose layout for recipes section.',
            'std'         => 'right-sidebar',
            'rows'        => '',
            'post_type'   => '',
            'choices'     => array(
                array(
                    'value'   => 'left-sidebar',
                    'label'   => 'Left Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
                    ),
                array(
                    'value'   => 'right-sidebar',
                    'label'   => 'Right Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
                    ),
                array(
                    'value'   => 'left-sidebar-grid',
                    'label'   => 'left Sidebar Grid',
                    'src'     => OT_URL . '/assets/images/layout/left-sidebar-grid.png'
                    ),
                array(
                    'value'   => 'right-sidebar-grid',
                    'label'   => 'right Sidebar Grid',
                    'src'     => OT_URL . '/assets/images/layout/right-sidebar-grid.png'
                    ),
                array(
                    'value'   => 'masonry',
                    'label'   => 'Masonry',
                    'src'     => OT_URL . '/assets/images/layout/masonry.png'
                    ),

                ),
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
            'label'       => 'Display advanced recipes search on homepage',
            'id'          => 'pp_home_adv_search',
            'type'        => 'on_off',
            'desc'        => 'Advanced recipe form will be displayed on home page',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
         array(
          'label'       => 'Grid layout isotope mode',
          'id'          => 'pp_isotope_mode',
          'type'        => 'select',
          'desc'        => '',
          'choices'     => array(
            array('label'  => 'Masonry','value' => 'masonry'),
            array('label'  => 'Fit rows','value' => 'fitRows'),
          ),
          'std'         => 'masonry',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
        ),
      array(
            'label'       => 'Blog section title',
            'id'          => 'pp_blog_title',
            'type'        => 'text',
            'desc'        => '',
            'std'         => 'Latest Posts',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),   
      array(
            'label'       => 'Recipies section title',
            'id'          => 'pp_recipes_title',
            'type'        => 'text',
            'desc'        => '',
            'std'         => 'Latest Recipes',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
          'label'       => 'Post meta informations on single post',
          'id'          => 'pp_meta_single',
          'type'        => 'checkbox',
          'desc'        => 'Set which elements of posts meta data you want to display.',
          'choices'     => array(
            array (
              'label'       => 'Author',
              'value'       => 'author'
              ),
            array (
              'label'       => 'Date',
              'value'       => 'date'
              ),
            array (
              'label'       => 'Tags',
              'value'       => 'tags'
              ),
            array (
              'label'       => 'Categories',
              'value'       => 'cat'
              ),
            array (
              'label'       => 'Comments',
              'value'       => 'com'
              )
            ),
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
          ),
        array(
            'label'       => 'Global background header on post',
            'id'          => 'pp_header_bg_status',
            'type'        => 'on_off',
            'desc'        => 'Enable global background header on single post displayed in case the post doesn\'t have individual background set',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
            'label'       => 'Global background header image',
            'id'          => 'pp_header_bg',
            'type'        => 'upload',
            'desc'        => 'Displayed if post doesn\'t have individual background image',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'condition'   => 'pp_header_bg_status:is(on)',
            'section'     => 'blog'
        ),
        array(
            'label'       => 'Select which \'social share\' icons to display on post',
            'id'          => 'pp_post_share',
            'type'        => 'checkbox',
            'desc'        => '',
            'choices'     => array(
                array (
                    'label'       => 'Facebook',
                    'value'       => 'facebook'
                    ),
                array (
                    'label'       => 'Twitter',
                    'value'       => 'twitter'
                    ),
                array (
                    'label'       => 'Google Plus',
                    'value'       => 'google-plus'
                    ),
                array (
                    'label'       => 'Pinterest',
                    'value'       => 'pinterest'
                    ),
                ),
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
            ),
        array(
            'label'       => 'Select which \'post meta\' elements to display on blog page with <strong>List layout</strong>',
            'id'          => 'pp_list_meta',
            'type'        => 'checkbox',
            'desc'        => '',
            'choices'     => array(
                array (
                    'label'       => 'Author',
                    'value'       => 'author'
                    ),
                array (
                    'label'       => 'Date',
                    'value'       => 'date'
                    ),
                array (
                    'label'       => 'Comments',
                    'value'       => 'comments'
                    ),
                array (
                    'label'       => 'Categories',
                    'value'       => 'categories'
                    ),
                array (
                    'label'       => 'Tags',
                    'value'       => 'tags'
                    ),
                array (
                    'label'       => 'Servings (recipe)',
                    'value'       => 'servings'
                    ),
                array (
                    'label'       => 'Preperation Time (recipe)',
                    'value'       => 'recipe_time'
                    ),
                array (
                    'label'       => 'Level (recipe)',
                    'value'       => 'level'
                    ),
                array (
                    'label'       => 'Allergens (recipe)',
                    'value'       => 'allergens'
                    ),
                ),
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
            ),
        array(
            'label'       => 'On "List View" display Summary of Recipe (if available) instead of post excerpt',
            'id'          => 'pp_list_view_summary',
            'type'        => 'on_off',
            'desc'        => 'On "List View" display Summary of Recipe instead of post excerpt',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
            'label'       => 'Select which \'post meta\' elements to display on blog page with <strong>Grid layout</strong>',
            'id'          => 'pp_grid_meta',
            'type'        => 'checkbox',
            'desc'        => '',
            'choices'     => array(
                array (
                    'label'       => 'Author',
                    'value'       => 'author'
                    ),
                array (
                    'label'       => 'Date',
                    'value'       => 'date'
                    ),
                array (
                    'label'       => 'Comments',
                    'value'       => 'comments'
                    ),
                array (
                    'label'       => 'Categories',
                    'value'       => 'categories'
                    ),
                array (
                    'label'       => 'Tags',
                    'value'       => 'tags'
                    ),
                array (
                    'label'       => 'Servings (recipe)',
                    'value'       => 'servings'
                    ),
                array (
                    'label'       => 'Preperation Time (recipe)',
                    'value'       => 'recipe_time'
                    ),
                array (
                    'label'       => 'Level (recipe)',
                    'value'       => 'level'
                    ),
                array (
                    'label'       => 'Allergens (recipe)',
                    'value'       => 'allergens'
                    ),
                ),
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
            ),
          array(
            'label'       => 'Select which \'post meta\' elements to display on blog page for post that are <strong>not recipe</strong>',
            'id'          => 'pp_meta_no_recipe',
            'type'        => 'checkbox',
            'desc'        => '',
            'choices'     => array(
                array (
                    'label'       => 'Author',
                    'value'       => 'author'
                    ),
                array (
                    'label'       => 'Date',
                    'value'       => 'date'
                    ),
                array (
                    'label'       => 'Comments',
                    'value'       => 'comments'
                    ),
                array (
                    'label'       => 'Categories',
                    'value'       => 'categories'
                    ),
                array (
                    'label'       => 'Tags',
                    'value'       => 'tags'
                    ),
                ),
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
            ),
        array(
            'label'       => 'Add blog post content to print view?',
            'id'          => 'pp_print_content',
            'type'        => 'on_off',
            'desc'        => 'By defautl (if OFF) only recipe content is getting printed',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
          ),   
        array(
            'label'       => 'Copyrights text',
            'id'          => 'pp_copyrights',
            'type'        => 'text',
            'desc'        => 'Text in footer',
            'std'         => '&copy; Theme by <a href="http://themeforest.net/user/purethemes/portfolio?ref=purethemes">Purethemes.net</a>. All Rights Reserved.',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'footer'
        ),
        array(
          'label'       => 'Footer widgets layout',
          'id'          => 'pp_footer_widgets',
          'type'        => 'select',
          'desc'        => 'Total width of footer is 16 columns, here you can decide layout based on columns number for each widget area in footer',
          'choices'     => array(
            array('label'  => '5 | 3 | 3 | 5','value' => '5,3,3,5'),
            array('label'  => '4 | 4 | 4 | 4','value' => '4,4,4,4'),
            array('label'  => '8 | 8','value' => '8,8'),
            array('label'  => '1/3 | 2/3','value' => '1/3,2/3'),
            array('label'  => '2/3 | 1/3','value' => '2/3,1/3'),
            array('label'  => '1/3 | 1/3 | 1/3','value' => '1/3,1/3,1/3'),
          ),
          'std'         => '5,3,3,5',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'footer'
        ),
        array(
            'label'       => 'Shop sidebar side',
            'id'          => 'pp_shop_layout',
            'type'        => 'radio-image',
            'desc'        => 'Choose sidebar side on blog page.',
            'std'         => 'full-width',
            'rows'        => '',
            'post_type'   => '',
            'choices'     => array(
                array(
                    'value'   => 'left-sidebar',
                    'label'   => 'Left Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
                    ),
                array(
                    'value'   => 'right-sidebar',
                    'label'   => 'Right Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
                    ),
                array(
                    'value'   => 'full-width',
                    'label'   => 'Full Width (no sidebar)',
                    'src'     => OT_URL . '/assets/images/layout/full-width.png'
                    )

                ),
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'shop'
        ),
        array(
          'label'       => 'WooCommerce number of items per page',
          'id'          => 'pp_wooitems',
          'type'        => 'select',
          'desc'        => 'Select how many products you want to display on shop page',
          'std'         => '9',
          'rows'        => '',
          'choices'     => array(
            array('label'=> '3','value'=> '3'),
            array('label'=> '4','value'=> '4'),
            array('label'=> '5','value'=> '5'),
            array('label'=> '6','value'=> '6'),
            array('label'=> '7','value'=> '7'),
            array('label'=> '8','value'=> '8'),
            array('label'=> '9','value'=> '9'),
            array('label'=> '10','value'=> '10'),
            array('label'=> '11','value'=> '11'),
            array('label'=> '12','value'=> '12'),
            array('label'=> '13','value'=> '13'),
            array('label'=> '14','value'=> '14'),
            array('label'=> '15','value'=> '15'),
            array('label'=> '16','value'=> '16'),
            array('label'=> '20','value'=> '20'),
            array('label'=> '32','value'=> '32'),
            array('label'=> '40','value'=> '40'),
            array('label'=> '99','value'=> '99'),
            ),
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'shop'
          ),
        array(
          'label'       => 'Revert Product Gallery to original WooCommerce gallery',
          'id'          => 'pp_product_default_gallery',
          'type'        => 'on_off',
          'desc'        => 'This will remove Royal Slider and show original gallery for WooCommerce - makes it compatible with some 3rd party plugins',
          'std'         => 'off',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'shop'
          ),
       array(
          'id'          => 'sidebars_text',
          'label'       => 'About sidebars',
          'desc'        => 'All sidebars that you create here will appear both in the Appearance > Widgets, and then you can choose them for specific pages or posts.',
          'std'         => '',
          'type'        => 'textblock',
          'section'     => 'sidebars',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => ''
          ),
        array(
          'label'       => 'Create Sidebars',
          'id'          => 'incr_sidebars',
          'type'        => 'list-item',
          'desc'        => 'Choose a unique title for each sidebar',
          'section'     => 'sidebars',
          'settings'    => array(
            array(
              'label'       => 'ID',
              'id'          => 'id',
              'type'        => 'text',
              'desc'        => 'Write a lowercase single world as ID (it can\'t start with a number!), without any spaces',
              'std'         => 'my_new_sidebar',
              'rows'        => '',
              'post_type'   => '',
              'taxonomy'    => '',
              'class'       => ''
              )
            )
          ),

    )
  );

  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( ot_settings_id(), $custom_settings );
  }

}