<?php
$type = $type ?? '';
$iframe = $iframe ?? '';
$url = $url ?? '';
$modifier = $modifier ?? '';

$button = __('Show player', 'sage');
$service = '';
$termsLink = '';
if($type == 'youtube') {
    $button = __('Show video', 'sage');
    $service = 'Youtube';
    $termsLink = 'https://www.youtube.com/t/terms';
} elseif($type == 'soundcloud') {
    $termsLink = 'https://soundcloud.com/terms-of-use';
    $service = 'Soundcloud';
}

$terms = '<a href="' . $termsLink . '" target="__blank">' . __('terms and conditions', 'sage') . '</a>';
$msg = sprintf(__('The player requires acceptance of third-party cookies from %s. By showing the player you accept the %s of %s', 'sage'), $service, $terms, $service);
$link = sprintf(__('Go to %s', 'sage'), $service);

$classes = \App\array_to_modifiers([
    $type,
    $modifier,
], 'embed-message');
?>
<div class="<?= $classes; ?> js-embed-message" data-content='<?= htmlspecialchars($iframe); ?>'>
    <div class="embed-message__content">
        <div class="embed-message__msg"><?= $msg; ?></div>

        <button class="embed-message__button js-embed-button" type="button">
            <?= $button; ?>
        </button>

        <a class="embed-message__link" href="<?= $url; ?>"><?= $link; ?></a>
    </div>
</div>
