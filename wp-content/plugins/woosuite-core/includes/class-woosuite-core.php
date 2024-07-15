<?php
/**
 * Main Plugin File.
 *
 * @package Woosuite_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Class.
 *
 * @class Woosuite_Core
 */
final class Woosuite_Core {

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	public $name = 'Woosuite Core';

	/**
	 * Singleton The reference the *Singleton* instance of this class.
	 *
	 * @var Woosuite_Core
	 */
	protected static $instance = null;

	/**
	 * Private clone method to prevent cloning of the instance of the
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Private unserialize method to prevent unserializing.
	 *
	 * @return void
	 */
	public function __wakeup() {}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	private function __construct() {
		$this->define_constants();
		$this->include_files();
		register_activation_hook( WOOSUITE_CORE_PLUGIN_FILE, array( $this, 'activation_hook_callback' ) );
		$this->register_hooks();
		$this->initialize();
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		define( 'WOOSUITE_CORE_DIR', plugin_dir_path( WOOSUITE_CORE_PLUGIN_FILE ) );
		define( 'WOOSUITE_CORE_URL', plugin_dir_url( WOOSUITE_CORE_PLUGIN_FILE ) );
		define( 'WOOSUITE_CORE_BASENAME', plugin_basename( WOOSUITE_CORE_PLUGIN_FILE ) );
		define( 'WOOSUITE_CORE_NAME', $this->name );
	}

	/**
	 * Include plugin dependency files
	 */
	private function include_files() {
		require WOOSUITE_CORE_DIR . '/includes/functions.php';
		require WOOSUITE_CORE_DIR . '/includes/class-woosuite-core-plugins-api.php';
		require WOOSUITE_CORE_DIR . '/includes/class-woosuite-core-utils.php';
		require WOOSUITE_CORE_DIR . '/includes/class-woosuite-core-settings-api.php';

		if ( is_admin() ) {
			require WOOSUITE_CORE_DIR . '/includes/admin/class-woosuite-core-ajax-handlers.php';
			require WOOSUITE_CORE_DIR . '/includes/admin/class-woosuite-core-admin-main.php';
			require WOOSUITE_CORE_DIR . '/includes/admin/class-woosuite-core-admin-dashboard-widget.php';
			require WOOSUITE_CORE_DIR . '/includes/admin/class-woosuite-core-admin-page-template-helper.php';
			require WOOSUITE_CORE_DIR . '/includes/class-child-plugins-manager.php';
			require WOOSUITE_CORE_DIR . '/includes/admin/pages/class-admin-modules-page.php';
			require WOOSUITE_CORE_DIR . '/includes/admin/pages/class-admin-license-page.php';
			require WOOSUITE_CORE_DIR . '/includes/admin/pages/class-admin-dashboard-page.php';

			if ( class_exists( 'Woosuite_White_Label' ) ) {
				require WOOSUITE_WHITE_LABEL_PATH . '/includes/class-admin-whitelabel-page.php';
			}
		}
	}

	/**
	 * Initialize the plugin
	 */
	private function initialize() {
		Woosuite_Core_Plugins_Api::get_instance();

		if ( is_admin() ) {
			new Woosuite_Core_Ajax_Handlers();
			new Woosuite_Core_Admin_Page_Template_Helper();
			new Woosuite_Core_Admin_Main();
			new Woosuite_Core_Admin_Dashboard_Widget();
			new Woosuite_Core_Admin_Modules_Page();
			new Woosuite_Core_Admin_License_Page();
			new Woosuite_Core_Admin_Dashboard_Page();
			new Woosuite_Core_Child_Plugins_Manager();

			if ( class_exists( 'Woosuite_White_Label' ) ) {
				new Woosuite_Core_Admin_Whitelabel_Page();
			}
		}

		// if whitelabel
		if ( self::get_whitelabel_data( 'enabled', FALSE ) ) {
			// add filter to change plugins name
			add_filter( 'all_plugins', array( $this, 'replace_whitelabel_plugin_name' ) );
		}

	}

