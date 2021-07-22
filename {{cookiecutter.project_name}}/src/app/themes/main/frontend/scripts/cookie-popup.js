import $ from 'jquery';
import store from 'store';
import expirePlugin from 'store/plugins/expire';

function initCookiePopup(el) {
    const $el = $(el);

    // Activate expire plugin on init so sessions that are expired are removed
    store.addPlugin(expirePlugin);

    $el.removeClass('js-state-initial').hide();

    const isClosed = store.get('cookie-popup-closed');
    if (isClosed === undefined) {
        $el.fadeIn();
    }

    $el.find('.js-cookie-close').click(() => {
        $el.fadeOut('fast');
        const expire = 365 * 24 * 60 * 60 * 1000; // 365 days
        store.set('cookie-popup-closed', 'true', new Date().getTime() + expire);
    });
}

export default initCookiePopup;
