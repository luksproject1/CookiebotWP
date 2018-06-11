<?php

namespace cookiebot_addons_framework\controller\addons\facebook_for_woocommerce;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\Settings_Service_Interface;

class Facebook_For_Woocommerce implements Cookiebot_Addons_Interface {
	/**
	 * @var Settings_Service_Interface
	 *
	 * @since 1.3.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.3.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.3.0
	 */
	protected $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

	/**
	 * Jetpack constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct( Settings_Service_Interface $settings, Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent, Buffer_Output_Interface $buffer_output ) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_configuration() {
		/**
		 * We add the action after wp_loaded and replace the original GA Google
		 * Analytics action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_facebook_for_woocommerce_tracking_code' ), 5 );
	}

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_facebook_for_woocommerce_tracking_code() {
		//Check Facebook for Wooocommerce is active
		if ( ! class_exists( 'WC_Facebookcommerce' ) ) {
			return;
		}

		/** Check if consent is given  */
		if( $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ) {
			return;
		}

		/** @var  $consent_given boolean    consent is not given */
		$consent_given = false;

		$this->buffer_output->add_tag( 'wp_head', 10, array(
			'fbq(\'track\',' => $this->get_cookie_types()
		), $consent_given );


		$this->buffer_output->add_tag( 'woocommerce_after_single_product', 2, array(
			'fbq(\'ViewContent\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_after_shop_loop', 10, array(
			'fbq(\'ViewCategory\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'pre_get_posts', 10, array(
			'fbq(\'Search\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_after_cart', 10, array(
			'fbq(\'AddToCart\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_add_to_cart', 2, array(
			'fbq(\'AddToCart\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'wc_ajax_fb_inject_add_to_cart_event', 2, array(
			'fbq(\'AddToCart\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_after_checkout_form', 10, array(
			'fbq(\'InitiateCheckout\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_thankyou', 2, array(
			'fbq(\'Purchase\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_payment_complete', 2, array(
			'fbq(\'Purchase\'' => $this->get_cookie_types()
		), false );

		/**
		 * inject base pixel
		 */
		cookiebot_remove_class_action( 'wp_footer', 'WC_Facebookcommerce_EventsTracker', 'inject_base_pixel_noscript' );
		cookiebot_remove_class_action( 'wp_head', 'WC_Facebookcommerce_EventsTracker', 'inject_base_pixel' );
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Facebook For WooCommerce';
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_option_name() {
		return 'facebook_for_woocommerce';
	}

	/**
	 * Plugin file name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_plugin_file() {
		return 'facebook-for-woocommerce/facebook-for-woocommerce.php';
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( $this->get_option_name() );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_option_name() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_plugin_file() );
	}

	/**
	 * Checks if addon is activated
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated() {
		return $this->settings->is_addon_activated( $this->get_plugin_file() );
	}

	public function has_placeholder() {
		return $this->settings->has_placeholder( $this->get_option_name() );
	}

	public function get_placeholders() {
		return $this->settings->get_placeholders( $this->get_option_name() );
	}

	public function is_placeholder_enabled() {
		return $this->settings->is_placeholder_enabled( $this->get_option_name() );
	}
}
