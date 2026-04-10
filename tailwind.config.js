import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Lato', ...defaultTheme.fontFamily.sans],
                heading: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                body: ['Lato', 'sans-serif'],
                accent: ['"Great Vibes"', 'cursive'],
            },
        },
    },

    plugins: [forms],
};
