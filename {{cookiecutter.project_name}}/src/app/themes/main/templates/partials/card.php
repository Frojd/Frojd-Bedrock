<?php
$id = $ID ?? '';
$image = $image ?? '';
$imageId = $image['ID'] ?? '';
$title = $title ?? '';
$text = $text ?? '';
$link = $link ?? [];
$url = $link['url'] ?? '';
$linkTitle = $link['title'] ?? '';
$linkTarget = $link['target'] ?? '';

$postType = get_post_type($id);
$title = $title ?: get_the_title($id);
$text = $text ?: get_the_excerpt($id);
$url = $url ?: get_permalink($id);
$imageId = $imageId ?: get_post_thumbnail_id($id);

$imageAttr = [
    // Change depending on width of each breakpoint
    'sizes' => "(max-width: 480px) 100vw, (max-width: 1024px) 50vw, 25vw",
];
$image = '';
if(!empty($imageId))
    $image = App\get_post_thumbnail_img('medium', null, $imageId, $imageAttr);

$classes = App\array_to_modifiers([
    $postType,
    empty($image) ? 'no-image' : 'has-image',
], 'card');
?>
<article class="<?= $classes; ?>">
    <a class="card__link" href="<?= $url; ?>" target="<?= $linkTarget; ?>">
        <span class="sr-only"><?= $title; ?></span>
    </a>
    <div class="card__container">
        <?php if(!empty($imageId)) : ?>
            <div class="card__image">
                <?= $image; ?>
            </div>
        <?php endif; ?>

        <div class="card__content">
            <h3 class="card__title"><?= $title; ?></h3>

            <?php if(!empty($text)) : ?>
                <p class="card__text"><?= $text; ?></p>
            <?php endif; ?>

            <?php if(!empty($linkTitle)) : ?>
                <button
                    class="card__button"
                    type="button"
                    disabled
                    aria-hidden="true"
                ><?= $linkTitle; ?></button>
            <?php endif; ?>
        </div>
    </div>
</article>