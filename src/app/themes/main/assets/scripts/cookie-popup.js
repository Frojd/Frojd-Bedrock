import store from 'store';

function initCookiePopup(el) {
    const isClosed = store.get('cookie-popup-closed');
    if (isClosed) {
        jQuery(el).hide();
    }

    jQuery(el).find('.cookie-popup__close-btn').click(() => {
        jQuery(el).fadeOut('fast');
        store.set('cookie-popup-closed', true);
    });
}

export default initCookiePopup;
