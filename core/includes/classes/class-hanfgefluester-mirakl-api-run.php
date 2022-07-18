<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Hanfgefluester_Mirakl_Api_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		HANFGEFLUE
 * @subpackage	Classes/Hanfgefluester_Mirakl_Api_Run
 * @author		external evelopment
 * @since		1.0.0
 */
class Hanfgefluester_Mirakl_Api_Run{

	/**
	 * Our Hanfgefluester_Mirakl_Api_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'add_wp_webhooks_integrations' ), 9 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles() {
		wp_enqueue_style( 'hanfgeflue-backend-styles', HANFGEFLUE_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), HANFGEFLUE_VERSION, 'all' );
		wp_enqueue_script( 'hanfgeflue-backend-scripts', HANFGEFLUE_PLUGIN_URL . 'core/includes/assets/js/backend-scripts.js', array(), HANFGEFLUE_VERSION, false );
		wp_localize_script( 'hanfgeflue-backend-scripts', 'hanfgeflue', array(
			'plugin_name'   	=> __( HANFGEFLUE_NAME, 'hanfgefluester-mirakl-api' ),
		));
	}

	/**
	 * ####################
	 * ### WP Webhooks 
	 * ####################
	 */

	/*
	 * Register dynamically all integrations
	 * The integrations are available within core/includes/integrations.
	 * A new folder is considered a new integration.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function add_wp_webhooks_integrations(){

		// Abort if WP Webhooks is not active
		if( ! function_exists('WPWHPRO') ){
			return;
		}

		$custom_integrations = array();
		$folder = HANFGEFLUE_PLUGIN_DIR . 'core' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'integrations';

		try {
			$custom_integrations = WPWHPRO()->helpers->get_folders( $folder );
		} catch ( Exception $e ) {
			WPWHPRO()->helpers->log_issue( $e->getTraceAsString() );
		}

		if( ! empty( $custom_integrations ) ){
			foreach( $custom_integrations as $integration ){
				$file_path = $folder . DIRECTORY_SEPARATOR . $integration . DIRECTORY_SEPARATOR . $integration . '.php';
				WPWHPRO()->integrations->register_integration( array(
					'slug' => $integration,
					'path' => $file_path,
				) );
			}
		}
	}

}
