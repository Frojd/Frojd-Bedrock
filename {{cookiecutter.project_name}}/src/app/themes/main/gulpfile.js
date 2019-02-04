'use strict'

// Disable annoying terminal notice
process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');
require('laravel-elixir-eslint');

// Always run in production
elixir.config.production = true;
process.env.NODE_ENV = 'production';

elixir(function(mix) {
    var publicPath = elixir.config.publicPath;
    var assetsPath = elixir.config.assetsPath;

    // Add trailing slash
    publicPath += publicPath.endsWith("/") ? "" : "/";
    assetsPath += assetsPath.endsWith("/") ? "" : "/";

    mix.sass(assetsPath + 'styles/main.scss', publicPath + 'styles/main.css')

    .sass(assetsPath + 'styles/editor.scss', publicPath + 'styles/editor.css')

    .eslint([
        'scripts',
        '!scripts/libs/**'
    ])

    .browserify(assetsPath + 'scripts/main.js', publicPath + 'scripts/main.js')

    .browserSync({proxy: 'bedrock.dev:8080'})

    .copy(assetsPath+'images', publicPath+'images')
    .copy(assetsPath+'fonts', publicPath+'fonts');
});
