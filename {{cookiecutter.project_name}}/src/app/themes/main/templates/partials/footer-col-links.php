<?php
if(!isset($links) || empty($links))
    return;

$iconPath = 'acf-field-icons/follow/';

foreach($links as $item) : ?>
    <?php
        $link = $item['link'];
        $text = isset($item['text']) ? $item['text'] : '';
        $class = 'footer__link';
        if(isset($item['icon'])) {
            $text = App\get_the_svg_icon($iconPath . str_replace('.svg', '', $item['icon']));
            $class .= ' footer__link--icon';
        }
    ?>
    <a class="<?= $class; ?>" href="<?= $link; ?>"><?= $text; ?></a>
<?php endforeach; ?>