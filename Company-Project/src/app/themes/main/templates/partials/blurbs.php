<?php
$items = $items ?? [];
if(empty($items))
    return;

$title = $title ?? '';

$title = $title ?: __('Blurbs', 'sage');
?>
<div class="blurbs">
    <div class="blurbs__wrap">
        <h2 class="blurbs__title"><?= $title; ?></h2>
        <ul class="blurbs__list">
            <?php foreach($items as $item) : ?>
                <?php
                    $item = (array) $item;
                    if(isset($item['post']))
                        $item = array_merge((array) $item['post'], $item);
                ?>
                <li class="blurbs__item">
                    <?php App\template_part('partials/card', $item); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
