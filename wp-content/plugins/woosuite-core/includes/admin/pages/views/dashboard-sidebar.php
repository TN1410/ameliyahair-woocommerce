<?php
if ( Woosuite_Core::get_whitelabel_data( 'sidebar-disabled', FALSE ) ) {
    echo Woosuite_Core::get_whitelabel_data( 'sidebar-content' );
} else {
?>
<!-- dashboard sidebar -->
<div class="opt-desh-sidebar">
    <div class="swatch-var-widget-wrap">
        <!-- widget -->

        <div class="swatch-var-widget">
            <h3 class="swatch-video-title"> <?php _e('Getting Started ', 'woosuite-core'); ?></h3>
            <div class="swat-video-frame">
                <iframe src="https://www.youtube.com/embed/ECJ8bA5dgS8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
        <!-- widget -->

        <!-- opt core setup widget -->
        <?php 
        if ( isset( $_GET['clicked_final'] ) ) {
            update_option( 'woosuite_core_clicked_final_step', true );
            wp_redirect( remove_query_arg( 'clicked_final' ) );
            exit;
        }
        $step_completed = 0;
        $license_activated_once = get_option( 'woosuite_core_license_activated_once', false );
        $addon_installed_once = get_option( 'woosuite_core_addon_installed_once', false ) && $license_activated_once;
        $clicked_final_step = get_option( 'woosuite_core_clicked_final_step', false ) && $addon_installed_once;

        $selected_not_included_plugins = get_option( 'woosuite_core_not_included_plugins', array() );


        if ( $license_activated_once ) {
            $step_completed++;
        }

        if ( $addon_installed_once ) {
            $step_completed++;
        }

        if ( $clicked_final_step ) {
            $step_completed++;
        }

        $num_of_selected_not_included_plugins = empty( $selected_not_included_plugins ) ? 0 : count( $selected_not_included_plugins );

        if ( ! empty( $selected_not_included_plugins ) ) {
	        foreach ( $selected_not_included_plugins as $index => $not_included_plugin ) {
                $is_activated=is_plugin_active( $not_included_plugin['path']);
		        $selected_not_included_plugins[$index]['activated']=$is_activated;
		        if ( $is_activated ) {
			        $step_completed ++;
		        }
	        }
        }

        $all_steps=( 3 + $num_of_selected_not_included_plugins );

        $completed_percentage = (int)($step_completed * 100) / $all_steps;
        if ( $step_completed < $all_steps ):
        ?>
        <div class="swatch-var-widget setscore-widget">
            <h2 class="opt-score-title"><?php _e('Set up your store', 'woosuite-core'); ?></h2>
            <div class="opt-crore-content">
                <span class="opt-score-number">
                    <?php echo esc_html( $step_completed ); ?><?php echo '/'.$all_steps; ?>
                </span>
                <div class="opt-scro-bar">
                    <div class="opt-score-barfill" style="width: <?php echo esc_attr($completed_percentage) . '%'; ?>;"></div>
                </div>
            </div>
            <ul class="opt-score-list">
                <li class="<?php echo !$license_activated_once ? 'opt-current-step' : ''; ?>">
                    <h3 class="opt-list-label"><?php _e('Activate license', 'woosuite-core'); ?></h3>
                    <?php 
                    if ( $license_activated_once ) {
                        ?> <span class="dashicons dashicons-yes"></span> <?php
                    }
                    else {
                        ?> 
                            <a class="opt-scroe-next" href="<?php echo esc_url(admin_url( 'admin.php?page=woosuite-core-license' )); ?>">
                                <span class="dashicons dashicons-arrow-right-alt"></span>
                            </a>
                        <?php
                    }
                    ?>
                </li>
                <li class="<?php echo $license_activated_once && !$addon_installed_once ? 'opt-current-step' : ''; ?>">
                    <h3 class="opt-list-label"><?php _e('Add your first plugin', 'woosuite-core'); ?></h3>
                    <?php 
                    if ( $addon_installed_once ) {
                        ?> <span class="dashicons dashicons-yes"></span> <?php
                    }
                    else if ( $license_activated_once && !$addon_installed_once ) {
                        ?> 
                            <a class="opt-scroe-next" href="<?php echo esc_url(admin_url( 'admin.php?page=woosuite-core-addons' )); ?>">
                                <span class="dashicons dashicons-arrow-right-alt"></span>
                            </a>
                        <?php
                    }
                    ?>
                    
                </li>
                <?php
                if ( ! empty( $selected_not_included_plugins ) ) {
	                foreach ( $selected_not_included_plugins as $not_included_plugin ) {
                            ?>
                        <li class="">
                            <h3 class="opt-list-label"><?php echo __( 'Install ', 'woosuite-core' ) . $not_included_plugin['name']; ?></h3>
			                <?php if ( $not_included_plugin['activated'] ) { ?>
                                <span class="dashicons dashicons-yes"></span>
			                <?php }else{ ?>
                                <a class="opt-scroe-next" target="_blank" rel="nofollow noopener noreferrer" href="<?php echo esc_url($not_included_plugin['homepage']); ?>">
                                    <span class="dashicons dashicons-arrow-right-alt"></span>
                                </a>
			                <?php } ?>

                        </li>
		                <?php
	                }
                }
                ?>
                <li class="<?php echo $addon_installed_once && !$clicked_final_step ? 'opt-current-step' : ''; ?>">
                    <h3 class="opt-list-label"><?php _e('You&apos;re all set ;)', 'woosuite-core'); ?></h3>
                    <?php 
                    if ( $clicked_final_step ) {
                        ?> <span class="dashicons dashicons-yes"></span> <?php
                    }
                    else if ( $addon_installed_once && !$clicked_final_step ) {
                        ?> 
                        <a href="<?php echo esc_url( add_query_arg( array('clicked_final' => true) ) ); ?>" class="opt-scroe-next">
                            <span class="dashicons dashicons-arrow-right-alt"></span>
                        </a>
                        <?php
                    }
                    ?>
                </li>
            </ul>
        </div>
        <!-- opt core widget -->
        <?php endif; ?>

        <!-- Changelog widget -->
        <div class="swatch-var-widget opt-new-blog-widget" style="display: none;">
            <div class="opt-new-blog-content">
                <h3 class="opt-new-blog"> <?php _e('What\'s new', 'woosuite-core'); ?></h3>
                <ul class="opt-new-blog-list">
                    <li><a href="#"><?php _e('- May 25,2021 - Buy and register a new domain through', 'woosuite-core'); ?></a></li>
                    <li><a href="#"><?php _e('- May 25,2021 - Buy and register a new domain through', 'woosuite-core'); ?></a></li>
                    <li><a href="#"><?php _e('- May 25,2021 - Buy and register a new domain through', 'woosuite-core'); ?></a></li>
                </ul>
            </div>

        </div>
        <!-- Changelog widget -->

        <!-- Changelog Version widget -->
        <?php $version_data = woosuite_core_api_version_data(); ?>
        <div class="swatch-var-widget opt-version-widget">
            <div class="opt-version-content">
                <h3 class="opt-version"> <?php _e('What\'s new', 'woosuite-core'); ?></h3>
                <?php if( $version_data ) { ?>
                <div class="acc">
                    <?php foreach ($version_data as $v_key => $v_value) { ?>
                        <div class="acc__card">
                            <div class="acc__title"><span class="badge"><?php _e('new', 'woosuite-core'); ?></span><span class="date"><?=$v_value['date']?></span><h4><?php _e($v_value['title'], 'woosuite-core'); ?> v<?=$v_value['version']?> <span class="ti-bolt spark"></span></h4></div>
                            <div class="acc__panel"><?=$v_value['changelog']?></div>
                        </div>
                    <?php } ?>
                </div>
                <?php } else { ?>
                    <p><?php _e('Update not available!', 'woosuite-core'); ?></p>
                <?php } ?>
            </div>
        </div>
        <!-- Changelog Version widget -->

        <!-- Latest blog widget -->
        <?php 
        $posts = get_option( 'woosuite_core_news' );
        if ( is_array($posts) && count($posts) ):
            
        ?>
        <div class="opt-latest-blog-widget">
            <div class="opt-latest-blog-head">
                <h2><?php echo esc_html__( 'Latest Blog Posts', 'woosuite-core' ); ?></h2>
            </div>
            <ul class="opt-latest-blog">
                <?php 
                foreach ( $posts as $post ) {
                    printf(
                        '<li><a target="_blank" href="%s">%s</a></li>',
                        $post['link'],
                        $post['title']
                    );
                }
                ?>
            </ul>
        </div>
        <!-- Latest blog widget -->
        <?php endif; ?>
    </div>
</div>
<!-- dashboard sidebar -->
<?php
}
?>