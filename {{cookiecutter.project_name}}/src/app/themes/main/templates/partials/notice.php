<?php

$notice = get_field('notice', 'option');

if (empty($notice['show']) || empty($notice['text'])) {
    return;
}

$text = $notice['text'];

// Unique id for this notice: the content plus the last time the notice was
// saved (see the acf/update_value hook in src/acf.php). Editing the notice in
// admin changes the id, so it reappears for users who dismissed the old one.
$noticeId = hash('md5', $text . get_option('notice_updated_date'));

?>
<aside class="notice js-notice" data-notice-id="<?= esc_attr($noticeId) ?>">
    <div class="notice__message"><?= $text ?></div>
    <button
        type="button"
        class="notice__close js-notice-close"
        aria-label="<?= esc_attr__('Close', 'sage') ?>"
    >
        <span><?= __('Close', 'sage') ?></span>
    </button>
</aside>
