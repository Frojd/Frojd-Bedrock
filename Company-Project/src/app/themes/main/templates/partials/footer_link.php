<?php
$link = $link ?? [];
$icon = $icon ?? '';
$linkUrl = $link['url'] ?? '';
$linkTarget = $link['target'] ?? '';
$linkTitle = $link['title'] ?? '';

$iconPath = '/acf-field-icons/follow/';

$classes = App\array_to_modifiers([
    !empty($icon) ? 'icon' : '',
], 'footer__link');
?>
<a class="<?= $classes; ?>" href="<?= $linkUrl; ?>" target="<?= $linkTarget; ?>">
    <?php if(!empty($icon)) : ?>
        <?= App\get_the_svg_icon($icon, $iconPath); ?>
        <span class="sr-only"><?= $linkTitle; ?></span>
    <?php else : ?>
        <?= $linkTitle; ?>
    <?php endif; ?>
</a>
