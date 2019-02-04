import store from 'store';

function initCookiePopup(el) {
    jQuery(el).removeClass('js-state-initial').hide();
    
    const isClosed = store.get('cookie-popup-closed');
    if (isClosed === undefined) {
        jQuery(el).fadeIn();
    }

    jQuery(el).find('.js-cookie-close').click(() => {
        jQuery(el).fadeOut('fast');
        store.set('cookie-popup-closed', true);
    });
}

export default initCookiePopup;
