import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],

    build: {
        outDir: 'public/build',
        manifest: true,
        assetsInlineLimit: 0,
        chunkSizeWarningLimit: 1000,
        cssCodeSplit: true,
        minify: 'terser',

        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },

    optimizeDeps: {
        include: ['axios', 'lodash-es'],
    },

    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
});
