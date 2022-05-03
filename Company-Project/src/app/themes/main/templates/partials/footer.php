<?php
$footer = get_field('footer', 'option');
if(empty($footer))
    return;
?>
<footer class="footer">
    <div class="footer__wrap">
        <div class="footer__content">
            <?php foreach($footer as $col) : ?>
                <?php App\template_part('partials/footer_column', $col); ?>
            <?php endforeach; ?>
        </div>
    </div>
</footer>
