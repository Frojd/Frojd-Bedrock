import $ from 'jquery';

const MobileMenu = (el) => {
    const $el = $(el);
    console.log($el);
    $el.click(function (e) {
        e.preventDefault();

        console.log($el.data('toggle'));

        $('body').toggleClass('js-state-menu-open');
        $el.toggleClass('js-state-menu-open');

        if($el.data('toggle') !== undefined) {
            $($el.data('toggle')).toggleClass('js-state-menu-open');
        }
    });
};

export default MobileMenu;
