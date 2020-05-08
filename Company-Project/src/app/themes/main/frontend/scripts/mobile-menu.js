import $ from 'jquery';

const MobileMenu = (el) => {
    let $el = $(el);
    $el.click(function (e) {
        e.preventDefault();

        $('body').toggleClass('js-state-menu-open');
        $el.toggleClass('js-state-menu-open');
        
        if($el.data('toggle') !== undefined)
            $($el.data('toggle')).toggleClass('js-state-menu-open');
    });
};

export default MobileMenu;
