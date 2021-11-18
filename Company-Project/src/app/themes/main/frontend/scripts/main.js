/* eslint no-unused-vars: 0 */
import $ from 'jquery';

import initCookiePopup from './cookie-popup';
import initScrollTo from './scroll-to';
import mobileMenu from './mobile-menu';
import sideMenu from './sidemenu';

$(document).ready(() => {
    const $cookiePopup = $('.js-cookie-popup');
    if ($cookiePopup.length) {
        $cookiePopup.map((i, v) => initCookiePopup(v));
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
