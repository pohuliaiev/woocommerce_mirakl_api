<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Hanfgefluester_Mirakl_Api_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		HANFGEFLUE
 * @subpackage	Classes/Hanfgefluester_Mirakl_Api_Settings
 * @author		external evelopment
 * @since		1.0.0
 */
class Hanfgefluester_Mirakl_Api_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	private $plugin_name;

	/**
	 * Our Hanfgefluester_Mirakl_Api_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		$this->plugin_name = HANFGEFLUE_NAME;
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'HANFGEFLUE/settings/get_plugin_name', $this->plugin_name );
	}
}
