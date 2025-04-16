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

                // Paleta anaranjada
                orange: {
                    900: '#7C2D12',   // Extra oscuro (profundidad, fondos fuertes)
                    800: '#C2410C',   // Oscuro (tu tono actual dark)
                    700: '#EA580C',   // Intermedio oscuro
                    600: '#F97316',   // Principal (tu DEFAULT actual)
                    500: '#FB923C',   // Intermedio claro
                    400: '#FDBA74',   // Claro (tu actual light)
                    300: '#FEC89A',   // Pastel (más suave)
                    200: '#FFE8D1',   // Muy claro (tu actual soft)
                    100: '#FFF3E0',   // Extra claro (para backgrounds sutiles)
                    DEFAULT: '#F97316',
                },
            },
        },
    },

    plugins: [forms, typography],
};