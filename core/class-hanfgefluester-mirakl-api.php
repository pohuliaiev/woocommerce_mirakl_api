<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Hanfgefluester_Mirakl_Api' ) ) :

	/**
	 * Main Hanfgefluester_Mirakl_Api Class.
	 *
	 * @package		HANFGEFLUE
	 * @subpackage	Classes/Hanfgefluester_Mirakl_Api
	 * @since		1.0.0
	 * @author		external evelopment
	 */
	final class Hanfgefluester_Mirakl_Api {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Hanfgefluester_Mirakl_Api
		 */
		private static $instance;

		/**
		 * HANFGEFLUE helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Hanfgefluester_Mirakl_Api_Helpers
		 */
		public $helpers;

		/**
		 * HANFGEFLUE settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Hanfgefluester_Mirakl_Api_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'hanfgefluester-mirakl-api' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'hanfgefluester-mirakl-api' ), '1.0.0' );
		}

		/**
		 * Main Hanfgefluester_Mirakl_Api Instance.
		 *
		 * Insures that only one instance of Hanfgefluester_Mirakl_Api exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Hanfgefluester_Mirakl_Api	The one true Hanfgefluester_Mirakl_Api
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hanfgefluester_Mirakl_Api ) ) {
				self::$instance					= new Hanfgefluester_Mirakl_Api;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Hanfgefluester_Mirakl_Api_Helpers();
				self::$instance->settings		= new Hanfgefluester_Mirakl_Api_Settings();

				//Fire the plugin logic
				new Hanfgefluester_Mirakl_Api_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'HANFGEFLUE/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once HANFGEFLUE_PLUGIN_DIR . 'core/includes/classes/class-hanfgefluester-mirakl-api-helpers.php';
			require_once HANFGEFLUE_PLUGIN_DIR . 'core/includes/classes/class-hanfgefluester-mirakl-api-settings.php';

			require_once HANFGEFLUE_PLUGIN_DIR . 'core/includes/classes/class-hanfgefluester-mirakl-api-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'hanfgefluester-mirakl-api', FALSE, dirname( plugin_basename( HANFGEFLUE_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.