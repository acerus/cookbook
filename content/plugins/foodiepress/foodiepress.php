<?php
/**
 * FoodiePress - Recipe plugin
 *
 *
 * @package   FoodiePress
 * @author    purethemes
 * @license   ThemeForest
 * @copyright 2014 Purethemes.net
 *
 * @wordpress-plugin
 * Plugin Name:       FoodiePress - Recipe plugin
 * Plugin URI:        @TODO
 * Description:       Plugin for adding recipe with advanced functions
 * Version:           1.3.5
 * Author:            purethemes.net
 * Author URI:        http://purethemes.net
 * Text Domain:       foodiepress
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
  */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/


if (! class_exists('Dwoo_Core')) {
  include plugin_dir_path( __FILE__ ) . 'includes/Dwoo/dwooAutoload.php';
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-foodiepress.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-favs.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 */
register_activation_hook( __FILE__, array( 'FoodiePress', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'FoodiePress', 'deactivate' ) );

/*
 * @TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 */
add_action( 'plugins_loaded', array( 'FoodiePress', 'get_instance' ) );
   
/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-plugin-admin.php` with the name of the plugin's admin file
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
  require_once( plugin_dir_path( __FILE__ ) . 'admin/class-foodiepress-admin.php' );
  add_action( 'plugins_loaded', array( 'FoodiePress_Admin', 'get_instance' ) );
}


$api_url = 'http://purethemes.wpengine.com/pluginupdates/';
$plugin_slug = 'foodiepress';


// Take over the update check
add_filter('pre_set_site_transient_update_plugins', 'check_for_foodiepress_update');

function check_for_foodiepress_update($checked_data) {
  global $api_url, $plugin_slug, $wp_version;

  //Comment out these two lines during testing.
  if (empty($checked_data->checked))
    return $checked_data;

  $args = array(
    'slug' => $plugin_slug,
    'version' => $checked_data->checked[$plugin_slug .'/'. $plugin_slug .'.php'],
  );
  $request_string = array(
      'body' => array(
        'action' => 'basic_check',
        'request' => serialize($args),
        'api-key' => md5(get_bloginfo('url'))
      ),
      'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
    );

  // Start checking for an update
  $raw_response = wp_remote_post($api_url, $request_string);

  if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
    $response = unserialize($raw_response['body']);

  if (is_object($response) && !empty($response)) // Feed the update data into WP updater
    $checked_data->response[$plugin_slug .'/'. $plugin_slug .'.php'] = $response;

  return $checked_data;
}


// Take over the Plugin info screen
add_filter('plugins_api', 'foodiepress_api_call', 10, 3);

function foodiepress_api_call($def, $action, $args) {
  global $plugin_slug, $api_url, $wp_version;

  if (!isset($args->slug) || ($args->slug != $plugin_slug))
    return false;

  // Get the current version
  $plugin_info = get_site_transient('update_plugins');
  $current_version = $plugin_info->checked[$plugin_slug .'/'. $plugin_slug .'.php'];
  $args->version = $current_version;

  $request_string = array(
      'body' => array(
        'action' => $action,
        'request' => serialize($args),
        'api-key' => md5(get_bloginfo('url'))
      ),
      'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
    );

  $request = wp_remote_post($api_url, $request_string);

  if (is_wp_error($request)) {
    $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
  } else {
    $res = unserialize($request['body']);

    if ($res === false)
      $res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
  }

  return $res;
}


