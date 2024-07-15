<?php
/**
 * Get plugins information api url
 *
 * @return string Url address of the API server.
 */
function woosuite_core_get_api_url( $path = '' ) {

	//demo server site 
	return apply_filters( 'woosuite_core_api_url', woosuite_core_get_api_site_url() . 'wp-json/woosuite-server/v1' ) . $path;
}

function woosuite_core_get_api_site_url() {
	return apply_filters( 'woosuite_core_get_api_site_url', 'https://dw.aovup.com/' );
}

function woosuite_core_get_news_site_url() {
	return apply_filters( 'woosuite_core_get_news_site_url', 'https://aovup.com/' );
}
function woosuite_core_get_plugin_download_link( $slug ) {
	return woosuite_core_get_api_url( "/download/" ) . woosuite_core_get_license_key() . "/{$slug}.zip?wp_url=" . site_url();
}

/**
 * Get plugin basepath using folder name
 *
 * @param  string $slug Plugin slug/folder name.
 * @return string       Plugin basepath
 */
function woosuite_core_get_plugin_file( $slug ) {
	$installed_plugin = get_plugins( '/' . $slug );
	if ( empty( $installed_plugin ) ) {
		return false;
	}

	$key = array_keys( $installed_plugin );
	$key = reset( $key );
	return $slug . '/' . $key;
}

/**
 * Check if plugin active based on folder name.
 *
 * @param  string $slug Plugin slug/folder name.
 * @return boolean      True/False based on active status.
 */
function woosuite_core_is_module_active( $slug ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	return is_plugin_active( woosuite_core_get_plugin_file( $slug ) );
}

/**
 * check if plugin is active by a given path
 *
 * @param string $path
 * @return boolean
 */
function woosuite_check_module_active_by_path( $path ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	return is_plugin_active( $path );
}

/**
 * Check if module is installed by a given path
 *
 * @param string $path
 * @return boolean
 */
function woosuite_check_module_installed_by_path( $path ) {
	return file_exists( WP_PLUGIN_DIR . '/' . $path );
}

/**
 * Check if plugin folder exists.
 *
 * @param  string $slug Plugin slug/folder name.
 */
function woosuite_core_is_module_installed( $slug ) {
	return is_dir( WP_PLUGIN_DIR . '/' . $slug );
}

/**
 * Get a modules settings page url if present, otherwise false.
 *
 * @param  string $slug Plugin slug/folder name.
 * @return mixed        False or the url.
 */
function woosuite_core_get_module_settings_url( $slug ) {
	return apply_filters( 'woosuite_core_module_settings_url', false, $slug );
}

/**
 * Get a module by folder name.
 *
 * @param  string $slug Plugin slug/folder name.
 * @return object       Module/Plugin data object.
 */
function woosuite_core_get_module( $slug ) {
	$modules = woosuite_core_get_modules();
	foreach ( $modules as $module ) {
		if ( $module->slug === $slug ) {
			return $module;
		}
	}

	return null;
}

/**
 * Get all available modules.
 *
 * @return array Array of available modules.
 */
function woosuite_core_get_modules( $excluded_core = false ) {
	$cached_modules = get_transient( 'woosuite_core_all_modules' );
	if ( empty( $cached_modules ) || ! is_array( $cached_modules ) ) {
		$request = wp_remote_request( woosuite_core_get_api_url( '/plugins/' ) );
		$response_data = array();
		if ( ! is_wp_error( $request ) ) {

			$body = json_decode( wp_remote_retrieve_body( $request ) );
			if ( empty( $body->data ) || empty( $body->data->status ) || 200 === $body->data->status ) {
				if ( $excluded_core ) {
					$response_data = wp_list_filter( $body, array( 'slug' => 'woosuite-core' ), 'NOT' );
				} else {
					$response_data = $body;
				}
			}
		}
		$response_data = is_array( $response_data ) ? $response_data : array();
		set_transient( 'woosuite_core_all_modules', $response_data, 3600 );

		return $response_data;
	}

	return $cached_modules;
}

function woosuite_core_activate_license( $license_key, $plugin_slug = 'woosuite-core' ) {

	$data = woosuite_core_api_license_data( $license_key, $plugin_slug );
	if ( is_wp_error( $data ) ) {
		return $data;
	}

	if ( $data->installs_allowed <= $data->installs_active ) {
		return new WP_Error( 'limit_reached', __( 'License usage limit reached. You can not activate this license.', 'woosuite-core' ) );
	}
	# Woosuite_Core_Utils::d( $data );

	update_option( '_woosuite_core_license_key', $license_key );
	update_option( '_woosuite_core_license_data', $data );
	update_option( 'woosuite_core_license_activated_once', true );
	woosuite_core_maybe_send_plugins_data( array(
		'activate_license' => 1
	) );
	delete_transient('woosuite_core_available_plugins');
	return $data;
}

