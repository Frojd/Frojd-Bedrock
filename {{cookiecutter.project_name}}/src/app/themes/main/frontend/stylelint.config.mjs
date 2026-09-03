export default {
    extends: 'stylelint-config-standard-scss',
    rules: {
        'no-empty-source': null,
        'block-no-empty': null,
        'no-descending-specificity': null,
        'value-keyword-case': [
            'lower',
            {
                ignoreKeywords: ['S', 'SL', 'M', 'ML', 'L', 'XL', 'XXL', 'wp-S', 'wp-M'],
            },
        ],
        'color-function-notation': 'legacy',
        'media-feature-range-notation': 'prefix',
        'scss/no-global-function-names': null,
        'no-duplicate-selectors': null,
        'selector-id-pattern': null,
        'selector-class-pattern': null,
        'keyframes-name-pattern': null,
        'scss/percent-placeholder-pattern': null,
        'scss/at-mixin-pattern': null,
        'alpha-value-notation': null,
        'scss/dollar-variable-pattern': null,
        'scss/dollar-variable-empty-line-before': null,
        'scss/at-extend-no-missing-placeholder': null,
        // Keep intentional cross-browser pairs (e.g. -webkit-appearance next to appearance)
        'property-no-vendor-prefix': null,
        // Keep readable chained :not() (e.g. :not(:disabled):not(.is-disabled))
        'selector-not-notation': null,
        'declaration-block-no-redundant-longhand-properties': [
            true,
            {
                ignoreShorthands: ['inset'],
            },
        ],
        'at-rule-no-unknown': [
            true,
            {
                ignoreAtRules: [
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
                    'use',
                    'forward',
                ],
            },
        ],
    },
};
