import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                nepali: ['Noto Sans Devanagari', 'sans-serif']
            },
            colors: {
                'yellow-100': '#fffbeb',
                'primary': '#1e3a8a',
                'primary-dark': '#1e40af', 
                'secondary': '#0ea5e9',
                'secondary-dark': '#0284c7',
                'accent': '#10b981',
                'accent-dark': '#059669'
            },
            boxShadow: {
                'glow': '0 8px 30px rgba(14, 165, 233, 0.25)',
            },
            borderRadius: {
                'xl': '0.75rem',
            }
        },
    },

    safelist: [
        'bg-yellow-100',
        'text-wrap', 
        'gallery-item',
        'aspect-[4/3]',
        'nepali',
        'btn-primary',
        'btn-secondary',
        'btn-success', 
        'btn-danger',
        'btn-warning',
        'btn-outline',
        'alert-success',
        'alert-error',
        'alert-warning',
        'alert-info',
        'badge-success',
        'badge-error',
        'badge-warning',
        'badge-info',
        'badge-gray'
    ],

    plugins: [forms],
};