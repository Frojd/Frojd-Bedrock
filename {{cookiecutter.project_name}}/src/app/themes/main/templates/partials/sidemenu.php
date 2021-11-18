<?php
$menu = wp_page_menu([
    'container' => 'ul',
    'menu_class' => 'sidemenu__list',
    'class' => 'sidemenu',
    'show_content' => false,
    'walker' => new App\Walkers\Nav\Page(),
    'before' => '',
    'after' => '',
    'echo' => false,
]);
?>
<div class="sidemenu">
    <button class="sidemenu__button js-toggle-sidemenu">
        <?= __('Show side menu', 'sage'); ?>
    </button>

    <div class="sidemenu__content" style="display: none;">
        <?= $menu; ?>
    </div>
</div>
