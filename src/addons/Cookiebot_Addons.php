<?php

namespace cybot\cookiebot\addons;

use cybot\cookiebot\addons\config\Settings_Config;
use cybot\cookiebot\addons\controller\Plugin_Controller;
use cybot\cookiebot\addons\lib\buffer\Buffer_Output;
use cybot\cookiebot\addons\lib\Cookie_Consent;
use cybot\cookiebot\addons\lib\Dependency_Container;
use cybot\cookiebot\addons\lib\script_loader_tag\Script_Loader_Tag;
use cybot\cookiebot\addons\lib\Settings_Service;
use cybot\cookiebot\addons\lib\Theme_Settings_Service;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * __DIR__ of the cookiebot_addons folder
 */
define( 'COOKIEBOT_ADDONS_DIR', __DIR__ . DIRECTORY_SEPARATOR );

/**
 * Load helper functions
 */
require_once COOKIEBOT_ADDONS_DIR . 'lib/helper.php';

/**
 * Class Cookiebot_Addons
 * @package cookiebot_addons
 */
class Cookiebot_Addons {

	/**
	 * Dependency Container - is used for dependency injections
	 *
	 * @var Dependency_Container
	 *
	 * @since 1.3.0
	 */
	public $container;

	/**
	 * List of all supported plugin addons
	 *
	 * @var array
	 *
	 * @since 1.3.0
	 */
	private $plugin_addons_list = array();

	/**
	 * @var   Cookiebot_Addons The single instance of the class
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Main Cookiebot_WP Instance
	 *
	 * Ensures only one instance of Cookiebot_Addons is loaded or can be loaded.
	 *
	 * @return Cookiebot_Addons
	 * @since   2.2.0
	 * @static
	 *
	 * @version 2.2.0
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			try {
				self::$instance = new self();
			} catch ( Exception $e ) {
				echo 'Could not initialize Cookiebot addons: ' . esc_html( $e->getMessage() );
			}
		}

		return self::$instance;
	}

	/**
	 * Cookiebot_Addons constructor.
	 *
	 * @throws Exception
	 *
	 * @since 1.3.0
	 */
	public function __construct() {
		$this->load_addons();
		$this->build_container();
		$this->assign_addons_to_container();

		/**
		 * Load plugin controller to check if addons are active
		 * If active then load the plugin addon configuration class
		 * Else skip it
		 *
		 * @since 1.1.0
		 */
		add_action(
			'after_setup_theme',
			array(
				new Plugin_Controller( $this->container->get( 'Settings_Service_Interface' ) ),
				'load_active_addons',
			)
		);
		/**
		 * Load settings config
		 *
		 * @since 1.1.0
		 */
		$settings = new Settings_Config( $this->container->get( 'Settings_Service_Interface' ) );
		$settings->load();
	}

	/**
	 * if the cookiebot is activated
	 * run this script to start up
	 *
	 * @throws Exception
	 * @since 2.2.0
	 */
	public function cookiebot_activated() {
		$settings_service = $this->container->get( 'Settings_Service_Interface' );
		$settings_service->cookiebot_activated();
	}

	/**
	 * if the cookiebot is deactivated
	 * run this script to clean up addons.
	 *
	 * @throws Exception
	 * @since 2.2.0
	 */
	public function cookiebot_deactivated() {
		$settings_service = $this->container->get( 'Settings_Service_Interface' );
		$settings_service->cookiebot_deactivated();
	}

	protected function load_addons() {
		require_once 'addons.php';
		$this->plugin_addons_list = apply_filters( 'cookiebot_plugin_addons_list', PLUGIN_ADDONS );
	}

	/**
	 * @throws Exception
	 */
	protected function build_container() {
		$dependencies = array(
			'Script_Loader_Tag_Interface' => new Script_Loader_Tag(),
			'Cookie_Consent_Interface'    => new Cookie_Consent(),
			'Buffer_Output_Interface'     => new Buffer_Output(),
			'plugin_addons_list'          => $this->plugin_addons_list,
		);

		$this->container = new Dependency_Container( $dependencies );

		$this->container->set(
			'Settings_Service_Interface',
			new Settings_Service( $this->container )
		);

		$this->container->set(
			'Theme_Settings_Service_Interface',
			new Theme_Settings_Service( $this->container )
		);
	}

	/**
	 * Assign addon class to the container to use it later
	 *
	 * @throws Exception
	 *
	 * @since 1.3.0
	 */
	protected function assign_addons_to_container() {
		/**
		 * Check plugins one by one and load addon configuration
		 */
		foreach ( $this->plugin_addons_list as $plugin_addon ) {
			/**
			 * Load addon class to the container
			 */
			if ( class_exists( $plugin_addon ) ) {
				$this->container->set(
					$plugin_addon,
					new $plugin_addon(
						$this->container->get( 'Settings_Service_Interface' ),
						$this->container->get( 'Script_Loader_Tag_Interface' ),
						$this->container->get( 'Cookie_Consent_Interface' ),
						$this->container->get( 'Buffer_Output_Interface' )
					)
				);
			} else {
				throw new Exception( 'Class ' . $plugin_addon . ' not found' );
			}
		}
	}
}

/**
 * Initiate the cookiebot addons framework plugin
 */
Cookiebot_Addons::instance();
