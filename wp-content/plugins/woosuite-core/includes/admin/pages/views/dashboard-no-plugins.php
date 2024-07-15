<div class="no-plugins-wrap">
    <h3><?php echo esc_html__( 'No active plugins', 'woosuite-core' ); ?></h3>
    <p><?php echo esc_html__( 'Your active plugins will show here when you have installed your first plugin.', 'woosuite-core' ); ?></p>
    <div class="no-plugins-btn">
        <?php 
        $license_active = woosuite_core_is_license_active();
        if ( $license_active ) {
            ?> 
            <a href="<?php echo esc_url(admin_url( 'admin.php?page=woosuite-core-addons' )); ?>">
                <button><?php echo esc_html__( 'Install a Plugin', 'woosuite-core' ); ?></button>
            </a>
            <?php
        }
        else {
            ?> 
            <a href="<?php echo esc_url(admin_url( 'admin.php?page=woosuite-core-license' )); ?>">
                <button><?php echo esc_html__( 'Activate License', 'woosuite-core' ); ?></button>
            </a>
            <?php
        }
        ?>
    </div>
    <div class="noplugins-img">
        <img src="<?php echo esc_url( WOOSUITE_CORE_ASSETS_URL . 'images/noplugin.png' ); ?>" alt="">
    </div>
</div>