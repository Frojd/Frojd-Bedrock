<?php
    $text = get_field('cookie_popup_text', 'option');
    $text = $text ?: '--Cookie text--';
?>

<div class="popup popup--cookie js-cookie-popup js-state-initial">
    <div class="popup__message"><?= $text ?></div>
    <button type="button" class="popup__close js-cookie-close"><?=__('Close', 'sage') ?></button>
</div>
