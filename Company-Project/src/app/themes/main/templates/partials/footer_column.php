<?php
$title = $title ?? '';
$links = $links ?? [];
$text = $text ?? '';
$layout = $acf_fc_layout ?? '';

$classes = App\array_to_modifiers([
    $layout,
], 'footer__column');
?>
<div class="<?= $classes; ?>">
    <?php if($title) : ?>
        <h2 class="footer__title"><?= $title; ?></h2>
    <?php endif; ?>

    <?php if(!empty($text)) : ?>
        <div class="footer__richtext">
            <?= apply_filters('the_content', $text); ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($links)) : ?>
        <ul class="footer__list">
            <?php foreach($links as $item) : ?>
                <li class="footer__item">
                    <?php App\template_part('partials/footer_link', $item); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>