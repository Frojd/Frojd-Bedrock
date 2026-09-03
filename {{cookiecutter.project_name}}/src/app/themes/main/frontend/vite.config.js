// Vite builds the theme frontend into ../dist (themes/main/dist). The frontend/
// directory holds only source; the built site needs just dist/.
//
// http://localhost:{{cookiecutter.docker_frontend_port}} serves Vite in development (accessing it directly is empty).
// The port is set from the docker_frontend_port cookiecutter value below.

import {defineConfig} from 'vite'
import {viteStaticCopy} from 'vite-plugin-static-copy'
import liveReload from 'vite-plugin-live-reload'
import path from 'path'


// https://vitejs.dev/config/
export default defineConfig((env) => ({
    plugins: [
        liveReload([
            import.meta.dirname + '/../**/*.php',
            import.meta.dirname + '/**/*.scss',
            import.meta.dirname + '/scripts/*.js',
        ]),
        // Copy static assets that are referenced by path (not imported) into
        // dist so they are available from the built theme without a symlink.
        viteStaticCopy({
            targets: [
                {
                    src: 'assets/images/**/*',
                    dest: 'assets/images',
                    rename: { stripBase: 2 },
                },
            ],
        }),
    ],

    // In build the theme is served from /app/themes/main/dist/; in dev from the
    // Vite dev server root.
    base: env.command === 'build'
        ? '/app/themes/main/dist/'
        : '/',

    build: {
        outDir: path.resolve(import.meta.dirname, '../dist'),
        emptyOutDir: true,
        manifest: true,
        minify: 'terser',
        sourcemap: true,
        assetsDir: 'assets',
        // Emit every asset as a hashed file instead of inlining as data URIs,
        // so assets stay out of the CSS and are cached individually.
        assetsInlineLimit: 0,

        rollupOptions: {
            input: [
                "./scripts/main.js",
                "./styles/main.scss",
                "./styles/editor.scss",
            ],
            output: {
                // Isolate jQuery into its own chunk so it is not hoisted into
                // the entry (which WordPress enqueues with a ?ver query, causing
                // the entry to execute twice when a lazy chunk imports it back).
                manualChunks: (id) => {
                    if (id.includes('node_modules/jquery')) {
                        return 'jquery';
                    }
                },
                // Keep font filenames stable (no hash) so preloads resolve.
                assetFileNames: (assetInfo) => {
                    const ext = assetInfo.name.split('.').pop();
                    if (['woff', 'woff2', 'ttf', 'otf', 'eot'].includes(ext)) {
                        return 'assets/fonts/[name][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },

    server: {
        // required to load scripts from custom host
        cors: true,

        // listen on all interfaces (0.0.0.0), not just localhost, so the app
        // running in Docker can reach the dev server via host.docker.internal
        host: true,

        // strict port so it matches the port the PHP side expects
        strictPort: true,
        port: {{cookiecutter.docker_frontend_port}},
    },
    define: {
        'process.env': {},
    },
}))
