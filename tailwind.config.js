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
                primary: {
                    DEFAULT: '#4F46E5', // Indigo vibrante para botões e destaques
                    hover: '#4338CA', // Tom mais escuro para hover
                },
                secondary: {
                    DEFAULT: '#06B6D4', // Ciano para elementos secundários
                    hover: '#0891B2', // Tom mais escuro para hover
                },
                accent: {
                    DEFAULT: '#7C3AED', // Roxo para variações e gradientes
                    hover: '#6D28D9', // Tom mais escuro para hover
                },
                white: '#FFFFFF', // Branco puro
                dark: {
                    DEFAULT: '#1F2937', // Cinza escuro para fundo dark mode
                    hover: '#374151', // Cinza mais claro para hover
                },
                success: {
                    DEFAULT: '#10B981', // Verde para badges de sucesso
                    hover: '#059669',
                },
                warning: {
                    DEFAULT: '#F59E0B', // Amarelo para badges de alerta
                    hover: '#D97706',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], // Mantém Inter como fonte principal
            },
            boxShadow: {
                xl: '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)', // Sombra suave para cards
                '2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)', // Sombra mais forte para destaque
            },
            borderRadius: {
                '2xl': '1rem', // Bordas mais arredondadas
                '3xl': '1.5rem', // Bordas ainda mais suaves para cards
            },
            transitionProperty: {
                'transform-scale': 'transform, scale', // Para animações de hover
            },
            keyframes: {
                pulse: {
                    '0%, 100%': { opacity: 1 },
                    '50%': { opacity: 0.5 },
                },
            },
            animation: {
                pulse: 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
        },
    },
    plugins: [forms],
};
