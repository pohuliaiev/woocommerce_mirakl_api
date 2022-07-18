<?php
/**
 * Hanfgefluester Mirakl API 
 *
 * @package       HANFGEFLUE
 * @author        external evelopment
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Hanfgefluester Mirakl API 
 * Plugin URI:    http://hanfgefluester.de/
 * Description:   Hanfgefluester Mirakl API 
 * Version:       1.0.0
 * Author:        external evelopment
 * Author URI:    https://your-author-domain.com
 * Text Domain:   hanfgefluester-mirakl-api
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'HANFGEFLUE_NAME',			'Hanfgefluester Mirakl API ' );

// Plugin version
define( 'HANFGEFLUE_VERSION',		'1.0.0' );

// Plugin Root File
define( 'HANFGEFLUE_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'HANFGEFLUE_PLUGIN_BASE',	plugin_basename( HANFGEFLUE_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'HANFGEFLUE_PLUGIN_DIR',	plugin_dir_path( HANFGEFLUE_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'HANFGEFLUE_PLUGIN_URL',	plugin_dir_url( HANFGEFLUE_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once HANFGEFLUE_PLUGIN_DIR . 'core/class-hanfgefluester-mirakl-api.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  external evelopment
 * @since   1.0.0
 * @return  object|Hanfgefluester_Mirakl_Api
 */
function HANFGEFLUE() {
	return Hanfgefluester_Mirakl_Api::instance();
}

HANFGEFLUE();
