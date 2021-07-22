const presets = [
    [
        '@babel/preset-env', {
            debug: false,
            useBuiltIns: 'entry',
            corejs: '3.7',
            targets: {
                browsers: [
                    'last 2 versions',
                    'safari >= 7',
                    'ie >= 11'
                ]
            }
        }
    ],
];
const plugins = [
    '@babel/plugin-proposal-class-properties',
    '@babel/plugin-proposal-export-default-from',
]

module.exports = {
    presets,
    plugins
};