<?php
/**
 * Plugin Name: KD WooCommerce Addon
 * Plugin URI: #
 * Description: This plugin lets you capture the cart coupon records.
 * Version: 1.0
 * Author: Shobha Patel
 * Author URI: #
 * Text Domain: kd-woocommerce-addon
 *
 * @package KWA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KD_WooCommerce' ) ) {

	/**
	 * KD Core Class
	 *
	 * @class kd_wooCommerce
	 */
	class KD_WooCommerce {

		/**
		 * Default constructor
		 */
		public function __construct() {

			/**
			 * Defining Constants.
			 */
			$this->kwa_define_constants();

			if ( ! self::kwa_is_required_plugin_active() ) {
				return;
			}

			/**
			 * Including Plugin Files
			 */
			self::kwa_maybe_include_files();
		}

		/**
		 * Including plugin files.
		 */
		public static function kwa_include_files() {
			// Task 2: Woocommerce customization
			require_once KWA_INCLUDE_PATH . 'kwa-functions.php';

			// Task 1: API includes.
			include_once KWA_API_PATH . 'kwa-api-plugin-actions.php';
		}

		/**
		 * Define constants to be used accross the plugin.
		 */
		public static function kwa_define_constants() {

			/**
			 * The name of your product. This is the title of your product in EDD and should match the download title in EDD exactly
			 * IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
			 */
			define( 'KWA_PLUGIN_NAME', 'KD WooCommerce Addon' );
			define( 'KWA_DOMAIN', 'kd-woocommerce-addon' );

			define( 'KWA_VERSION', '1.0' );

			if ( ! defined( 'KWA_FILE' ) ) {
				define( 'KWA_FILE', __FILE__ );
			}

			if ( ! defined( 'KWA_PLUGIN_PATH' ) ) {
				define( 'KWA_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
			}

			if ( ! defined( 'KWA_PLUGIN_URL' ) ) {
				define( 'KWA_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
			}

			define( 'KWA_API_PATH', KWA_PLUGIN_PATH . '/includes/api/' );
			define( 'KWA_INCLUDE_PATH', KWA_PLUGIN_PATH . '/includes/' );

		}

		/**
		 * Checks if WooCommerce is installed and active.
		 */
		public static function kwa_is_required_plugin_active() {

			$woocommerce_path = 'woocommerce/woocommerce.php';
			$active_plugins   = (array) get_option( 'active_plugins', array() );

			$active = false;
			if ( is_multisite() ) {
				$plugins = get_site_option( 'active_sitewide_plugins' );
				if ( isset( $plugins[ $woocommerce_path ] ) ) {
					$active = true;
				}
			}

			return in_array( $woocommerce_path, $active_plugins ) || array_key_exists( $woocommerce_path, $active_plugins ) || $active;
		}

		/**
		 * Checks whether to inlcude KWA core files.
		 */
		public static function kwa_maybe_include_files() {

			if ( self::kwa_is_required_plugin_active() ) {
				self::kwa_include_files();
			}
		}

	}

	$kd_wooCommerce = new KD_WooCommerce();
}