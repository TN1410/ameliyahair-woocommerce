<?php
/**
 * Admin Settings Page Class.
 *
 * @package Woosuite_Core
 * @class Woosuite_Core_Admin_Modules_Page
 */

class Woosuite_Core_Admin_License_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9999 );
	}

	/**
	 * Sanitize settings option
	 */
	public function admin_menu() {
        if ( Woosuite_Core::is_submenu_enabled( 'activate' ) ) {
			// Access capability.
			$access_cap = apply_filters( 'woosuite_core_admin_page_access_cap', 'manage_options' );

			// Register menu.
			$admin_page = add_submenu_page(
				'woosuite-core',
				__( 'Woosuite Core License', 'woosuite-core' ),
				__( 'Activate', 'woosuite-core' ),
				$access_cap,
				'woosuite-core-license',
				array( $this, 'render_page' )
			);

			add_action( "admin_print_styles-{$admin_page}", array( $this, 'print_scripts' ) );
			add_action( "load-{$admin_page}", array( $this, 'handle_actions' ) );
		}
	}

	public function handle_actions() {
        $addon_page_url=esc_url(admin_url( 'admin.php?page=woosuite-core-addons' ));
		if ( isset( $_POST['action'] ) ) {
			if ( 'woosuite_core_set_license' === $_POST['action'] ) {
				$activate = woosuite_core_activate_license( $_POST['license_key'] );

				$arg = is_wp_error( $activate ) ?
					array( 'error' => urlencode( $activate->get_error_message() ) ) :
					array( 'message' => urlencode( __( 'License activated.' ) ) );

				wp_redirect( is_wp_error( $activate ) ? add_query_arg( $arg ) : add_query_arg( $arg, $addon_page_url ) );
				exit;

			} elseif ( 'woosuite_core_add_new_license' === $_POST['action'] ) {
				$activate = optimizeform_core_new_activate_license( $_POST['new_license_key'] );

				$arg = is_wp_error( $activate ) ?
					array( 'error' => urlencode( $activate->get_error_message() ) ) :
					array( 'message' => urlencode( __( 'License activated.' ) ) );

				wp_redirect( is_wp_error( $activate ) ? add_query_arg( $arg ) : add_query_arg( $arg, $addon_page_url ) );

				exit;
			} elseif ( 'woosuite_core_remove_license' === $_POST['action'] ) {

				$deactivate = woosuite_core_deactivate_license();

				$arg = is_wp_error( $deactivate ) ?
					array( 'error' => urlencode( $deactivate->get_error_message() ) ) :
					array( 'message' => urlencode( __( 'License deactivated.' ) ) );

				wp_redirect( add_query_arg( $arg ) );
				exit;
			} elseif ( 'woosuite_core_remove_new_license' === $_POST['action'] ) {

				$deactivate = woosuite_core_deactivate_new_license( $_POST['new_license_key'] );

				$arg = is_wp_error( $deactivate ) ?
					array( 'error' => urlencode( $deactivate->get_error_message() ) ) :
					array( 'message' => urlencode( __( 'License deactivated.' ) ) );

				wp_redirect( add_query_arg( $arg ) );
				exit;
			}
		}

		// Maybe send current plugin data.
		woosuite_core_maybe_send_plugins_data();
	}

	public function render_page() {
		$license_key = woosuite_core_get_license_key();

		?>
		<?php do_action( 'woosuite_core_admin_page_top' ); ?>

		<h1><?php _e( 'Manage License', 'woosuite-core' ) ?></h1>

		<?php do_action( 'woosuite_core_admin_page_notices' ); ?>

		<p>
			<a href="https://aovup.com/support/" class="button help-btn" target="_blank">
				<span class="btn-icon dashicons dashicons-editor-help"></span>
				<span class="btn-text"><?php _e( 'Need Help?', 'woosuite-core' ); ?></span>
			</a>
		</p>

		<?php
		
		$license_messages = array();

		// DEBUG license data.
		if ( ! empty( $license_key ) ) {
			$data = woosuite_core_api_license_data( $license_key );

			if ( is_wp_error( $data ) ) {
				$license_heading = __( 'Api Error', 'woosuite-core' );
				if ( 'rest_no_route' === $data->get_error_code() ) {
					$license_messages[] = __( 'Woosuite API server is unreachable right now. Should be back soon.', 'woosuite-core' );
				} else {
					$license_messages[] = sprintf(
						__( 'Error: %s.' ),
						$data->get_error_message()
					);
				}
			}
			else if ( !is_object($data) ) {
				$license_heading = __( 'Api Error', 'woosuite-core' );
				$license_messages[] = __( 'Something went wrong.', 'woosuite-core' );
			}
			else {

				$license_heading = __( 'License Information', 'woosuite-core' );

				# $data->status = 'suspended';

				if ( $data->status === 'active' ) {
					$license_heading = __( 'License status: Active', 'woosuite-core' );


					$license_messages[] = sprintf(
						__( 'This license will be expired on %s.', 'woosuite-core' ),
						mysql2date( 'dS M Y', $data->date_active_through )
					);

					$license_messages[] = sprintf(
						__( 'You have used it on %d sites out of allocated %d sites.', 'woosuite-core' ),
						$data->installs_active,
						$data->installs_allowed
					);

				} elseif ( $data->status === 'expired' ) {
					$license_heading = 'License Expired';

					$license_messages[] = __( 'Automatic updates has been disabled for all of our Woosuite plugins.' );
					$license_messages[] = sprintf(
						__( '<a href="%s">Visit here</a> to renew your license.' ),
						'https://aovup.com/my-account'
					);

				} elseif ( $data->status === 'onhold' ) {
					$license_heading = __( 'License On-hold' );

					$license_messages[] = __( 'Our team is reviewing your license. Till then, you will not receive automatic updates..' );
					$license_messages[] = sprintf(
						__( '<a href="%s">Contact us</a> if the issue is taking longer than expected.' ),
						'https://aovup.com/support'
					);

				} else {
					$license_heading = __( 'License Suspended', 'woosuite-core' );

					$license_messages[] = sprintf(
						__( 'Possible reason: %s' ),
						$data->status_note ? $data->status_note : __( 'Nothing', 'woosuite-core' )
					);
				}
			}

			if ( ! empty( $license_messages ) && is_array( $license_messages ) ) {
				echo '<div class="woosuite-core-info-box">';
					echo '<h2>' . $license_heading . '</h2>';
					echo '<ul><li>';
					echo join(  '</li><li>',$license_messages );
					echo '</li></ul>';
				echo '</div>';
			}
			# Woosuite_Core_Utils::p( $data );
		}
		?>

		<div class="woosuite-core-box">
			<?php
				include 'views/license-form.php';
			?>
		</div>
		<?php
        	include 'views/new-license-form.php';
        ?>

		<?php do_action( 'woosuite_core_admin_page_bottom'  );
	}

	public function print_scripts() {
		do_action( 'woosuite_core_admin_page_scripts' );
	}
}
