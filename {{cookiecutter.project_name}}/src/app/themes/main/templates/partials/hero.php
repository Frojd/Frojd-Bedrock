<?php
// Pass id as '' if not a single post or page
$id = $id ?? get_the_ID();
$title = $title ?? '';
$text = $text ?? '';

$hero = !empty($id) ? get_field('hero', $id) : [];
$hero = $hero['hero'] ?? [];
$imageId = $hero['image']['ID'] ?? '';
$heroTitle = $hero['title'] ?? '';
$heroText = $hero['text'] ?? '';

// Only get hero and content of current page if not an archive page
if(!empty($id))
    $imageId = $imageId ?: get_post_thumbnail_id($id);
    $title = $title ?: $heroTitle;
    $title = $title ?: get_the_title($id);
    $text = $text ?: $heroText;

$imageAttr = [
    'sizes' => '100vw',
];
$image = '';
if(!empty($imageId))
    $image = App\get_post_thumbnail_img('large', null, $imageId, $imageAttr);

$classes = \App\array_to_modifiers([
    empty($image) ? 'no-image' : 'has-image',
], 'hero');
?>
<div class="<?= $classes; ?>">
    <?php if(!empty($image)) : ?>
        <div class="hero__background">
            <?= $image; ?>
        </div>
    <?php endif; ?>
    <div class="hero__wrap">
        <h1 class="hero__title"><?= $title; ?></h1>
        <div class="hero__text"><?= $text; ?></div>
    </div>
</div>