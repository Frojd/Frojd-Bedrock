// View your website at your own local server
// for example http://vite-php-setup.test

// http://localhost:3000 is serving Vite on development
// but accessing it directly will be empty
// TIP: consider changing the port for each project, see below

// IMPORTANT image urls in CSS works fine
// BUT you need to create a symlink on dev server to map this folder during dev:
// ln -s {path_to_project_source}/src/assets {path_to_public_html}/assets
// on production everything will work just fine

import {defineConfig} from 'vite'
import liveReload from 'vite-plugin-live-reload'
import legacy from '@vitejs/plugin-legacy'
import path from 'path'


// https://vitejs.dev/config/
export default defineConfig((env) => ({
    plugins: [
        legacy({ targets: ["defaults", "IE 11"] }),
        liveReload([
            // edit live reload paths according to your source code
            // for example:
            __dirname + '../**/*.php',
            __dirname + '**/*.scss',
            __dirname + 'scripts/*.js',
        ])
    ],

    // config
    base: env.command === 'build'
        ? '/app/themes/main/frontend/dist/'
        : '/',

    build: {
        // output dir for production build
        outDir: path.resolve(__dirname, './dist'),
        emptyOutDir: true,
        minify: 'terser',

        // emit manifest so PHP can find the hashed files
        manifest: true,

        rollupOptions: {
            input: [
                "./scripts/main.js",
                "./styles/main.scss",
            ]
        },
        assetsDir: "./frontend/assets",
        sourcemap: true,
    },


    server: {
        // required to load scripts from custom host
        cors: true,

        // we need a strict port to match on PHP side
        // change freely, but update on PHP to match the same port
        strictPort: true,
        port: 3000
    },
    define: {
        'process.env': {},
    },
}))
