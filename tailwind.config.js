import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Http/Livewire/**/*.php',
        './node_modules/flowbite/**/*.js'
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
            },
            spacing: {
                '128': '32rem',
            }
        },
    },

    // Safelist ma sabai necessary classes add gar
    safelist: [
        // Layout
        'flex', 'hidden', 'block', 'inline', 'inline-block', 'grid',
        // Sizing
        'w-16', 'w-64', 'w-full', 'h-screen', 'h-full',
        // Spacing
        'ml-16', 'ml-64', 'p-2', 'p-4', 'p-6', 'm-2', 'm-4',
        // Responsive
        'lg:hidden', 'md:flex', 'sm:block',
        // Flexbox
        'flex-col', 'flex-row', 'flex-1', 'items-center', 'justify-between',
        // Position
        'fixed', 'relative', 'absolute', 'z-10', 'z-20', 'z-50',
        // Background
        'bg-white', 'bg-gray-50', 'bg-gray-800', 'bg-blue-700',
        // Text
        'text-white', 'text-gray-800', 'text-gray-500',
        // Border
        'border', 'border-gray-200', 'border-blue-700',
        // Shadow
        'shadow', 'shadow-sm', 'shadow-lg',
        // Transition
        'transition', 'transition-all', 'duration-300',
        // Custom
        'bg-yellow-100', 'text-wrap', 'gallery-item', 'aspect-[4/3]',
        'nepali', 'btn-primary', 'btn-secondary', 'btn-success', 
        'btn-danger', 'btn-warning', 'btn-outline', 'alert-success',
        'alert-error', 'alert-warning', 'alert-info', 'badge-success',
        'badge-error', 'badge-warning', 'badge-info', 'badge-gray'
    ],

    plugins: [forms],
};