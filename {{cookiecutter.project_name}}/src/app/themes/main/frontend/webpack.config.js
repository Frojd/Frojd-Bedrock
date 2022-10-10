/* eslint-disable no-undef */
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
const globImporter = require('node-sass-glob-importer-plus');

const path = require('path');
const isProd = process.env.NODE_ENV === '';

const styleConf = {
    name: 'sass',
    devtool: 'source-map',
    entry: {
        'main': './styles/main.scss',
        'editor': './styles/editor.scss'
    },
    output: { path: __dirname + '/../dist/styles',},
    module: {
        rules: [{
            test: /\.scss$/,
            exclude: /node_modules/,
            use: [
                { loader: MiniCssExtractPlugin.loader },
                {
                    loader: 'css-loader',
                    options: { sourceMap: true, importLoaders: true }
                },
                { loader: 'postcss-loader', options: { sourceMap: 'inline' } },
                {
                    loader: 'sass-loader',
                    options: {
                        sourceMap: true,
                        sassOptions: { importer: globImporter() },
                    },
                },
            ],
        }, {
            test: /\.*$/,
            include: /assets/,
            exclude: [/\.js$/, /\.html$/, /\.json$/, /\.scss$/,],
            type: 'asset/resource',
            // loader: 'file-loader?name=[path][name].[ext]',
        }],
    },
    plugins: [
        new MiniCssExtractPlugin({filename: '[name].css'}),
        new IgnoreEmitPlugin(['editor.js', 'main.js']),
    ],
    stats: { colors: true, hash: false, modules: false },
};

const jsConf = {
    name: 'js',
    context: __dirname + '/scripts',
    devtool: 'source-map',
    entry: {
        main: './main.js',
    },
    output: { path: __dirname + '/../dist/scripts' },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    chunks: 'all',
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendor',
                }
            }
        }
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
            }
        ]
    },
    plugins: [
        new CopyWebpackPlugin({
            patterns: [{
                from: __dirname + '/assets',
                to: __dirname + '/../dist/assets'
            }],
        }),
    ],
};

module.exports = [ jsConf, styleConf ];
