/* eslint no-unused-vars: 0 */
import $ from 'jquery';

import initNotice from './notice';
import initEmbedMessage from './embed-message';
import initScrollTo from './scroll-to';
import mobileMenu from './mobile-menu';
import sideMenu from './sidemenu';

$(document).ready(() => {
    const $notice = $('.js-notice');
    if ($notice.length) {
        $notice.map((i, v) => initNotice(v));
    }

    const $embedMessage = $('.js-embed-message');
    if ($embedMessage.length) {
        $embedMessage.map((i, v) => initEmbedMessage(v));
    }

    const $jsScrollTo = $('.js-scroll-to');
    if ($jsScrollTo.length) {
        $jsScrollTo.map((i, v) => initScrollTo(v));
    }

    const $jsMobileMenu = $('.js-toggle-menu');
    if ($jsMobileMenu.length) {
        $jsMobileMenu.map((i, v) => mobileMenu(v));
    }

    const $jsSideMenu = $('.js-toggle-sidemenu');
    if ($jsSideMenu.length) {
        $jsSideMenu.map((i, v) => sideMenu(v));
    }
});
