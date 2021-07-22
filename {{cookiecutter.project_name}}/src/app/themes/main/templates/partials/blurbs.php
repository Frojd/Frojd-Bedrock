<?php

$blurbs = get_field('blurbs');

if(!$blurbs)
    return;
?>

<div class="blurbs">
    <div class="blurbs__container">
        <h2 class="blurbs__title"><?= __('Blurbs', 'sage'); ?></h2>
        <div class="blurbs__list">
            <?php foreach($blurbs as $item) : ?>
                <div class="blurbs__item">
                    <?= $item['title']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
