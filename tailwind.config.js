import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                'primary': '#0083ff',   // Azul principal
                'secondary': '#00dbff', // Azul claro
                'accent': '#1647f8',    // Azul escuro
                'white': '#ffffff',     // Branco
                'dark': '#0b487c',      // Azul muito escuro
            },
            fontFamily: {
                'amonos': ['"Amonos Display"', 'sans-serif'], // Fallback para sans-serif
            },
        },
    },


    plugins: [forms],
};
