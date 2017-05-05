<?php
    $cookiePopupText = get_field('cookie_popup_text', 'option');
    if(!$cookiePopupText)
        return;
?>

<div class="cookie-popup">
    <div class="cookie-popup__message"><?= $cookiePopupText ?></div>
    <button class="cookie-popup__close-btn"><?=__('Close', 'sage') ?></button>
</div>
