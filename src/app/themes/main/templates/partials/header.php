<header class="header">
    <div class="header__container">
        <a class="header__brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
        <nav class="header__menu">
            <?php
                if (has_nav_menu('primary_navigation')) :
                    wp_nav_menu([
                        'theme_location' => 'primary_navigation',
                        'menu_class' => 'menu menu--primary',
                        'class' => 'menu',
                        'container' => '',
                        'walker' => new App\Walkers\Nav\Nav()
                    ]);
                endif;
            ?>
        </nav>
        <nav class="header__service">
            <?php
                if (has_nav_menu('service_navigation')) :
                    wp_nav_menu([
                        'theme_location' => 'service_navigation',
                        'menu_class' => 'menu menu--service',
                        'class' => 'menu',
                        'container' => '',
                        'walker' => new App\Walkers\Nav\Nav()
                    ]);
                endif;
            ?>
        </nav>
        <a class="header__toggle"></a>
    </div>
</header>
