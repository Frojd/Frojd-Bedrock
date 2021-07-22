<header class="header">
    <div class="header__container">
        <div class="header__content">
            <a class="header__brand" href="<?= esc_url(home_url('/')); ?>">
                <img
                    src="<?= App\asset_path('assets/images/logo.svg'); ?>"
                    width=""
                    height=""
                    alt="<?= __('To startpage', 'sage'); ?>"
                />
            </a>
            <button type="button" class="header__toggle js-toggle-menu" data-toggle=".header__nav">
                <span class="sr-only"><?= __('Toggle menu', 'sage'); ?></span>
            </button>
            <div class="header__nav">
                <nav class="header__menu" aria-label="<?= __('Primary navigation', 'sage'); ?>">
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
                <nav class="header__service" aria-label="<?= __('Secondary navigation', 'sage'); ?>">
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
            </div>
        </div>
    </div>
</header>
