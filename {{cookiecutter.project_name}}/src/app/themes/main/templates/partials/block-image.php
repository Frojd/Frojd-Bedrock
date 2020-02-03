<?php
/**
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$image = get_field('image');
?>
<div class="image">
  <figure>
    <img src="<?= $image['url'] ?>" class="image__image">
    <?= $image['caption'] ? "<figcaption>${image['caption']}</figcaption>" : '' ?>
  </figure>
</div>
