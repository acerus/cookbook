<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   FoodiePress
 * @author    purethemes
 * @license   ThemeForest
 * @copyright 2014 Purethemes.net
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// @TODO: Define uninstall functionality here