function optimizeform_core_new_activate_license( $license_key, $plugin_slug = 'woosuite-core' ) {

	$data = woosuite_core_api_license_data( $license_key, $plugin_slug );

	if ( is_wp_error( $data ) ) {
		return $data;
	}

	if ( $data->installs_allowed <= $data->installs_active ) {
		return new WP_Error( 'limit_reached', __( 'License usage limit reached. You can not activate this license.', 'woosuite-core' ) );
	}
	$new_licenses = !empty( get_option( '_optimizeform_core_new_license', true ) ) ? get_option( '_optimizeform_core_new_license', false ) : array();

	$new_licenses[$license_key] = array('license_key'=> $license_key, 'license_data'=> $data );

	update_option( '_optimizeform_core_new_license', $new_licenses );

	woosuite_core_maybe_send_new_plugins_data();

	return $data;
}
function woosuite_core_deactivate_license() {
	$data = woosuite_core_api_license_data( woosuite_core_get_license_key() );
	$deactivated_license_key = woosuite_core_get_license_key();

	if ( is_wp_error( $data ) ) {
		return $data;
	}
	woosuite_core_maybe_send_plugins_data( array(
		'deactivate_license' => 1
	) );

	delete_option( '_woosuite_core_license_key' );
	delete_option( '_woosuite_core_license_data' );

	return true;
}
function woosuite_core_deactivate_new_license($license_key) {

	$saved_array = get_option( '_optimizeform_core_new_license', '' );
	$new_licenses = !empty( $saved_array ) ? $saved_array : array();
	if (isset($new_licenses[$license_key])) {
		unset($new_licenses[$license_key]);
	}

	update_option( '_optimizeform_core_new_license', $new_licenses );

	$data = woosuite_core_api_license_data( $license_key );

	if ( is_wp_error( $data ) ) {
		return $data;
	}

	woosuite_core_maybe_send_new_plugins_data($license_key, $data);

	return true;
}
function woosuite_core_get_license_data() {
	return get_option( '_woosuite_core_license_data' );
}

function woosuite_core_get_license_key() {
	return get_option( '_woosuite_core_license_key' );
}

function woosuite_core_log( $message, $context = array() ) {
	do_action(
		'w4_loggable_log',
		// string, usually a name from where you are storing this log
		'Woosuite Core',
		// string, log message
		$message,
		// array, a data that can be replaced with placeholder inside message.
		$context
	);
}

function woosuite_core_get_plugins_data() {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	$installed_plugins = get_plugins( '/' );
	if ( empty( $installed_plugins ) ) {
		return array();
	}

	$our_plugins = wp_list_filter(
		$installed_plugins,
		array(
			'Author'    => 'Woosuite',
			'AuthorURI' => 'https://aovup.com'
		)
	);
	if ( empty( $our_plugins ) ) {
		return array();
	}

	$plugins_data = array();
	foreach ( $our_plugins as $basename => $plugin ) {
		$status = 'installed';
		if ( is_plugin_active( $basename ) ) {
			$status = 'active';
		}

		$plugins_data[] = array(
			'slug'    => dirname( $basename ),
			'version' => $plugin['Version'],
			'status'  => $status
		);
	}

	return $plugins_data;
}

/**
 * Get license data.
 *
 * @return array Array of available modules.
 */
function woosuite_core_maybe_send_plugins_data( $extra = array() ) {
	$plugins_data = woosuite_core_get_plugins_data();

	$data = array(
		'plugins'    => $plugins_data,
		'wp_url'     => esc_url( site_url() ),
		'wp_locale'  => get_locale(),
		'wp_version' => get_bloginfo( 'version', 'display' ),
	);

	// Add license if present.
	if ( woosuite_core_get_license_key() ) {
		$data['license_key'] = woosuite_core_get_license_key();
	}

	if ( isset( $extra['deactivate_license'] ) ) {
		$data['deactivate_license'] = 1;
	}

	if ( isset( $extra['activate_license'] ) ) {
		$data['activate_license'] = 1;
	}
	// Check if we had already sent that data.
	if ( $data === get_transient( 'woosuite_core_plugins_data' ) ) {
		return true;
	}

	woosuite_core_log( 'woosuite_core_maybe_send_plugins_data', array(
		'data' => $data
	));

	// Store lastest plugin data payload.
	set_transient( 'woosuite_core_plugins_data', $data, DAY_IN_SECONDS );

	$request = wp_remote_post(
		woosuite_core_get_api_url( "/install/bulk" ),
		array(
			'timeout' => 15,
			'headers' => array(
				'Content-Type' => 'application/json'
			),
			'body' => json_encode( $data )
		)
	);

	if ( is_wp_error( $request ) ) {
		return $request;
	}

	$body = json_decode( wp_remote_retrieve_body( $request ) );

	if ( ! empty( $body->data ) && ! empty( $body->data->status ) && 200 !== $body->data->status ) {
		return new WP_Error( $body->code, $body->message );
	}

	return true;
}
function woosuite_core_maybe_send_new_plugins_data( $license_key = '', $plugins_data = array() ) {

	$data = array(
		'plugins'    => $plugins_data,
		'wp_url'     => esc_url( site_url() ),
		'wp_locale'  => get_locale(),
		'wp_version' => get_bloginfo( 'version', 'display' ),
	);

	// Add license if present.
	if ( !empty($license_key) ) {
		$data['license_key'] = $license_key;
	}


	woosuite_core_log( 'woosuite_core_maybe_send_new_plugins_data', array(
		'data' => $data
	));

	$request = wp_remote_post(
		woosuite_core_get_api_url( "/install/bulk" ),
		array(
			'timeout' => 15,
			'headers' => array(
				'Content-Type' => 'application/json'
			),
			'body' => json_encode( $data )
		)
	);

	if ( is_wp_error( $request ) ) {
		return $request;
	}

	$body = json_decode( wp_remote_retrieve_body( $request ) );

	if ( ! empty( $body->data ) && ! empty( $body->data->status ) && 200 !== $body->data->status ) {
		return new WP_Error( $body->code, $body->message );
	}

	return true;
}
/**
 * Checks if license exists and license status is active
 *
 * @return boolean
 */
