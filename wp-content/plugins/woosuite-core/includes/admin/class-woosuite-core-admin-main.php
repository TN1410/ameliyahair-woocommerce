<?php
/**
 * Admin main class.
 *
 * @package Woosuite_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Main Class.
 *
 * @class Woosuite_Core_Admin_Main
 */
class Woosuite_Core_Admin_Main {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ), 5 );
		add_action( 'admin_head', array( $this, 'menu_icon_fix' ) );
		add_action( 'plugin_action_links_' . WOOSUITE_CORE_BASENAME, array( $this, 'plugin_action_links' ) );
	}

	/**
	 * Fix menu icon issue.
	 */
	public function menu_icon_fix() {
		?>
		<style type="text/css">
		.wp-menu-image > img{
			display: inline;
			border:none !important;
		}
		</style>
		<?php
	}

	/**
	 * Register admin assets.
	 */
	public function register_admin_scripts() {
		wp_register_style( 'themify-icons', WOOSUITE_CORE_URL . 'assets/css/themify-icons.css' );
		wp_register_style( 'woosuite-core-admin', WOOSUITE_CORE_URL . 'assets/css/admin.css', array( 'themify-icons' ) );
		wp_enqueue_style('woosuite-circle-admin', WOOSUITE_CORE_URL . 'assets/css/circle.css' );
		wp_enqueue_style('woosuite-wizard-admin', WOOSUITE_CORE_URL . 'assets/css/wizard.css',array('woosuite-circle-admin') );

		$dependencies = array( 'jquery' );
		if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'woosuite-core-addons' || $_GET['page'] == 'woosuite-core' )) {
			wp_register_script( 'woosuite-chartjs-admin', WOOSUITE_CORE_URL . 'assets/js/lib/chartjs/chart.min.js' );
			wp_register_script( 'woosuite-lib-jquery-steps', WOOSUITE_CORE_URL . 'assets/js/lib/jquery.steps.min.js' );

			$dependencies[] = 'woosuite-lib-jquery-steps';
			$dependencies[] = 'woosuite-chartjs-admin';
		}

		wp_register_script( 'woosuite-core-admin', WOOSUITE_CORE_URL . 'assets/js/admin.js', $dependencies );

		wp_register_script( 'woosuite-core-admin', WOOSUITE_CORE_URL . 'assets/js/admin.js', $dependencies );
		wp_localize_script( 'woosuite-core-admin', 'woosuite_core_admin', array(
			'api_url'                => woosuite_core_get_api_url(),
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'placeholder'            => function_exists( 'WC' ) ? WC()->plugin_url() . '/assets/images/placeholder.png' : '',
			'title'                  => __( 'Select image', 'woosuite-core' ),
			'b2b_applied_date_range' => get_option( 'woosuite_core_widget_b2b_applied_date_range', 'year' )
		) );

		wp_enqueue_script( 'woosuite-core-admin' );
	}

	/**
	 * Setup parent admin menu
	 */
	public function admin_menu() {
		// Access capability.
		$access_cap    = apply_filters( 'woosuite_core_admin_parent_menu_access_cap', 'manage_options' );
		// Menu riority.
		$menu_priority = apply_filters( 'woosuite_core_admin_parent_menu_priority', 4.9 );
		// Menu icon.
		$menu_icon     = apply_filters( 'woosuite_core_admin_parent_menu_icon', WOOSUITE_CORE_URL . 'assets/images/admin-menu-icon.png' );

		// Register menu.
		$admin_page = add_menu_page(
			__( 'Woosuite Core', 'woosuite-core' ),
			__( 'Woosuite', 'woosuite-core' ),
			$access_cap,
			'woosuite-core',
			'__return_false',
			$menu_icon,
			$menu_priority
		);
	}

	/**
	 * Adds plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$links['modules'] = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=woosuite-core' ),
			__( 'Modules', 'woosuite-core' )
		);
		$links['license'] = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=woosuite-core-license' ),
			__( 'License', 'woosuite-core' )
		);

		return $links;
	}
}
