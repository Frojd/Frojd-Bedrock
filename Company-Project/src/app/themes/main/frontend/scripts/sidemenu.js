import $ from 'jquery';

const SideMenu = (el) => {
    const $el = $(el);
    $el.click(function (e) {
        e.preventDefault();

        $el.toggleClass('js-state-sidemenu-open');
        $el.siblings().slideToggle();
    });
};

export default SideMenu;
