<?php
$check_active = apply_filters( 'wbw_check_module_active', $module->slug );
// var_dump($module->path);
# print_r( $module );
if ( woosuite_check_module_active_by_path( $module->path ) ) {
	$settings_url = woosuite_core_get_module_settings_url( $module->slug );
	if ( $settings_url ) {
		printf(
			'<a class="action-btn manage-btn" href="%s">%s</a>',
			$settings_url,
			__( 'Manage', 'woosuite-core' )
		);
	}
	printf(
		'<div class="woosuite-core-switch switch-on woosuite-core-module-switch" data-slug="%s" data-nonce="%s">
			<span class="switch-label">
				<span class="switch-inner"></span>
				<span class="switch-pointer"></span>
			</span>
		</div>',
		$module->slug,
		wp_create_nonce( 'deactivate-plugin_' . $module->slug )
	);

} elseif ( woosuite_check_module_installed_by_path( $module->path ) ) {
	if ( $module->homepage && isset( $_GET['page'] ) && $_GET['page'] === 'woosuite-core-addons' ) {
		printf(
			'<a class="action-btn learn-btn" href="%s" target="_blank">%s</a>',
			$module->homepage,
			__( 'Learn more', 'woosuite-core' )
		);
	}
	printf(
		'<div class="woosuite-core-switch switch-off woosuite-core-module-switch" data-slug="%s" data-nonce="%s">
			<span class="switch-label">
				<span class="switch-inner"></span>
				<span class="switch-pointer"></span>
			</span>
		</div>',
		$module->slug,
		wp_create_nonce( 'activate-plugin_' . $module->slug )
	);
} elseif ( woosuite_core_is_license_active() ) {
	if ( $module->homepage ) {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'woosuite-core-addons' ) {
			printf(
				'<a class="action-btn learn-btn" href="%s">%s</a>',
				$module->homepage,
				__( 'Learn more', 'woosuite-core' )
			);
		}
		if (!$check_active) {
			printf(
				'<a class="action-btn" href="%s">%s</a>',
				$module->homepage,
				__( 'Upgrade Now', 'woosuite-core' )
			);
		}else {

			printf(
				'<button class="action-btn install-btn" data-slug="%s" data-nonce="%s">%s</button>',
				$module->slug,
				wp_create_nonce( 'updates' ),
				__( 'Install Now', 'woosuite-core' )
			);
		}
	}
} else {
	if ( $module->homepage ) {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'woosuite-core-addons' ) {
			printf(
				'<a class="action-btn learn-btn" href="%s">%s</a>',
				$module->homepage,
				__( 'Learn more', 'woosuite-core' )
			);
				
			_e( 'Activate License', 'woosuite-core' );
		} else {
			printf(
				'<a class="swatch-submit-link update-clicks" href="%s">%s</a>',
				$module->homepage,
				__( 'Get it now', 'woosuite-core' )
			);
		}
	}
}
