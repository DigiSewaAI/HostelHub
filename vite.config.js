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
                'resources/css/dashboard-mobile.css', // ✅ ADDED: Mobile dashboard CSS
                'resources/css/themes/modern.css',
                'resources/css/themes/classic.css',
                
                // JS Files
                'resources/js/app.js',
                'resources/js/home.js',
                'resources/js/gallery.js',
                'resources/js/themes/classic.js',
                'resources/js/themes/modern.js'

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
                manualChunks: {
                    // ✅ ADDED: Modern theme chunk optimization
                    'modern-theme': [
                        './public/css/themes/modern.css',
                        './public/js/themes/modern.js'
                    ]
                }
            }
        },
        // Additional build optimizations for Laravel
        assetsInlineLimit: 0,
        chunkSizeWarningLimit: 1000,
        // ✅ ADDED: CSS optimization for modern theme
        css: {
            devSourcemap: true
        },
        // Prevent vendor chunk splitting issues
        commonjsOptions: {
            transformMixedEsModules: true
        },
        // ✅ ADDED: Modern theme build optimizations
        cssCodeSplit: true,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true
            }
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