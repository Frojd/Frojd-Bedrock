module.exports = {
    'extends': 'stylelint-config-standard',
    'rules': {
        'no-empty-source': null,
        'indentation': 4,
        'number-leading-zero': null,
        'string-quotes': 'single',
        'selector-list-comma-newline-after': null,
        'block-no-empty': null,
        'no-descending-specificity': null,
        'value-keyword-case': [
            'lower',
            {
                'ignoreKeywords': ['S', 'SL', 'M', 'L', 'XL', 'wp-S', 'wp-M'],
            }
        ],
        'at-rule-no-unknown': [
            true,
            {
                'ignoreAtRules': [
                    'extend',
                    'at-root',
                    'elseif',
                    'debug',
                    'warn',
                    'error',
                    'if',
                    'else',
                    'for',
                    'each',
                    'while',
                    'mixin',
                    'include',
                    'content',
                    'return',
                    'function',
                    'tailwind',
                    'apply',
                    'responsive',
                    'variants',
                    'screen',
                ],
            },
        ],
    },
};
