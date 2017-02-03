<?php
/**
 * Initialize the meta boxes.
 */
add_action( 'admin_init', '_custom_meta_boxes' );

/**
 * Meta Boxes demo code.
 *
 * You can find all the available option types
 * in demo-theme-options.php.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */
function _custom_meta_boxes() {


  $blog_layout = ot_get_option('pp_blog_layout','left-sidebar');
  switch ($blog_layout) {
    case 'left-sidebar':
      $post_layout = 'left-sidebar';
      break;

    case 'right-sidebar':
      $post_layout = 'right-sidebar';
      break;    

    case 'left-sidebar-grid':
      $post_layout = 'left-sidebar';
      break;

    case 'masonry':
      $post_layout = 'left-sidebar';
      break;

    case 'right-sidebar-grid':
      $post_layout = 'right-sidebar';
      break;
    
    default:
      $post_layout = 'left-sidebar';
      break;
  }
  /**
   * Create a custom meta boxes array that we pass to
   * the OptionTree Meta Box API Class.
   */
  $meta_box_layout = array(
    'id'        => 'pp_metabox_sidebar',
    'title'     => __('Layout','chow'),
    'desc'      => __('You can choose a sidebar from the list below. Sidebars can be created in the Theme Options and configured in the Appearance -> Widgets.','chow'),
    'pages'     => array( 'post','recipe' ),
    'context'   => 'normal',
    'priority'  => 'high',
    'fields'    =>   array(
      array(
        'id'          => 'pp_sidebar_layout',
        'label'       => __('Layout','chow'),
        'desc'        => '',
        'std'         =>  $post_layout,
        'type'        => 'radio_image',
        'class'       => '',
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
          ),
        ),
      array(
        'id'          => 'pp_sidebar_set',
        'label'       => __('Sidebar','chow'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'sidebar-select',
        'class'       => '',
        )
      )
    );

$meta_box_layout_page = array(
  'id'        => 'pp_metabox_sidebar',
  'title'     => __('Layout','chow'),
  'desc'      => __('You can choose a sidebar from the list below. Sidebars can be created in the Theme Options and configured in the Appearance -> Widgets.','chow'),
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_sidebar_layout',
      'label'       => __('Layout','chow'),
      'desc'        => '',
      'std'         => 'full-width',
      'type'        => 'radio_image',
      'class'       => '',
      'choices'     => array(
        array(
          'value'   => 'left-sidebar',
          'label'   => __('Left Sidebar','chow'),
          'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
          ),
        array(
          'value'   => 'right-sidebar',
          'label'   => __('Right Sidebar','chow'),
          'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
          ),
        array(
          'value'   => 'full-width',
          'label'   => __('Full Width (no sidebar)','chow'),
          'src'     => OT_URL . '/assets/images/layout/full-width.png'
          )
        ),
      ),
    array(
      'id'          => 'pp_sidebar_set',
      'label'       => 'Sidebar',
      'desc'        => '',
      'std'         => '',
      'type'        => 'sidebar-select',
      'class'       => '',
      )
    )
);


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


$revs = array();
global $wpdb;
// Table name
$table_name = $wpdb->prefix . "revslider_sliders";
// Get sliders
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
  $sliders = $wpdb->get_results( "SELECT alias, title FROM $table_name" );
} else {
  $sliders = '';
}

// Iterate over the sliders
if($sliders) {
  foreach($sliders as $key => $item) {
    $revs[] = array(
      'label' => $item->title,
      'value' => $item->alias
      );
  }
} else {
  $revs[] = array(
    'label' => 'No Sliders Found',
    'value' => ''
    );
}


$slider = array(
  'id'        => 'pp_metabox_cpslider',
  'title'     => 'Slider settings',
  'desc'      => 'If you want to use  Slider on this page, select page template "Slider Page" and choose here slider you want to display.',
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_page_slider',
      'label'       => 'Chow Slider',
      'desc'        => 'Set Page Template to "Page Template with Chow Slider" to use it',
      'std'         => '',
      'type'        => 'select',
      'choices'     => $cpsliders,
      'class'       => '',
      ),
    array(
      'id'          => 'pp_rev_slider',
      'label'       => 'RevolutionSlider',
      'desc'        => 'Set Page Template to "Page Template with Revolution Slider" to use it',
      'std'         => '',
      'type'        => 'select',
      'choices'     => $revs,
      'class'       => '',
      )
    )
  );

$bg = array(
  'id'        => 'pp_metabox_post_options',
  'title'     => 'Background image for post header',
  'desc'      => '',
  'pages'     => array( 'post','recipe' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_header_bg',
      'label'       => 'Header background ',
      'desc'        => 'Set image for header, it should 1920px wide.',
      'std'         => '',
      'type'        => 'upload',
      'class'       => 'ot-upload-attachment-id',
      )
    )
);

$sliderimage = array(
  'id'        => 'pp_metabox_post_slider',
  'title'     => 'Select Image for slider (if empty, Featured image will be used)',
  'desc'      => '',
  'pages'     => array( 'post','recipe' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_post_slider_img',
      'label'       => 'Slider image',
      'desc'        => 'For best visual effect it should  be 1920px x 590px.',
      'std'         => '',
      'type'        => 'upload',
      'class'       => 'ot-upload-attachment-id',
      )
    )
);


$productsoptions = array(
  'id'        => 'pp_metabox_products',
  'title'     => 'Product page Options',
  'pages'     => array( 'product' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(

        array(
        'id'          => 'pp_sidebar_layout',
        'label'       => 'Layout',
        'desc'        => '',
        'std'         => 'full-width',
        'type'        => 'radio_image',
        'class'       => '',
        'choices'     => array(
          array(
            'value'   => 'full-width',
            'label'   => 'Full Width (no sidebar)',
            'src'     => OT_URL . '/assets/images/layout/full-width.png'
            ),
          array(
            'value'   => 'left-sidebar',
            'label'   => 'Left Sidebar',
            'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
            ),
          array(
            'value'   => 'right-sidebar',
            'label'   => 'Right Sidebar',
            'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
            )
          ),
        ),
    )
  );

  /**
   * Register our meta boxes using the
   * ot_register_meta_box() function.
   */
  ot_register_meta_box( $productsoptions );
  ot_register_meta_box( $meta_box_layout );
  ot_register_meta_box( $meta_box_layout_page );
  ot_register_meta_box( $bg );
  ot_register_meta_box( $sliderimage );
  ot_register_meta_box( $slider );


} ?>