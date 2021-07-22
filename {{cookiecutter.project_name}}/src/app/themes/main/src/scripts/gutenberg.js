const { __ } = wp.i18n;
const { InnerBlocks } = wp.blockEditor;

wp.data.dispatch('core/edit-post').removeEditorPanel('discussion-panel');
wp.data.dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-category');

wp.domReady(() => {
    wp.blocks.unregisterBlockStyle('core/quote', ['large', 'default']);
    wp.blocks.unregisterBlockStyle('core/table', ['regular', 'stripes']);
    wp.blocks.unregisterBlockStyle('core/image', ['default', 'rounded']);
    wp.blocks.unregisterBlockStyle('core/button', ['default', 'fill', 'outline', 'squared']);

    const allowedEmbedBlocks = ['vimeo', 'youtube'];
    wp.blocks.getBlockVariations('core/embed').forEach(function (blockVariation) {
        if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
            wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
        }
    });
});

wp.blocks.registerBlockType('sage/preamble', {
    category: 'common',
    title: 'Ingress',
    icon: 'media-text',
    attributes: {
        content: {
            type: 'string',
            source: 'html',
            selector: 'p',
        },
    },
    supports: {
        alignWide: false,
        customClassName: false,
        defaultStylePicker: false,
        html: false,
        reusable: false,
    },
    transforms: {
        from: [
            {
                type: 'block',
                blocks: ['core/paragraph', 'core/heading', 'core/quote'],
                transform: (attributes) => wp.blocks.createBlock('sage/preamble', {
                    content: attributes.content,
                }),
            },
        ],
        to: [
            {
                type: 'block',
                blocks: ['core/paragraph'],
                transform: (attributes) => wp.blocks.createBlock('core/paragraph', {
                    content: attributes.content,
                }),
            },
            {
                type: 'block',
                blocks: ['core/heading'],
                transform: (attributes) => wp.blocks.createBlock('core/heading', {
                    content: attributes.content,
                }),
            },
            {
                type: 'block',
                blocks: ['core/quote'],
                transform: (attributes) => wp.blocks.createBlock('core/quote', {
                    content: attributes.content,
                }),
            },
        ],
    },
    edit: function( props ) {
        return wp.element.createElement(wp.blockEditor.RichText, {
            tagName: 'p',
            className: props.className,
            value: props.attributes.content,
            allowedFormats: [],
            placeholder: 'Lägg till ingress...',
            onChange: function( content ) {
                props.setAttributes( { content: content } );
            },
        });
    },
    save: function( props ) {
        return wp.element.createElement( wp.blockEditor.RichText.Content, {
            tagName: 'p', value: props.attributes.content,
        } );
    },
});
