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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // === PALET WARNA BARU DARI GAMBAR PANTAI ===
            colors: {
                'brand': {
                    'ivory': '#F0EFE8',       // Latar Belakang Halaman
                    'deep-teal': '#4A7C7F',   // Latar Belakang Sidebar
                    'light-teal': '#B0D0C1',  // Aksen
                    'sand-green': {
                        'light': '#e9eae4',
                        DEFAULT: '#C4C5B3',   // Warna Aksi Utama (Tombol, etc)
                        'dark': '#a2a48f'
                    },
                    'sand': '#B8A485',         // Aksen
                    'dark-stone': '#1E120B',   // Warna Teks Utama
                }
            }
        },
    },

    plugins: [forms],
};