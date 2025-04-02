import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#0F2A1D',    // Verde oscuro
                secondary: '#375534',  // Verde intermedio
                accent: '#6B9071',     // Verde medio
                light: '#AEC3B0',      // Verde claro
                soft: '#E3EED4',       // Verde muy claro
            },
        },
    },

    plugins: [forms, typography],
};