function woosuite_core_is_license_active() {
	$data = woosuite_core_get_license_data();

	if ( is_object($data) && isset( $data->status ) && $data->status === 'active' ) {
		return true;
	}
	return false;
}

/**
 * Get license data live from api url.
 *
 * @return array|WP_Error Array of available modules.
 */
function woosuite_core_api_license_data( $license_key ) {
	$data = array(
		'license_key' => $license_key,
		'wp_url'      => esc_url( site_url() ),
		'wp_locale'   => get_locale(),
		'wp_version'  => get_bloginfo( 'version', 'display' )
	);

	$request = wp_remote_post(
		woosuite_core_get_api_url( '/license/' ),
		array(
			'headers' => array(
				'Content-Type' => 'application/json'
			),
			'body' => json_encode( $data )
		)
	);

	if ( is_wp_error( $request ) ) {
		return array();
	}

	$body = json_decode( wp_remote_retrieve_body( $request ) );

	if ( ! empty( $body->data ) && ! empty( $body->data->status ) && 200 !== $body->data->status ) {
		return new WP_Error( $body->code, $body->message );
	}

	update_option( '_woosuite_core_license_data', $body );

	return $body;
}

/**
 * Get new license data live from api url.
 *
 * @return array Array of available modules.
 */
function woosuite_core_api_new_license_data( $license_key ) {
	$data = array(
		'license_key' => $license_key,
		'wp_url'      => esc_url( site_url() ),
		'wp_locale'   => get_locale(),
		'wp_version'  => get_bloginfo( 'version', 'display' )
	);

	$request = wp_remote_post(
		woosuite_core_get_api_url( '/license/' ),
		array(
			'headers' => array(
				'Content-Type' => 'application/json'
			),
			'body' => json_encode( $data )
		)
	);

	if ( is_wp_error( $request ) ) {
		return array();
	}

	$body = json_decode( wp_remote_retrieve_body( $request ) );

	if ( ! empty( $body->data ) && ! empty( $body->data->status ) && 200 !== $body->data->status ) {
		return new WP_Error( $body->code, $body->message );
	}

	return $body;
}

/**
 * Get verion data live from api url
 *
 * @return Array of available versions.
 */
function woosuite_core_api_version_data() {
	$data = array();
	if ( ! function_exists( 'woosuite_core_get_modules' )
	     || ! function_exists( 'woosuite_core_get_version_data_array' ) ) {
		return array();
	}

	$plugin_changelog_array = array();

	if ( false === ( $woosuite_core_all_plugins = get_transient( 'woosuite_core_all_plugins' ) ) ) {
		$woosuite_core_all_plugins = woosuite_core_get_modules();
		woosuite_core_sort_plugins_by_latest_updated_date( $woosuite_core_all_plugins );

		set_transient( 'woosuite_core_all_plugins', $woosuite_core_all_plugins, 48 * HOUR_IN_SECONDS );
	}

	if ( empty( $woosuite_core_all_plugins ) ) {
		return array();
	}

	foreach ( $woosuite_core_all_plugins as $plugin ) {
		if ( empty( $plugin->sections->changelog ) ) {
			continue;
		}
		$plugin_changelog_temp = woosuite_core_get_version_data_array( $plugin->name, $plugin->sections->changelog );
		if( empty( $plugin_changelog_temp ) ) {
			continue;
		}
        $plugin_changelog_array[] = $plugin_changelog_temp[0];
	}

	return $plugin_changelog_array;
}

