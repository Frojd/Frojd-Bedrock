const initScrollTo = (el, offset = 100) => {
    $(el).click(function scrollToElClick(e) {
        e.preventDefault();

        const $target = $($(this).attr('href'));
        $('html, body').animate({ scrollTop: $target.position().top + offset }, 'slow');
    });
};

export default initScrollTo;
