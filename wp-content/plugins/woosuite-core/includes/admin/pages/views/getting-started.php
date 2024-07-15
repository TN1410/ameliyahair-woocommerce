<div class="wizard-wrapper">
    <div class="control-exit">
        <span class="dashicons dashicons-exit"></span>
    </div>
    <form action="" id="wizard">
        <div class="form-header">
            <img class="wizard-header-img" src="<?php echo WOOSUITE_CORE_BASE_URL; ?>assets/images/wslogo.png">
        </div>
        <!-- SECTION 1 -->
        <h2></h2>
        <section>
            <div class="inner">
                <div class="form-content">
                    <h3><?php _e('Which market do you serve?' ,'woosuite-core'); ?></h3>
                    <p><?php _e('This let us tailor the setup process' ,'woosuite-core'); ?></p>
                    <div class="form-row">
                        <div class="button">
                            <input type="radio" name="market" value="b2b" checked id="market-b2b">
                            <label class="btn btn-default" for="market-b2b"><strong><?php _e('B2B Only' ,'woosuite-core'); ?></strong></label>
                        </div>
                        <div class="button">
                            <input type="radio" name="market" value="b2b-b2c" id="market-b2b-b2c">
                            <label class="btn btn-default" for="market-b2b-b2c"><strong><?php _e('B2B and B2C' ,'woosuite-core'); ?></strong></label>
                        </div>
                    </div>
                </div>
        </section>

        <!-- SECTION 2 -->
        <h2></h2>
        <section>
            <div class="inner">
                <div class="form-content">
                    <h3><?php _e('What function do you want to enable?' ,'woosuite-core'); ?></h3>
                    <div class="form-row available-functions">
						<?php
						if ( ! empty( $available_plugins ) ):
							foreach ( $available_plugins as $plugin_slug => $plugin_data ):
								$is_plugin_activated = is_plugin_active( $plugin_data['file_path'] );
								$additional_data = array();
								if ( $is_plugin_activated ) {
									$additional_data[] = 'activated';
								}
								if ( !$plugin_data['included'] ) {
									$additional_data[] = 'not included';
								}
								?>
                                <div class="button <?php echo $is_plugin_activated ? 'disabled' : ''; ?>"
                                     data-type="<?php echo htmlspecialchars( json_encode( $plugin_data['type'] ), ENT_QUOTES, 'UTF-8' ); ?>">
                                    <input class="enabled_functions <?php echo $plugin_data['included'] ? '' : 'not-included'; ?>"
                                           type="checkbox"
                                           name="enabled_functions"
                                           value="<?php echo $plugin_slug; ?>"
                                           id="enabled_functions-<?php echo $plugin_slug; ?>"
                                           data-nonce="<?php echo wp_create_nonce( 'updates' ); ?>"
                                           data-activate-nonce="<?php echo wp_create_nonce( 'woo-activate-plugin_' . $plugin_data['file_path'] ); ?>"
                                           data-path="<?php echo $plugin_data['file_path']; ?>"
                                           data-name="<?php echo $plugin_data['name']; ?>"
                                           data-homepage="<?php echo $plugin_data['homepage']; ?>">
                                    <label class="btn btn-default"
									       <?php if ( ! $is_plugin_activated ): ?>for="enabled_functions-<?php echo $plugin_slug; ?>"<?php endif; ?>>
                                        <strong>
											<?php echo $plugin_data['name']; ?>
											<?php if ( ! empty( $additional_data ) ): ?>
                                                <i>(<?php echo implode( ', ', $additional_data ); ?>)</i>
											<?php endif; ?>
                                        </strong>
                                    </label>
                                </div>
							<?php
							endforeach;
						endif;
						?>
                    </div>
                </div>
        </section>

        <!-- SECTION 3 -->
        <h2></h2>
        <section>
            <div class="inner">
                <div class="form-content">
                    <h3><?php _e('Thank you :)' ,'woosuite-core'); ?></h3>
                    <p>
	                    <?php _e('To the right of your screen, you will see a checklist<br>to guide you through the setup process.' ,'woosuite-core'); ?>
                    </p>
                    <div class="form-row">
                        <img class="clapping-finish"
                             src="<?php echo WOOSUITE_CORE_BASE_URL; ?>assets/images/clapping.png">
                    </div>
                </div>
            </div>
        </section>
        <!-- SECTION 3 -->
        <h2></h2>
        <section>
            <div class="inner">
                <div class="form-content">
                    <div class="circle-installing c100 p0 green">
                        <span>0%</span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div>
                    <div class="plugin-install-status">
                        <p><?php _e('Installing selected plugins' ,'woosuite-core'); ?></p>
                    </div>
                </div>
            </div>
        </section>
    </form>
</div>