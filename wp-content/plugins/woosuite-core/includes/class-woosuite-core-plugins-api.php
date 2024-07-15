<?php
/**
 * Plugins api override.
 *
 * @package Woosuite_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Override WordPress plugins_api call to allow it access our plugin information.
 *
 * @class Woosuite_Core_Plugins_Api
 */

class Woosuite_Core_Plugins_Api {
	/**
	 * Singleton The reference the *Singleton* instance of this class.
	 *
	 * @var Woosuite_Core_Plugins_Api
	 */
	protected static $instance = null;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Woosuite_Core_Plugins_Api The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	private function __construct() {
		add_filter( 'http_request_host_is_external', '__return_true' );

		add_filter( 'plugins_api', array( $this, 'plugins_api' ), 100, 3 );

		add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 20, 2 );
		
		// After active_plugins option is updated.
		add_action( 'update_option_active_plugins', array( $this, 'active_plugins_updated' ), 20, 2 );


		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_update_plugins' ), 50 );
		
		add_filter( 'wbw_check_module_active', array( $this, 'check_module_ajax' ) );

		# Woosuite_Core_Utils::d( $this->our_plugin_slugs() );
	}
	
	// 	check module is installable or not
	function check_module_ajax($slug){
		$resp = true;
		//get group match status 
		$lic_key = woosuite_core_get_license_key();
		$uri = woosuite_core_get_api_url( "/groupcheck/{$lic_key}/{$slug}/");
		$r = wp_remote_get( $uri );
		$rbody = json_decode( wp_remote_retrieve_body( $r ), true );
		//$group_license_matched = 0;
		$group_license_matched = isset($rbody['status']) ? $rbody['status'] : false;
		
		if(!$group_license_matched){
			$resp = false;
		}
		if (!$resp) {
			$saved_array = get_option( '_optimizeform_core_new_license', '' );
			$new_licenses = !empty( $saved_array ) ? $saved_array : array();
			if (!empty($new_licenses)) {
				foreach ($new_licenses as $license_key => $license) {
					
					$uri = woosuite_core_get_api_url( "/groupcheck/{$license_key}/{$slug}/");
					$r = wp_remote_get( $uri );
					$rbody = json_decode( wp_remote_retrieve_body( $r ), true );
					//$group_license_matched = 0;
					$group_license_matched = isset($rbody['status']) ? $rbody['status'] : false;

					if($group_license_matched){
						
						return true;
					}
				}
			}
		}
		return $resp;
		
	}
	
	public function pre_update_plugins( $transient ) {
		/*
		woosuite_core_log( 'pre_update_plugins', array(
			'transient' => $transient
		) );
		*/

		if ( ! woosuite_core_get_license_key() ) {
			return $transient;
		}

		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$installed_plugins = get_plugins( '/' );
		if ( empty( $installed_plugins ) ) {
			return $transient;
		}

		$our_plugins = array_filter( $installed_plugins, function($item) {
			if ( $item['Author'] == 'Woosuite' && strpos( $item['AuthorURI'], 'https://aovup.com') !== FALSE ) {
				return $item;
			}
		} );

		if ( empty( $our_plugins ) ) {
			return $transient;
		}

		$checked = array();
		foreach ( $our_plugins as $basename => $plugin ) {
			$checked[ $basename ] = $plugin['Version'];
		}

		$woosuite_plugins = get_transient( 'woosuite_core_plugins' );

		if ( is_admin() && isset( $_REQUEST['force-check'] ) ) {
			$woosuite_plugins = false;
		}


		if ( false === $woosuite_plugins ) {
			$woosuite_plugins = woosuite_core_get_modules();
			if ( is_wp_error( $woosuite_plugins ) ) {
				return $transient;
			}

			set_transient( 'woosuite_core_plugins', $woosuite_plugins, HOUR_IN_SECONDS );
		}

		# Woosuite_Core_Utils::d( $woosuite_plugins );

		if ( empty( $woosuite_plugins ) ) {
			return $transient;
		}

		$plugins = array();
		foreach ( $woosuite_plugins as $plugin ) {
			$plugins[ $plugin->slug ] = (object) array(
				'id'            => str_replace( 'https://', '', $plugin->homepage ),
                'slug'          => $plugin->slug,
                'plugin'        => '',
                'new_version'   => $plugin->version,
                'url'           => $plugin->homepage,
                'package'       => woosuite_core_get_plugin_download_link( $plugin->slug ),
                'icons'         => is_object( $plugin->icons ) ? get_object_vars( $plugin->icons ) : array(),
                'banners'       => array(),
                'banners_rtl'   => array(),
                'tested'        => $plugin->tested,
                'requires_php'  => isset( $plugin->requires_php ) ? $plugin->requires_php : '5.3.6',
                'compatibility' => new stdClass,
			);
		}

		woosuite_core_log( 'plugins pre_update_plugins', array(
			'plugins' => $plugins,
			'checked' => $checked
		) );

		foreach ( $checked as $basename => $version ) {
			$plugin_slug = dirname( $basename );

			if ( ! empty( $plugin_slug ) && isset( $plugins[ $plugin_slug ] ) ) {

				$plugin = $plugins[ $plugin_slug ];
				$plugin->plugin = $basename;

				unset(
					$transient->checked[ $basename ],
					$transient->response[ $basename ],
					$transient->no_update[ $basename ]
				);

				if ( version_compare( $plugin->new_version, $version, '>' ) ) {
					if ( ! isset( $transient->response ) ) {
						$transient->response = array();
					}

					$transient->response[ $basename ] = $plugin;

				} else {
					if ( ! isset( $transient->no_update ) ) {
						$transient->no_update = array();
					}

					$transient->no_update[ $basename ] = $plugin;
				}
			}
		}

		/*
		woosuite_core_log( 'end pre_update_plugins', array(
			'transient' => $transient
		) );
		*/

		return $transient;
	}

	/**
	 * Update plugin install data after any of woosuite plugin gets installed/updated.
	 * @param  [type] $upgrader   [description]
	 * @param  [type] $hook_extra [description]
	 * @return [type]             [description]
	 */
	public function upgrader_process_complete( $upgrader, $hook_extra ) {
		woosuite_core_log( 'upgrader_process_complete', array(
			'hook_extra' => $hook_extra,
			'upgrader' => $upgrader
		) );

		$plugin_slug = '';
		if ( isset( $hook_extra ) && isset( $hook_extra['type'] ) && isset( $hook_extra['plugin'] ) ) {
			$plugin_slug = $hook_extra['plugin'];
		} elseif ( isset( $upgrader->result ) && ! empty( $upgrader->result['destination_name'] ) ) {
			$plugin_slug = $upgrader->result['destination_name'];
		}

		if ( ! empty( $plugin_slug ) && in_array( $plugin_slug, $this->our_plugin_slugs() ) ) {
			woosuite_core_maybe_send_plugins_data();

			// include_once ABSPATH . 'wp-admin/includes/plugin.php';
			// $plugin = get_plugin_data( WP_PLUGIN_DIR . '/' . $product_file );
			// $this->clear_updates_transient();
			// $this->product_version = $plugin['Version'];
			// $this->api_request( 'ping' );
		}
	}

	public function active_plugins_updated( $old_value, $value ) {
		woosuite_core_maybe_send_plugins_data();
	}

	/**
	 * Override plugins_api call.
	 *
	 * @param  mixed  $res     Response.
	 * @param  string $action  Request action.
	 * @param  array  $args    Arguments.
	 * @return mixed           Instance of WP_Error or array of response.
	 */
	public function plugins_api( $res, $action, $args ) {
		if ( 'plugin_information' === $action && in_array( $args->slug, $this->our_plugin_slugs() ) ) {
			if ( ! woosuite_core_get_license_key() ) {
				return new WP_Error( 'license_key_required', __( 'Please activate your Woosuite Core plugin license to install module.' ) );
			}

			if(!woosuite_core_check_plugin_belong_to_license($args->slug)){
				return new WP_Error( 'plugin_group_match_failed', 'Plugin does not belong to this license/group.' );
			}
			
			$url = woosuite_core_get_api_url( "/plugins/{$args->slug}/?wp_url=" . esc_url( site_url() ) );
			$request = wp_remote_get( $url );

			
			if ( is_wp_error( $request ) ) {
				$res = new WP_Error(
					'plugins_api_failed',
					sprintf( __( 'Error: %s.' ), $request->get_error_message() )
				);
			} else {
				$res = json_decode( wp_remote_retrieve_body( $request ), true );
				if ( ! empty( $res['data'] ) && ! empty( $res['data']['status'] ) && 200 !== $res['data']['status'] ) {
					return new WP_Error( $res['code'], $res['data']['message'] );

				} elseif ( isset( $res['slug'] ) ) {
					// Object casting is required in order to match the info/1.0 format.
					unset( $res['id'] );
					$res['download_link'] = woosuite_core_get_plugin_download_link( $args->slug );
					$res = (object) $res;
					update_option( 'woosuite_core_addon_installed_once', true );

				} elseif ( empty( $res ) ) {
					$res = new WP_Error(
						'plugins_api_failed',
						'Ops'
					);
				} else {
					$res = new WP_Error(
						'plugins_api_failed',
						sprintf(
							/* translators: %s: Support forums URL. */
							__( 'An unexpected error occurred. Something may be wrong with Woosuite Server. If you continue to have problems, please try the <a href="%s">support forums</a>.' ),
							__( 'https://aovup.com/support/?utm_source=user-dashboard&utm_medium=error' )
						)
					);
				}
			}
		}

		# Woosuite_Core_Utils::d( $res );

		return $res;
	}

	/**
	 * All of our plugins slug / folder name.
	 *
	 * @return array Our plugins.
	 */
	public function our_plugin_slugs() {
		$plugin_slugs = array();
		$woosuite_plugins = get_transient( 'woosuite_core_plugins' );

		if ( false === $woosuite_plugins ) {
			$woosuite_plugins = woosuite_core_get_modules();
			if ( ! is_wp_error( $woosuite_plugins ) ) {
				set_transient( 'woosuite_core_plugins', $woosuite_plugins, HOUR_IN_SECONDS );
			}
		}

		if ( ! empty( $woosuite_plugins ) ) {
			$plugin_slugs = wp_list_pluck( $woosuite_plugins, 'slug' );
		}

		if ( empty( $plugin_slugs ) ) {
			$plugin_slugs = apply_filters( 'woosuite_core_our_plugin_slugs', array(
				'woosuite-core',
				'woosuite-product-table',
				'woosuite-pricing-discount-rules',
				'woosuite-private-woocommerce-store',
				'woosuite-user-registration-for-woocommerce',
			));
		}

		return $plugin_slugs;
	}
}
