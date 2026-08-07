<?php
$type = $type ?? '';
$iframe = $iframe ?? '';
$url = $url ?? '';
$modifier = $modifier ?? '';

// Known providers: nicer service label, terms link and button wording.
$providers = [
    'youtube' => ['name' => 'YouTube', 'terms' => 'https://www.youtube.com/t/terms', 'button' => __('Show video', 'sage')],
    'soundcloud' => ['name' => 'SoundCloud', 'terms' => 'https://soundcloud.com/terms-of-use', 'button' => __('Show player', 'sage')],
    'spotify' => ['name' => 'Spotify', 'terms' => 'https://www.spotify.com/legal/end-user-agreement/', 'button' => __('Show player', 'sage')],
];

$provider = $providers[$type] ?? null;
$service = $provider['name'] ?? ($type ? ucfirst($type) : __('the provider', 'sage'));
$termsLink = $provider['terms'] ?? '';
$button = $provider['button'] ?? __('Show content', 'sage');

if ($termsLink) {
    $terms = '<a href="' . $termsLink . '" target="_blank" rel="noopener">' . __('terms and conditions', 'sage') . '</a>';
    $msg = sprintf(__('The player requires acceptance of third-party cookies from %s. By showing the player you accept the %s of %s', 'sage'), $service, $terms, $service);
} else {
    $msg = sprintf(__('The player requires acceptance of third-party cookies from %s.', 'sage'), $service);
}
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
