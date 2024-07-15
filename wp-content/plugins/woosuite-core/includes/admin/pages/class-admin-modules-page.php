<?php
/**
 * Admin Settings Page Class.
 *
 * @package Woosuite_Core
 * @class Woosuite_Core_Admin_Modules_Page
 */

class Woosuite_Core_Admin_Modules_Page {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 6 );
		add_action( 'wp_ajax_woo_activate_plugin', array( $this, 'activate_plugin' ) );
		add_action( 'wp_ajax_update-selected-not-included-plugin', array( $this, 'update_selected_not_included_plugin' ) );
	}

	/**
	 * Sanitize settings option
	 */
	public function admin_menu() {
        if ( Woosuite_Core::is_submenu_enabled( 'addons' ) ) {
			// Access capability.
			$access_cap = apply_filters( 'woosuite_core_admin_page_access_cap', 'manage_options' );

			// Register menu.
			$admin_page = add_submenu_page(
				'woosuite-core',
				__( 'Woosuite Core Addons', 'woosuite-core' ),
				__( 'Addons', 'woosuite-core' ),
				$access_cap,
				'woosuite-core-addons',
				array( $this, 'render_page' )
			);

			add_action( "admin_print_styles-{$admin_page}", array( $this, 'print_scripts' ) );
			add_action( "load-{$admin_page}", array( $this, 'handle_actions' ) );
		}
	}

	public function handle_actions() {
		// Maybe send current plugin data.
		woosuite_core_maybe_send_plugins_data();
	}

	public function update_selected_not_included_plugin() {
		if ( ! isset( $_POST['listOfPlugin'] ) ) {
			wp_send_json_error();
		}
		update_option( 'woosuite_core_not_included_plugins', $_POST['listOfPlugin'] );
		wp_send_json_success();

	}

	public function render_page(){
		
		?>
			<div class="opt-desh-wrap">
				<?php include __DIR__ . '/views/dashboard-header.php'; ?>

				<div class="opt-desh-body-wrap">
					<div class="opt-desh-body">
						<div class="opt-desh-container">
							
							<?php include __DIR__ . '/views/dashboard-menu.php'; ?>

							<!-- module banner -->
							<div class="opt-module-banner">
								<div class="opt-module-banner-grid">
									<div class="opt-main-content-banner">
										<h3 class="opt-banner-title"><?php _e('Manage your plugins here','woosuite-core');?></h3>
										<p><?php _e('You can install and activate your plugins below', 'woosuite-core');?></p>
									</div>
									<div class="opt-module-banner-img">
										<img src="<?php echo WOOSUITE_CORE_BASE_URL;?>assets/images/wc-plugins.png" class="img-100" alt="">
									</div>
								</div>
							</div>
							<!-- module banner -->
							<!-- module wrap -->
							<div class="opt-modules-wrap">
								<div class="opt-moules-head">
									<?php if ( isset( $_GET['message'] ) && ! empty( $_GET['message'] ) ) { ?>
                                        <div class="module-card">
                                            <div class="main">
                                                <div class="desc">
                                                    <h3><?php _e('Your license key has been activated successfully.' ,'woosuite-core'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="footer">
                                                <a class="action-btn wizard-btn"
                                                   target="_blank"
                                                   href="<?php echo $addon_page_url=esc_url(admin_url( 'admin.php?page=woosuite-core-license' ));?>">
	                                                <?php _e('Click here to view your license detail' ,'woosuite-core'); ?>
                                                </a>
                                            </div>
                                        </div>
									<?php } ?>
                                    <div class="module-card">
                                        <div class="main">
                                            <div class="desc">
                                                <h3>
	                                                <?php _e('Let\'s add a Wholesale component to your store' ,'woosuite-core'); ?>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="footer">
                                            <a class="action-btn wizard-btn" target="_blank" href="https://aovup.com/support/">
	                                            <?php _e('Click here to get started' ,'woosuite-core'); ?>
                                            </a>
                                        </div>
                                    </div>
								</div>
								<div class="opt-module-content-wrap">
									<?php
									$modules = woosuite_core_get_modules(true);
									include 'views/modules-grid.php';
									?>
								</div>
							</div>
							<!-- module wrap -->
						</div>
					</div>
				</div>


				<?php
				$available_plugins = woosuite_core_get_available_plugins();
				include 'views/getting-started.php';
				?>
			</div>
		<?php
	}

	public function print_scripts() {
		do_action( 'woosuite_core_admin_page_scripts' );
		wp_localize_script( 'woosuite-core-admin', 'woosuiteCore', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'pageUrl' => admin_url( 'admin.php?page=woosuite-core' ),
			'wizardRedirectUrl' => esc_url( admin_url( 'admin.php?page=woosuite-core' ) )
		] );

		do_action( 'woosuite_core_admin_page_scripts', 'woosuite-core' );
	}

	public function activate_plugin() {
		if ( empty( $_POST['slug'] ) ) {
			wp_send_json_error(
				array(
					'slug' => '',
					'errorCode' => 'no_plugin_specified',
					'errorMessage' => __( 'No plugin specified.', 'woosuite-core' ),
				)
			);
		}
		$plugin_slug = sanitize_text_field( $_POST['slug'] );
		if ( empty( $_POST['file_path'] ) ) {
			wp_send_json_error(
				array(
					'slug' => $plugin_slug,
					'errorCode' => 'no_plugin_path_specified',
					'errorMessage' => __( 'No plugin file path specified.', 'woosuite-core' ),
				)
			);
		}

		$plugin = sanitize_text_field( $_POST['file_path'] );

		check_ajax_referer( 'woo-activate-plugin_' . $plugin );

		if ( ! current_user_can( 'activate_plugin', $plugin ) ) {

			wp_send_json_error(
				array(
					'slug' => $plugin_slug,
					'errorCode' => 'no_permision',
					'errorMessage' => __( 'Sorry, you are not allowed to activate this plugin.', 'woosuite-core' ),
				)
			);
		}

		if ( is_plugin_active( $plugin ) ) {
			wp_send_json_success(
				array(
					'slug' => $plugin_slug,
					'code' => 'already_activated',
					'message' => __( 'The plugin is already activated', 'woosuite-core' ),
				)
			);
		}

		if ( is_multisite() && ! is_network_admin() && is_network_only_plugin( $plugin ) ) {
			wp_send_json_error(
				array(
					'slug' => $plugin_slug,
					'errorCode' => 'only_network_activated',
					'errorMessage' => __( 'This plugin is activated via network setting only','woosuite-core' ),
				)
			);
		}


		$result = activate_plugin( $plugin );
		if ( is_wp_error( $result ) ) {
			if ( 'unexpected_output' === $result->get_error_code() ) {
				wp_send_json_error(
					array(
						'slug' => $plugin_slug,
						'errorCode' => 'unexpected_error',
						'errorMessage' => __( 'Errored' ),
					)
				);
			} else {
				wp_send_json_error();
			}
		}
		wp_send_json_success(
			array(
				'slug' => $plugin_slug,
				'code' => 'activated',
				'message' => __( 'The plugin is activated' ),
			)
		);
	}

}
