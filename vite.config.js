import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS Files
                'resources/css/app.css',
                'resources/css/hostelhub.css',
                'resources/css/public-themes.css',
                'resources/css/themes.css',
                'resources/css/gallery.css',
                'resources/css/home.css',
                'resources/css/dashboard.css',
                
                // JS Files
                'resources/js/app.js',
                'resources/js/home.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        manifest: 'manifest.json',
        outDir: 'public/build',
        rollupOptions: {
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`,
                manualChunks: undefined
            }
        },
        // Additional build optimizations for Laravel
        assetsInlineLimit: 0,
        chunkSizeWarningLimit: 1000,
        // CSS optimization
        css: {
            devSourcemap: true
        },
        // Prevent vendor chunk splitting issues
        commonjsOptions: {
            transformMixedEsModules: true
        }
    },
    // Server configuration for development
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
    // Resolve configuration
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    // Optimize dependencies
    optimizeDeps: {
        include: [
            'lodash-es',
            'axios'
        ]
    }
});