if ( ! function_exists( 'woosuite_core_sort_plugins_by_latest_updated_date' ) ) {
	/**
	 * @param $pluginsArr
	 */
	function woosuite_core_sort_plugins_by_latest_updated_date( &$pluginsArr ) {
		$pluginsArr = empty( $pluginsArr ) ? woosuite_core_get_modules() : $pluginsArr;

		if ( is_array( $pluginsArr ) ) {
			usort( $pluginsArr, function ( $a, $b ) {
				return strtotime( $b->last_date_updated ) - strtotime( $a->last_date_updated );
			} );
		}
	}
}

/**
 * @return Array of version data
 */
function woosuite_core_get_version_data_array( $title, $changelog_string ) {
	if ( empty( $changelog_string ) ) {
		return array();
	}

	$data = array();
    $changelog_string = str_replace( array('<h4>','</h4>'), array('<p>','</p><p>'), $changelog_string);
    $paragraphElements = woosuite_core_get_paragraph_from_string($changelog_string);
    $p_i = 0;
    foreach ($paragraphElements as $p_key => $p_value) {
    	if( $p_key % 2 == 0 ) {
    		$version_date_mix = explode(' - ', $p_value->nodeValue);
            $log_version = $version_date_mix[0];
		    $log_date = str_replace( array( '[', ']' ), '', isset( $version_date_mix[1] ) ? $version_date_mix[1] : '' );
            $data[$p_i]['title'] = $title;
            $data[$p_i]['version'] = str_replace('Version: ', '', $log_version);
            $data[$p_i]['date'] = $log_date;
    	} else {
    		$data[$p_i]['changelog'] = wpautop($p_value->nodeValue) ?:'Details not available';
    		$p_i++;
    	}
    }
	return $data;
}

/**
 * @return array|DOMNodeList Convert paragraph from string
 */
function woosuite_core_get_paragraph_from_string( $html_string ) {
	if ( empty( $html_string ) ) {
		return array();
	}
	$domDocument = new DOMDocument();
	$domDocument->loadHTML( $html_string );

	return $domDocument->getElementsByTagName( 'p' );
}


function woosuite_core_check_plugin_belong_to_license($slug){
	$current_license_key = woosuite_core_get_license_key();
	if ( ! $current_license_key ) {
		return false;
	}

	$resp_flag = true;
	//get group match status
	$response_check_group = woosuite_core_get_body_response( woosuite_core_get_api_url( "/groupcheck/{$current_license_key}/{$slug}/" ) );

	if ( ! $response_check_group['status'] ) {
		$resp_flag = false;
	}

	if ( ! $resp_flag ) {
		$new_licenses = get_option( '_optimizeform_core_new_license', '' );
		if ( empty( $new_licenses ) ) {
			return false;
		}
		foreach ( $new_licenses as $license_key => $license ) {
			$body = woosuite_core_get_body_response( woosuite_core_get_api_url( "/groupcheck/{$license_key}/{$slug}/" ) );
			$group_license_matched = isset( $body['status'] ) ? $body['status'] : false;

			if ( $group_license_matched ) {
				return true;
			}
		}
	}

	return $resp_flag;
}

function woosuite_core_get_body_response($url){
	$r = wp_remote_get( $url );
	if(is_wp_error($r)){
		return array();
	}
	$body = json_decode( wp_remote_retrieve_body( $r ), true );
	if(is_wp_error($body)){
		return array();
	}
	return $body;
}

/**
 * Get all available modules.
 *
 * @return array Array of available modules.
 */
function woosuite_core_get_available_plugins() {
	$cached_available_plugins = get_transient( 'woosuite_core_available_plugins' );
	if ( false === $cached_available_plugins ) {
		$all_modules = woosuite_core_get_modules( true );

		$plugins_data = array();

		foreach ( $all_modules as $module ) {
			$plugins_data[ $module->slug ] = array(
				'name' => $module->name,
				'file_path' => $module->path,
				'homepage' => $module->homepage,
				'type' => array( 'b2b', 'b2b-b2c' ),
				'included' => woosuite_core_check_plugin_belong_to_license( $module->slug )
			);
		}
		set_transient( 'woosuite_core_available_plugins', $plugins_data, 3600 );

		return $plugins_data;
	}

	return $cached_available_plugins;
}

function woosuite_core_get_admin_edit_user_link( $user_id ){

	if ( get_current_user_id() == $user_id )
		$edit_link = get_edit_profile_url( $user_id );
	else
		$edit_link = add_query_arg( 'user_id', $user_id, self_admin_url( 'user-edit.php'));

	return $edit_link;
}

if(!function_exists('woosuite_core_is_woocommerce_activated')){
	function woosuite_core_is_woocommerce_activated(){
		return class_exists( 'WooCommerce' );
	}
}