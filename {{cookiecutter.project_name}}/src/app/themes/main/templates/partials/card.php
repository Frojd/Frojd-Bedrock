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

$classes = App\array_to_modifiers([
    $postType,
    empty($imageId) ? 'no-image' : 'has-image',
], 'card');
?>
<article class="<?= $classes; ?>">
    <a class="card__link" href="<?= $url; ?>" target="<?= $linkTarget; ?>">
        <span class="sr-only"><?= $title; ?></span>
    </a>
    <div class="card__container">
        <?php if(!empty($imageId)) : ?>
            <div class="card__image">
                <?php App\the_post_thumbnail_img('thumbnail', null, $imageId); ?>
            </div>
        <?php endif; ?>

        <div class="card__content">
            <h3 class="card__title"><?= $title; ?></h3>

            <?php if(!empty($text)) : ?>
                <p class="card__text"><?= $text; ?></p>
            <?php endif; ?>

            <?php if(!empty($linkTitle) && $modifier == 'small') : ?>
                <button
                    class="card__button"
                    type="button"
                    disabled
                    aria-hidden="true"
                ><?= $linkTitle; ?></a>
            <?php endif; ?>
        </div>
    </div>
</article>