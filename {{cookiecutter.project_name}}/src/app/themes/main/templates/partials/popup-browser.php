<?php
    $browserPopupText = get_field('browser_popup_text', 'option');
    if(!$browserPopupText)
        return;
?>

<div class="popup popup--browser">
    <div class="popup__message"><?= $browserPopupText ?></div>
</div>
