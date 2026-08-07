import $ from 'jquery';

// Reveals a gated embed when the visitor clicks "Show". The iframe markup is
// held in data-content until then, so no third-party request is made upfront.
const initEmbedMessage = (el) => {
    const $el = $(el);

    $el.find('.js-embed-button').on('click', () => {
        $el.html($el.data('content'));
        $el.addClass('js-state-embed-visible');
    });
};

export default initEmbedMessage;
