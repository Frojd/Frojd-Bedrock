import js from '@eslint/js';

export default [
    {
        ignores: [
            'dist/**',
            'node_modules/**',
        ],
    },
    js.configs.recommended,
    {
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: {
                window: 'readonly',
                document: 'readonly',
                location: 'readonly',
                navigator: 'readonly',
                sessionStorage: 'readonly',
                localStorage: 'readonly',
                fetch: 'readonly',
                console: 'readonly',
                setTimeout: 'readonly',
                clearTimeout: 'readonly',
                setInterval: 'readonly',
                clearInterval: 'readonly',
                wp: 'readonly',
                jQuery: 'readonly',
                $: 'readonly',
            },
        },
        rules: {
            'no-unused-vars': ['warn', { args: 'none' }],
            'no-empty': ['warn', { allowEmptyCatch: true }],
            'no-useless-escape': 'warn',
            'no-redeclare': 'warn',
            'no-undef': 'warn',
        },
    },
];
