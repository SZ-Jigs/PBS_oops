<?php
/**
 * Class for the API that handles requests to the /listplugins endpoint.
 *
 * @author      Shobha Patel
 * @package     KWA/Api
 * @category    Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KWA_API_Plugin_Actions' ) ) {

	/**
	 * Listplugins API endpoint.
	 */
	class KWA_API_Plugin_Actions {

		/**
		 * Construct
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ), 9999, 1 );
		}

		/**
		 * Register routes.
		 *
		 * POST /activate
		 * POST /deactivate
		 *
		 * @param array $routes Routes.
		 * @return array
		 */
		public function register_routes() {

			register_rest_route(
				'sections/v2/listplugins',
				'/activate',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'kwa_activate' ),
					'permission_callback' => '__return_true'
				)
			);

			register_rest_route(
				'sections/v2/listplugins',
				'/deactivate',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'kwa_deactivate' ),
					'permission_callback' => '__return_true'
				)
			);

		}

		/**
		 * Get Counter.
		 *
		 * @param array $param Object.
		 * @return array|WP_Error
		 * @throws WP_Error If error encountered.
		 */
		public function kwa_activate( WP_REST_Request $param ) {
			try {
				if ( ! empty( $param ) ) {
					$filter = $param;
				} else {
					$filter = $_REQUEST;
				}

				if ( self::kwa_check_if_exists( 'plugin', $filter ) ) {
					if ( '' == $filter['plugin'] ) {
						return new WP_Error( 'dgh_api_invalid_parameter', __( 'Plugin name is required', KWA_DOMAIN ), 400 );
					}
				}

				$plugin = isset( $filter['plugin'] ) ? $filter['plugin'] : '';

				// Activate the plugin
				activate_plugin( $plugin );

				// check if activation was successful
				if ( is_plugin_active( $plugin ) ) {
					wp_send_json_success( array( 'message' => 'Plugin activated successfully.' ) );
				} else {
					wp_send_json_error( array( 'message' => 'Plugin activation failed.' ) );
				}
			} catch ( WP_Error $e ) {
				return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
			}
		}

		/**
		 * Set Counter.
		 *
		 * @param array $param Object.
		 * @return array|WP_Error
		 * @throws WP_Error If error encountered.
		 */
		public function kwa_deactivate( WP_REST_Request $param ) {
			try {
				if ( ! empty( $param ) ) {
					$filter = $param;
				} else {
					$filter = $_REQUEST;
				}

				if ( self::kwa_check_if_exists( 'plugin', $filter ) ) {
					if ( '' == $filter['plugin'] ) {
						return new WP_Error( 'dgh_api_invalid_parameter', __( 'Plugin name is required', KWA_DOMAIN ), 400 );
					}
				}

				$plugin = isset( $filter['plugin'] ) ? $filter['plugin'] : '';

				// Deactivate the plugin
				deactivate_plugins( $plugin );

				// check if activation was successful
				if ( ! is_plugin_active( $plugin ) ) {
					wp_send_json_success( array( 'message' => 'Plugin deactivated successfully.' ) );
				} else {
					wp_send_json_error( array( 'message' => 'Plugin deactivation failed.' ) );
				}
			} catch ( WP_Error $e ) {
				return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
			}
		}

		/**
		 * Checks if variable is available or if element exists in array.
		 *
		 * @param string $element Variable to check whether it exists.
		 * @param array  $data Array where Variable is checked if it exists.
		 * @return bool
		 */
		public function kwa_check_if_exists( $element, $data = '' ) {
			if ( '' !== $data && is_array( $data ) ) {
				$element = isset( $data[ $element ] );
			} else {
				$element = isset( $element );
			}
			return $element && '' !== $element;
		}

	}
	$kwa_api_plugin_actions = new KWA_API_Plugin_Actions();
}
