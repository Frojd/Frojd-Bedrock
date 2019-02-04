<?php

$blurbs = get_field('blurbs');

if(!$blurbs)
    return;
?>

<div class="blurbs">
    <div class="blurbs__container">
        <div class="blurbs__list">
            <?php foreach($blurbs as $item) : ?>
                <div class="blurbs__item">
                    <?php echo $item['title']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
