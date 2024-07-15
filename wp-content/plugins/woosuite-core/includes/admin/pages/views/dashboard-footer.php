
<!-- dashboard footer -->
<div class="swatch-variation-footer">

    <?php if ( ! Woosuite_Core::is_left_footer_disabled() ) { ?>
    <div class="thd-panel thd-panel-support">
        <?php
        if ( ! empty( $footer_left = Woosuite_Core::get_whitelabel_data( 'footer-left-content', '' ) ) ) {
            echo $footer_left;
        } else {
        ?>
        <div class="thd-panel-head">
            <h3 class="thd-panel-title"> <?php _e('Support', 'woosuite-core'); ?></h3>
        </div>
        <div class="thd-panel-content">
            <div class="thd-conttent-primary">
                <div class="thd-title">
                    <?php _e('Need help? Were here for you!', 'woosuite-core'); ?> </div>

                <div class="thd-description"><?php _e('Have a question? Hit a bug? Get the help you need, when you need it from our friendly support staff.', 'woosuite-core'); ?></div>

                <div class="thd-button-wrap">
                    <a href="https://aovup.com/support/?ref=free" class="thd-button button" target="_blank"><?php _e('Get Support', 'woosuite-core'); ?> </a>
                </div>
            </div>

            <div class="thd-conttent-secondary">
                <div class="thd-title">
                    <?php _e('Priority Support', 'woosuite-core'); ?> <div class="thd-badge"><?php _e('Pro', 'woosuite-core'); ?></div>
                </div>

                <div class="thd-description"><?php _e('Want your questions answered faster? Go Pro to be first in the queue!', 'woosuite-core'); ?></div>

                <div class="thd-button-wrap">
                    <a href="https://aovup.com/support/?ref=pro" class="thd-button button" target="_blank"><?php _e('Go PRO', 'woosuite-core'); ?></a>
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
            <h3 class="thd-panel-title"><?php _e('Custom development', 'woosuite-core'); ?></h3>
        </div>
        <div class="thd-panel-content">
            <div class="thd-title">
                <?php _e('Do you need a custom plugin or edit to your site?', 'woosuite-core'); ?></div>

            <div class="thd-description"><?php _e('We have created top-class plugins for WooCommerce powering over 80,000 online stores, we can customize our plugins or create you something custom.', 'woosuite-core', 'woosuite-core'); ?></div>

            <div class="thd-button-wrap">
                <a href="https://aovup.com/services?utm_source=user-dashboard&utm_medium=link" class="thd-button button" target="_blank"><?php _e('Start a Project', 'woosuite-core'); ?></a>
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
<!-- dashboard footer -->