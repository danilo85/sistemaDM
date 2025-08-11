import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Adicione esta linha

    content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './app/Livewire/**/*.php', // Adicione esta linha
    './resources/views/livewire/**/*.blade.php', // E esta linha
            './node_modules/flowbite/**/*.js' // <-- ADICIONE ESTA LINHA

    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'none': '0',
                'sm': '0.125rem',
                DEFAULT: '0.25rem', // rounded
                'md': '0.375rem',
                'lg': '0.250rem', // <-- AQUI ESTÁ A MUDANÇA
                'xl': '0.5rem',
                '2xl': '0.75rem',
                '3xl': '1rem',
                'full': '9999px',
                },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('flowbite/plugin') // <-- ADICIONE ESTA LINHA
    ],
};
