<?php
$items = $items ?? [];
if(empty($items))
    return;

$title = $title ?? '';

?>
<div class="articlelist">
    <div class="articlelist__wrap">
        <?php if(!empty($title)) : ?>
            <h2 class="articlelist__title"><?= $title; ?></h2>
        <?php endif; ?>

        <ul class="articlelist__list">
            <?php foreach($items as $item) : ?>
                <?php
                    $item = (array) $item;
                    if(isset($item['post']))
                        $item = array_merge((array) $item['post'], $item);
                ?>
                <li class="articlelist__item">
                    <?php App\template_part('partials/card', $item); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>