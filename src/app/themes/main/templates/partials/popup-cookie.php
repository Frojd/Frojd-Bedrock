<?php
    $cookiePopupText = get_field('cookie_popup_text', 'option');
    if(!$cookiePopupText)
        return;
?>

<div class="popup popup--cookie js-cookie-popup js-state-initial" onload="console.log('woot')">
    <div class="popup__message"><?= $cookiePopupText ?></div>
    <button class="popup__close js-cookie-close"><?=__('Close', 'sage') ?></button>
</div>
