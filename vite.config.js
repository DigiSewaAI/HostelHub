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
    // ✅ css.devSourcemap यहाँ राखियो (root level)
    css: {
        devSourcemap: true,
    },
    build: {
        manifest: 'manifest.json', // Optional, plugin already does this
        outDir: 'public/build',
        rollupOptions: {
            output: {
                // ✅ [hash] हटाइएको थियो, तर cache busting को लागि [hash] रहन दिनु राम्रो
                // यदि तपाईंलाई नाम नियन्त्रण गर्नै पर्छ भने entryFileNames, chunkFileNames, assetFileNames मा [hash] राख्नुहोस्।
                // तर सामान्यतया laravel-vite-plugin को डिफल्ट नै ठीक हुन्छ। त्यसैले यो भाग हटाउन सकिन्छ।
                // यदि राख्नै पर्छ भने:
                entryFileNames: `assets/[name]-[hash].js`,
                chunkFileNames: `assets/[name]-[hash].js`,
                assetFileNames: `assets/[name]-[hash].[ext]`,
                manualChunks: (id) => {
                    // ✅ सही manualChunks: source फाइलहरूमा आधारित
                    if (id.includes('/resources/css/themes/modern.css') || 
                        id.includes('/resources/js/themes/modern.js')) {
                        return 'modern-theme';
                    }
                    // तपाईं यहाँ अन्य चंकिङ पनि थप्न सक्नुहुन्छ
                },
            },
        },
        // Additional build optimizations for Laravel
        assetsInlineLimit: 0,
        chunkSizeWarningLimit: 1000,
        // ✅ cssCodeSplit build भित्र नै ठीक छ
        cssCodeSplit: true,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },
    // Server configuration for development
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true, // यदि तपाईं WSL वा Docker मा हुनुहुन्छ भने आवश्यक
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
            'axios',
        ],
    },
});