<div class="opt-desh-menu-wrap">
    <ul class="opt-desh-menu">
        <?php
        $menus = array(
            'dashboard',
            'addons',
            'docs',
            'support',
            'my-account',
            'activate',
            'whitelabel'
        );

        foreach ( $menus as $menu ) {
            echo Woosuite_Core::print_menu( $menu );
        }
        ?>
    </ul>
</div>