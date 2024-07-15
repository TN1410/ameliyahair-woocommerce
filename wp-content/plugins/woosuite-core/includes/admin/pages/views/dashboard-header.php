<!-- dashboard header -->
<div class="opt-desh-header">
    <div class="opt-desh-headr-inner">
        <div></div>
        <?php
		$logo = apply_filters( 'woosuite_core_admin_logo', WOOSUITE_CORE_BASE_URL . 'assets/images/wslogo-dash.png' );
        ?>
        <div class="opt-desh-logo"><img src="<?php echo $logo; ?>" alt="opt-img"></div>
        <div class="theme-version">
            <span class="version-text"><?php echo esc_html( __('Version ', 'woosuite-core' ) . WOOSUITE_CORE_VERSION ); ?></span>
        </div>
    </div>
</div>
<!-- dashboard header -->