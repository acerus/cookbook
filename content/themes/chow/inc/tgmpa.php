<?php
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'chow_register_required_plugins' );

function chow_register_required_plugins() {

/**
 * Array of plugin arrays. Required keys are name and slug.
 * If the source is NOT from the .org repo, then source is also required.
 */
$plugins = array(

    array(
        'name'                  => 'FoodiePress',
        'slug'                  => 'foodiepress',
        'source'                => get_template_directory_uri() . '/plugins/foodiepress.zip',
        'version'               => '1.3.5',
        'required'              => true,
    ),    
    array(
        'name'                  => 'Purethemes.net Shortcodes',
        'slug'                  => 'purethemes-shortcodes',
        'source'                => get_template_directory_uri() . '/plugins/purethemes-shortcodes.zip',
        'version'               => '2.2',
        'required'              => true,
    ),
    array(
        'name'                  => 'Followers',
        'slug'                  => 'followers',
        'source'                => get_template_directory_uri() . '/plugins/followers.zip',
        'version'               => '1.3',
        'required'              => false,
    ),
    array(
        'name'                  => 'Contact Form 7', // The plugin name
        'slug'                  => 'contact-form-7', // The plugin slug (typically the folder name)
        'required'              => false, // If false, the plugin is only 'recommended' instead of required
    ),    
    array(
        'name'                  => 'Mailchimp for WP', // The plugin name
        'slug'                  => 'mailchimp-for-wp', // The plugin slug (typically the folder name)
        'required'              => false, // If false, the plugin is only 'recommended' instead of required
    ),

    array(
        'name'                  => 'WP-PageNavi', // The plugin name
        'slug'                  => 'wp-pagenavi', // The plugin slug (typically the folder name)
        'required'              => false, // If false, the plugin is only 'recommended' instead of required
    ),
    array(
            'name' => 'Envato Market',
            'slug' => 'envato-market',
            'source' => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
            'required' => true,
            'recommended' => true,
            'force_activation' => false,
    ),

);
 $config = array(
        'id'           => 'chow',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                    // Message to output right before the plugins table.

    );

    tgmpa( $plugins, $config );
}
