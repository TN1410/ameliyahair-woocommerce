<?php 

/**
 * Class responsible for handling child plugins
 */
class Woosuite_Core_Child_Plugins_Manager {
    public function __construct() {
        add_action( 'woosuite_child_plugin_header', array( $this, 'child_plugin_header' ) );
        add_action( 'woosuite_child_plugin_sidebar', array( $this, 'child_plugin_sidebar' ) );
        add_action( 'woosuite_child_plugin_footer', array( $this, 'child_plugin_footer' ) );
        add_action( 'woosuite_child_plugin_video_tutorials', array( $this, 'child_plugin_video_tutorials' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'print_scripts' ) );
    }

    public function print_scripts() {
		wp_localize_script( 'woosuite-core-admin', 'woosuiteCore', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		] );

		do_action( 'woosuite_core_admin_page_scripts' );
	}


    public function child_plugin_video_tutorials( $plugin_slug ) {
        ?> 
        <!--video tutorial  -->
        <div class="swatch-variation-tutorail" style="display: none;">
            <div class="theplus-panel-row theplus-mt-50">
                <div class="theplus-panel-col theplus-panel-col-100">
                    <div class="theplus-panel-sec theplus-p-20 theplus-welcome-video">
                        <div class="theplus-sec-title">Video Tutorials</div>
                        <div class="theplus-sec-subtitle">
                            Checkout Few of our latest video tutorials
                        </div>
                        <div class="theplus-sec-border"></div>
                        <div class="theplus-panel-row theplus-panel-relative">
                            <a href="https://www.youtube.com/playlist?list=PLFRO-irWzXaLK9H5opSt88xueTnRhqvO5 " class="theplus-more-video" target="_blank"><?php _e('Our Full Playlist','woosuite-core');?></a>
                            <div class="theplus-panel-col theplus-panel-col-25">
                                <a href="https://youtu.be/HY5KlYuWP5k" class="theplus-panel-video-list" target="_blank"><img src="<?php echo WOOSUITE_CORE_BASE_URL.'assets/images/video-1.jpg';?>"></a>
                            </div>
                            <div class="theplus-panel-col theplus-panel-col-25">
                                <a href="https://youtu.be/9-8Ftlb79tI" class="theplus-panel-video-list" target="_blank"><img src="<?php echo WOOSUITE_CORE_BASE_URL.'assets/images/video-2.jpg';?>"></a>
                            </div>
                            <div class="theplus-panel-col theplus-panel-col-25">
                                <a href="https://youtu.be/Bwp3GBOlkaw" class="theplus-panel-video-list" target="_blank"><img src="<?php echo WOOSUITE_CORE_BASE_URL.'assets/images/video-3.jpg';?>"></a>
                            </div>
                            <div class="theplus-panel-col theplus-panel-col-25">
                                <a href="https://youtu.be/kl2xSnl2YqM" class="theplus-panel-video-list" target="_blank"><img src="<?php echo WOOSUITE_CORE_BASE_URL.'assets/images/video-4.jpg';?>"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--video tutorial  -->
        <?php
    }


    public function child_plugin_footer( $plugin_slug ) {
        ?>
        <!-- footer -->
        <div class="swatch-variation-footer">
            <?php if ( ! Woosuite_Core::is_left_footer_disabled() ) { ?>
            <div class="thd-panel thd-panel-support">
                <?php
                if ( ! empty( $footer_left = Woosuite_Core::get_whitelabel_data( 'footer-left-content', '' ) ) ) {
                    echo $footer_left;
                } else {
                ?>
                <div class="thd-panel-head">
                    <h3 class="thd-panel-title"> <?php _e('Support','woosuite-core');?></h3>
                </div>
                <div class="thd-panel-content">
                    <div class="thd-conttent-primary">
                        <div class="thd-title">
                            <?php _e('Need help? Were here for you!','woosuite-core');?>								
                        </div>

                        <div class="thd-description"><?php _e('Have a question? Hit a bug? Get the help you need, when you need it from our friendly support staff.','woosuite-core');?></div>

                        <div class="thd-button-wrap">
                            <a href="https://aovup.com/support/?ref=free" class="thd-button button" target="_blank"><?php _e('Get Support	','woosuite-core');?></a>
                        </div>
                    </div>

                    <div class="thd-conttent-secondary">
                        <div class="thd-title">
                            <?php _e('Priority Support','woosuite-core');?>										
                            <div class="thd-badge"><?php _e('pro','woosuite-core');?></div>
                        </div>

                        <div class="thd-description"><?php _e('Want your questions answered faster? Go Pro to be first in the queue!','woosuite-core');?></div>

                        <div class="thd-button-wrap">
                            <a href="https://aovup.com/support/?ref=pro" class="thd-button button" target="_blank"><?php _e('Go PRO','woosuite');?></a>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
            }
            if ( ! Woosuite_Core::is_right_footer_disabled() ) {
            ?>

            <div class="thd-panel thd-panel-community">
                <?php
                if ( ! empty( $footer_right = Woosuite_Core::get_whitelabel_data( 'footer-right-content', '' ) ) ) {
                    echo $footer_right;
                } else {
                ?>
                <div class="thd-panel-head">
                    <h3 class="thd-panel-title"><?php _e('Custom development','woosuite-core');?></h3>
                </div>
                <div class="thd-panel-content">
                    <div class="thd-title">
                    <?php _e('Do you need a custom plugin or edit to your site?','woosuite-core');?></div>

                    <div class="thd-description"><?php _e('We have created top-class plugins for WooCommerce powering over 80,000 online stores, we can customize our plugins or create you something custom.', 'woosuite-core');?></div>

                    <div class="thd-button-wrap">
                        <a href="https://aovup.com/services?utm_source=user-dashboard&utm_medium=link" class="thd-button button" target="_blank"><?php _e('Start a Project','woosuite-core');?></a>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
            }
            ?>
        </div>
        <!-- footer -->
        <?php
    }

    public function child_plugin_sidebar( $plugin_slug ) {
        if ( Woosuite_Core::get_whitelabel_data( 'sidebar-disabled', FALSE ) ) {
            echo Woosuite_Core::get_whitelabel_data( 'sidebar-content' );
        } else {
        ?>
        <!-- sidebar -->
        <div class="thd-main-sidebar">
            <div class="swatch-var-widget-wrap">
                <!-- widget -->
                <?php 

                $video_array = array(
                    'woo-sales-agent'                     => 'https://www.youtube.com/embed/Qff1jY6F8Fs',
                    'woosuite-shipping-and-payemnts'      => 'https://www.youtube.com/embed/KgS6ipo1l4o',
                    'woosuite-woo-show-single-variations' => 'https://www.youtube.com/embed/OKvhRX_m4xY',
                    'woosuite-min-max-quantities'         => 'https://www.youtube.com/embed/V86RK2xAmrI',
                    'woosuite-woocommerce-wholesale'      => 'https://www.youtube.com/embed/RaigQDn6BRg',
                    'woosuite-restriction-rules'          => 'https://www.youtube.com/embed/ZntqSBF987s',
                    'woosuite-woo-product-bundles'        => 'https://www.youtube.com/embed/bRusTJYLRqo',
                    'woosuite-product-table'              => 'https://www.youtube.com/embed/SPqqG-eX2B4',
                    'woosuite-pricing-discount-rules'     => 'https://www.youtube.com/embed/6rjVD_yozRM',
                    'woosuite-quick-view'                 => 'https://www.youtube.com/embed/v_BPaHMzetk',
                    'variation-swatches-for-woocommerce'  => 'https://www.youtube.com/embed/1qGusf9IfFY',
                );

                $video_url = isset ( $video_array[ $plugin_slug ] ) ? $video_array[ $plugin_slug ] : 'https://www.youtube.com/embed/ECJ8bA5dgS8';

                ?>
                <div class="swatch-var-widget">
                    <h3 class="swatch-video-title"> <?php _e('Getting Started','woosuite-core')?></h3>
                    <div class="swat-video-frame">
                    <iframe src="<?php echo $video_url; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                <!-- widget -->

                <!-- widget -->
                <?php 
                $all_plugins = woosuite_core_get_modules();
                ob_start();
                
                if ( $all_plugins ) {
                    $current_plugin = array_filter( $all_plugins, function( $item ) use ( $plugin_slug ) {
                        if ( $item->slug == $plugin_slug ) {
                            return $item;
                        }
                    } );

                    if ( $current_plugin ) {
                        sort( $current_plugin );
                        $current_plugin = $current_plugin[0];
                        $recommended_plugins = maybe_unserialize( maybe_unserialize( $current_plugin->recommended_plugins ) );

                        if ( $recommended_plugins ) {
                            // add clicks data to array
                            foreach( $all_plugins as $plugin ) {
                                foreach ( $recommended_plugins as $key => $value ) {
                                    if ( $recommended_plugins[$key]['plugin'] == $plugin->slug ) {
                                        $recommended_plugins[$key]['clicks'] = $plugin->clicks;
                                    }
                                }
                            }

	                        $keys = array();
	                        foreach ( $recommended_plugins as $recommended_plugin ) {
		                        $keys[] = isset( $recommended_plugin['clicks'] ) ? $recommended_plugin['clicks'] : '';
	                        }
                            array_multisort( $keys, SORT_DESC, $recommended_plugins );
                            $limit_plugin_to_show = 0;
                            $plugins_already_showed = get_option( 'woosuite_sidebar_'  . $current_plugin->slug, array() );

                            if ( count( $plugins_already_showed ) >= count( $recommended_plugins ) ) {
                                $plugins_already_showed = array();
                            }

                            foreach( $recommended_plugins as $recommended_plugin ) {
                                if ( ! isset( $recommended_plugin['plugin']) ) {
                                    continue;
                                }
    
                                $recommended_plugin_slug = $recommended_plugin['plugin'];
                                $plugin_data =  array_filter( $all_plugins, function( $item ) use ( $recommended_plugin_slug ) {
                                    if ( $item->slug == $recommended_plugin_slug ) {
                                        return $item;
                                    }
                                } );
    
                                if ( !$plugin_data ) {
	                                continue;
                                }

                                sort( $plugin_data );
                                $plugin_data = $module = $plugin_data[0];

                                if ( ! woosuite_check_module_active_by_path( $module->path ) && ! in_array( $plugin_data->slug, $plugins_already_showed ) ) {
                                    $limit_plugin_to_show++;
                                    if ( $limit_plugin_to_show <= 2 ) {
                                        $plugins_already_showed[] = $plugin_data->slug;
                                        $title       = ! empty( $recommended_plugin['plugin']['title'] ) ? $recommended_plugin['plugin']['title'] : $plugin_data->name;
                                        $description = ! empty( $recommended_plugin['plugin']['description'] ) ? $recommended_plugin['plugin']['description'] : $plugin_data->short_description;
                                        $link        = ! empty( $recommended_plugin['plugin']['link'] ) ? $recommended_plugin['plugin']['link'] : $plugin_data->homepage;
                                        ?>
                                        <div class="swatch-var-addon">
                                            <a href="<?php echo $link; ?>" target="_blank" rel="nofollow noopener noreferrer" class="swatch-addon-link"><h2><?php echo $title; ?></h2></a>
                                            <p><?php echo $description; ?></p>
                                            <div class="footer">
                                            <?php include plugin_dir_path(WOOSUITE_CORE_PLUGIN_FILE) . 'includes/admin/pages/views/module-footer.php'; ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    $plugins_already_showed[] = $plugin_data->slug;
                                }
                            }

                            update_option( 'woosuite_sidebar_'  . $current_plugin->slug, $plugins_already_showed );
                        }
                    }
                }

                $html = ob_get_clean();

                if ( ! empty( $html ) ) {
                    ?>
                    <div class="swatch-var-widget">
                        <h2 class="swatch-video-title"> <?php _e('Works well with..','woosuite-core')?></h2>
                        <div class="swatch-var-addon-wrap">
                            <?php echo $html; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <!-- widget -->
            </div>
        </div>
        <!-- sidebar -->
        <?php
        }
    }

    public function child_plugin_header( $plugin_slug ) {
        ?> 
        <!-- variation head -->
        <div class="variation-header-sticky">
            <div class="variation-header-wrap">
                <div class="variation-head">
                    <div class="varitaion-logo">
                    <?php
                    $logo = apply_filters( 'woosuite_core_admin_logo', WOOSUITE_CORE_BASE_URL . 'assets/images/wslogo-dash.png' );
                    ?>
                    <img src="<?php echo $logo; ?>" alt="">
                    </div>
                    <div class="variation-menu-outer">
                        <div class="variaion-munu-wrap">
                            <ul>
                                <?php 
                                $menus = array(
                                    'dashboard',
                                    'addons',
                                    'docs',
                                    'support',
                                );
                        
                                foreach ( $menus as $menu ) {
                                    echo Woosuite_Core::print_menu( $menu );
                                }
                                ?>
                            </ul>
                            <div class="variation-head-btns">
                                <a href="<?php echo esc_url( add_query_arg( null, null ) ); ?>" class="button vh-btn vh-discard-btn"><?php _e('Discard', 'woosuite-core');?></a>
                                <button name="save" class="button button-primary woosuite-save-master-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'woosuite-core' ); ?>"><?php esc_html_e( 'Save changes', 'woosuite-core' ); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}