	public function replace_whitelabel_plugin_name( $plugins ) {
		$data = self::get_whitelabel_data();

		// replace plugin's url
		if ( $data['agency-url'] ) {
			foreach ( $plugins as $key => $plugin ) {
				if ( strtolower( $plugin['Author'] ) == 'woosuite' ) {
					$plugins[$key]['AuthorURI'] = str_replace( 'https://aovup.com', $data['agency-url'], $plugins[$key]['AuthorURI'] );
					$plugins[$key]['PluginURI'] = str_replace( 'https://aovup.com', $data['agency-url'], $plugins[$key]['PluginURI'] );
				}
			}
		}

		
		// replace plugin's name
		if ( $data['agency-name'] ) {
			$string_to_replace = array( 'Woosuite', 'WooSuite', 'woosuite' );
			foreach ( $plugins as $key => $plugin ) {
				if ( strtolower( $plugin['Author'] ) == 'woosuite' ) {
					$plugins[$key]['Name'] = str_replace( $string_to_replace, $data['agency-name'], $plugins[$key]['Name'] );
					$plugins[$key]['Description'] = str_replace( $string_to_replace, $data['agency-name'], $plugins[$key]['Description'] );
					$plugins[$key]['Author'] = $data['agency-name'];
				}
			}
		}

		return $plugins;
	}

	/**
	 * Register hooks
	 */
	private function register_hooks() {
		add_action( 'init', array( $this, 'load_plugin_translations' ) );
		add_action( 'woosuite_core_news_cron', array( $this, 'process_news_cron' ) );
		add_action( 'woosuite_core_license_cron', array( $this, 'license_data_update' ) );
		add_action( 'admin_notices', array( $this, 'woocommerce_requirement_notice' ) );
	}

