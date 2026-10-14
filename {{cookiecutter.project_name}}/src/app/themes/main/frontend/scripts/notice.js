import $ from 'jquery';

// Dismissible site notice. Each notice carries a unique id (data-notice-id,
// derived server-side from the notice content + last edit); dismissing it
// stores that id in localStorage so it stays hidden until the notice changes
// (a new id) — at which point it shows again.
const STORAGE_KEY = 'notice-dismissed';

function initNotice(el) {
    const $el = $(el);
    const id = el.getAttribute('data-notice-id');

    if (id && localStorage.getItem(STORAGE_KEY) === id) {
        $el.hide();
        return;
    }

    $el.find('.js-notice-close').on('click', () => {
        $el.slideUp('fast');
        if (id) {
            localStorage.setItem(STORAGE_KEY, id);
        }
    });
}

export default initNotice;
