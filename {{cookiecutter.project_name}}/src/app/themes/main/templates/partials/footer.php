<?php
    $footer = get_field('footer', 'option');
    if(!$footer)
        return;
?>

<footer class="footer">
    <div class="footer__container">
        <div class="footer__list">
            <?php foreach($footer as $col) : ?>
                <div class="footer__column">

                    <?php if(!empty($col['title'])) : ?>
                        <h2 class="footer__title"><?= $col['title']; ?></h2>
                    <?php endif; ?>

                    <?php if(isset($col['links'])) : ?>

                        <div class="footer__links"><?php
                            App\template_part('partials/footer-col-links', array(
                                'links' => $col['links']
                            ));
                        ?></div>

                    <?php elseif(isset($col['content'])) : ?>

                        <div class="footer__content"><?= apply_filters('the_content', $col['content']); ?></div>

                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</footer>
