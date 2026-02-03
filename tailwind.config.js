import forms from "@tailwindcss/forms";
import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
                inter: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "green-primary": "oklch(62.7% 0.194 149.214)", // lime-700 - untuk background utama & tombol
                "green-secondary": "oklch(72.3% 0.219 149.579)", // lime-500 - untuk gradient & hover states
                "green-accent": "oklch(59.6% 0.145 163.225)", // green-400 - untuk aksen & highlights
                "green-dark": "#365314", // lime-800 - untuk teks & elemen gelap
                "green-light": "#bef264", // lime-300 - untuk background terang
            },
        },
    },
    plugins: [forms],
};
