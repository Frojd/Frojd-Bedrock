<?php
    $hero = App\get_field_group('hero');

    if(!$hero)
        return;
?>

<div class="hero"<?php App\the_post_thumbnail_background('extra_large', null, $hero['image']['ID']); ?>>
    <div class="hero__container">
        <h1 class="hero__title"><?= $hero['title']; ?></h1>
        <div class="hero__content"><?= $hero['content']; ?></div>
    </div>
</div>