<?php
/**
 * Admin page template helper.
 *
 * @package Woosuite_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Page template helper.
 *
 * @class Woosuite_Core_Admin_Page_Template_Helper
 */

class Woosuite_Core_Admin_Page_Template_Helper {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woosuite_core_admin_page_top', array( $this, 'admin_page_top' ) );
		add_action( 'woosuite_core_admin_page_bottom', array( $this, 'admin_page_bottom' ) );
		add_action( 'woosuite_core_admin_page_scripts', array( $this, 'admin_page_scripts' ) );
		add_action( 'woosuite_core_admin_page_notices', array( $this, 'admin_page_notices' ) );
	}

	/**
	 * Display this on all admin page top section.
	 *
	 * @return void
	 */
	public function admin_page_top() {
		?>
		
		<!-- reander html wrap -->
		<div class="opt-desh-wrap">
			<?php include __DIR__ . '/pages/views/dashboard-header.php'; ?>
			

			<!-- dashboard body -->
			<div class="opt-desh-body-wrap">
				<div class="opt-desh-body">
					<div class="opt-desh-container">
						<?php include __DIR__ . '/pages/views/dashboard-menu.php'; ?>
						<div class="opt-desh-main-content-wrap">
							<!-- dashboard main content -->
							<div class="opt-desh-main-content" <?php echo Woosuite_Core::get_content_style(); ?>>
								<div class="opt-desh-main-inner">
									
									<!-- feature plugin section -->
									<div class="woosuite-dashboard-content">
										<div class="woosuite-dashboard-content-wrap">
		
		<?php
	}

	private function get_menu_template( $item ) {
		$class = 'menu-item';

		if ( ! empty( $item['class'] ) ) {
			$class .= ' ' . $item['class'];
		}

		if ( $item['icon'] ) {
			$title = sprintf(
				'<span class="menu-icon"><i class="%s"></i></span>
				<span>%s</span>',
				$item['icon'],
				$item['name']
			);
		} else {
			$title = sprintf(
				'<span>%s</span>',
				$item['name']
			);
		}

		$submenu = '';
		if ( ! empty( $item['submenu'] ) ) {
			$submenu .= '<ul class="submenu">';
			foreach ( $item['submenu'] as $submenu_item ) {
				$submenu .= $this->get_menu_template( $submenu_item );
			}
			$submenu .= '</ul>';
		}

		if ( $item['url'] ) {
			$title = sprintf(
				'<a href="%s">%s</a>',
				$item['url'],
				$title
			);
		}

		return sprintf(
			'<li class="%s">%s%s</li>',
			$class,
			$title,
			$submenu
		);
	}


	/**
	 * Display at the bottom of admin page.
	 *
	 * @return void
	 */
	public function admin_page_bottom() {
		?>
										</div>
									</div>
									<!-- feature plugin section -->
								</div>
							</div>
							<!-- dashboard main content -->

							<?php include __DIR__ . '/pages/views/dashboard-sidebar.php'; ?>
						</div>

						<?php include __DIR__ . '/pages/views/dashboard-footer.php'; ?>
					</div>
					
				</div>
			</div>
			
			<!-- dashboard body -->
		</div>	
		<?php
	}

	/**
	 * Enqueue required admin page scripts.
	 *
	 * @return void
	 */
	public function admin_page_scripts() {
		wp_enqueue_style( array( 'woosuite-core-admin' ) );
		wp_enqueue_script( array( 'woosuite-core-admin' ) );
	}

	/**
	 * Display notices on page.
	 *
	 * @return void
	 */
	public function admin_page_notices() {
		?>
		<div id="woosuite_core-admin-notes">
			<?php if ( isset( $_GET['error'] ) && ! empty( $_GET['error'] ) ) { ?>
				<div class="notice notice-error settings-error"><p><?php echo stripslashes( urldecode( $_GET['error'] ) ); ?></p></div>
			<?php } ?>
			<?php if ( isset( $_GET['message'] ) && ! empty( $_GET['message'] ) ) { ?>
				<div class="notice notice-success settings-error"><p><?php echo stripslashes( urldecode( $_GET['message'] ) ); ?></p></div>
			<?php } ?>
		</div>
		<?php
	}
}