	public function woocommerce_requirement_notice() {
		if ( ! woosuite_core_is_woocommerce_activated() ) {
			$class   = 'notice notice-error';
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $this->get_admin_notices() );

			$this->deactivate_plugin();
		}
	}
	/**
	 * Function to deactivate the plugin
	 */
	protected function deactivate_plugin() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( WOOSUITE_CORE_PLUGIN_FILE );
	}

	/**
	 * Deactivate the plugin and display a notice if the dependent plugin is not compatible or not active.
	 */
	public function activation_hook_callback() {
		if ( ! woosuite_core_is_woocommerce_activated()) {
			$this->deactivate_plugin();
			wp_die( 'Could not be activated. ' . $this->get_admin_notices() );
		}
	}

	/**
	 * Writing the admin notice
	 */
	protected function get_admin_notices() {
		return sprintf(
			'%1$s requires WooCommerce installed and active. You can download WooCommerce latest version %2$s OR go back to %3$s.',
			$this->name,
			'<strong><a target="_blank" rel="nofollow noopener noreferrer" href="https://wordpress.org/plugins/woocommerce/">from here</a></strong>',
			'<strong><a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">plugins page</a></strong>'
		);
	}

	/**
	 * Upadte license data time to time
	 *
	 * @return void
	 */
	public function license_data_update() {
		$license_key = woosuite_core_get_license_key();
		if ( $license_key ) {
			// calling this will update the _woosuite_core_license_data option in database and we can freely use 
			// woosuite_core_is_license_active() function.
			woosuite_core_api_license_data($license_key);
		}
	}

	/**
	 * Register hooks
	 */
	public function process_news_cron() {
		woosuite_core_log( 'process_news_cron' );

		$target_tag_slug = 'dash';

		$url = woosuite_core_get_news_site_url() . 'wp-json/wp/v2/tags';
		$response = wp_remote_get( $url );

		if ( !is_wp_error( $response ) ) {
			$tags = json_decode(wp_remote_retrieve_body( $response ), true);
			update_option( 'the_tags', $tags );
			$target_tag = wp_list_filter( $tags, array('slug' => $target_tag_slug) );

			if ( is_array( $target_tag ) && isset( $target_tag[0]['id'] ) ) {
				$tag_id = $target_tag[0]['id'];
				$url = woosuite_core_get_news_site_url() . 'wp-json/wp/v2/posts?tags='.$tag_id.'&per_page=5';
				$response = wp_remote_get( $url, array( 'timeout' => 20 ) );
				$posts = array();
				if ( ! is_wp_error( $response ) ) {
					$body = json_decode( wp_remote_retrieve_body( $response ), true );

					foreach ( $body as $post ) {
						$posts[] = array(
							'title' => $post['title']['rendered'],
							'link' => $post['link']
						);
					}

					update_option( 'woosuite_core_news', $posts );

				}
			}

			
		}
		
	}

	/**
	 * Load plugin translation file
	 */
	public function load_plugin_translations() {
		load_plugin_textdomain(
			'woosuite-core',
			false,
			basename( dirname( WOOSUITE_CORE_PLUGIN_FILE ) ) . '/languages'
		);
	}

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Woosuite_Core The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Returns the whitelabel data
	 *
	 * @return array 
	 */
	public static function get_whitelabel_data( $key = '', $default = '' ) {
		if ( ! class_exists( 'Woosuite_White_Label' ) ) {
			return $default;
		}
			
		return Woosuite_White_Label::get_whitelabel_data( $key, $default );
	}
	
	public static function is_submenu_enabled( $slug ) {
		$submenu_disabled = self::get_whitelabel_data( $slug . '-submenu-disabled', FALSE );

		if ( ! $submenu_disabled ) {
			return TRUE;
		}

		return FALSE;
	}

	public static function get_content_style() {
        if ( Woosuite_Core::get_whitelabel_data( 'sidebar-disabled', FALSE ) ) {
			return 'style="max-width:100%!important;margin-right:0!important;"';
		}
	}

	public static function is_left_footer_disabled() {
		return Woosuite_Core::get_whitelabel_data( 'footer-left-disabled', FALSE );
	}

	public static function is_right_footer_disabled() {
		return Woosuite_Core::get_whitelabel_data( 'footer-right-disabled', FALSE );
	}

	public static function print_menu( $path ) {
        if ( ! class_exists( 'Woosuite_White_Label' ) && $path == 'whitelabel' ) {
			return;
		}

		$defaults = array(
			'dashboard'   => array(
				'label' => __( 'Dashboard', 'woosuite-core' ),
				'slug' => 'woosuite-core',
				'url' => admin_url( 'admin.php?page=woosuite-core' ),
			),
			'activate'   => array(
				'label' => __( 'License', 'woosuite-core' ),
				'slug' => 'woosuite-core-license',
				'url' => admin_url( 'admin.php?page=woosuite-core-license' ),
			),
			'addons'     => array(
				'label' => __( 'Addons', 'woosuite-core' ),
				'slug' => 'woosuite-core-addons',
				'url' => admin_url( 'admin.php?page=woosuite-core-addons' ),
			),
			'whitelabel' => array(
				'label' => __( 'White Label', 'woosuite-core' ),
				'slug' => 'woosuite-core-whitelabel',
				'url' => admin_url( 'admin.php?page=woosuite-core-whitelabel' ),
			),
			'docs'       => array(
				'label' => __( 'Docs', 'woosuite-core' ),
				'slug' => '',
				'url' => 'https://aovup.com/docs?utm_source=user-dashboard&utm_medium=header',
			),
			'support'    => array(
				'label' => __( 'Support', 'woosuite-core' ),
				'slug' => '',
				'url' => 'https://aovup.com/support?utm_source=user-dashboard&utm_medium=header'
			),
			'my-account' => array(
				'label' => __( 'My Account', 'woosuite-core' ),
				'slug' => '',
				'url' => 'https://aovup.com/my-account?utm_source=user-dashboard&utm_medium=header',
			),
		);

		if ( Woosuite_Core::is_submenu_enabled( $path ) ) {
			$url = Woosuite_Core::get_whitelabel_data( $path . '-url', FALSE );
			$url = wp_http_validate_url( $url ) ? $url : esc_url( $defaults[ $path ]['url'] );
		} else {
			return;
		}

		return sprintf( 
			'<li><a href="%s" class="%s">%s</a></li>',
			isset( $url ) ? $url : esc_url( $defaults[ $path ]['url'] ),
			isset( $_GET['page'] ) && $_GET['page'] == $defaults[$path]['slug'] ? 'active-desh-menu' : '',
			$defaults[$path]['label']
		);
	}
}
