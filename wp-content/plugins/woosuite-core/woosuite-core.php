<?php
/**
 * Plugin Name: AovUp Core
 * Plugin URI: https://aovup.com
 * Description: AovUp modules manager, you will need this active to receive updates for our plugins.
 * Version: 1.1.8
 * Author: AovUp
 * Author URI: https://aovup.com
 * Requires at least: 4.5
 * Tested up to: 6.4.2
 * Text Domain: woosuite-core
 * Domain Path: /languages
 *
 * @package Woosuite_Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if already loaded other way.
if ( defined( 'WOOSUITE_CORE_PLUGIN_FILE' ) || defined( 'WOOSUITE_CORE_VERSION' ) ) {
	return;
}

// Define base file.
define( 'WOOSUITE_CORE_PLUGIN_FILE', __FILE__ );
define( 'WOOSUITE_CORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WOOSUITE_CORE_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
// Define plugin version. (test use).
define( 'WOOSUITE_CORE_VERSION', '1.1.8' );
// Define base url
$baseurl = plugin_dir_url(__FILE__);
define('WOOSUITE_CORE_BASE_URL',$baseurl);



/**
 * Intialize everything after plugins_loaded action.
 *
 * @return void
 */
function woosuite_core_init() {
	// Load the main plug class.
	if ( ! class_exists( 'Woosuite_Core' ) ) {
		require dirname( __FILE__ ) . '/includes/class-woosuite-core.php';
	}

	Woosuite_Core::get_instance();
}
woosuite_core_init();

//Add support for Woo HPOS
add_action( 'before_woocommerce_init', function () {

	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}

} );