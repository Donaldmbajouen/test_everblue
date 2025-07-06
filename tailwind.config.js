/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/css/**/*.css",
        "./src/**/*.{js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            fontFamily: {
                outfit: ["Outfit", "sans-serif"],
            },
            colors: {
                primary: "#0B98FF",
                "primary-100": "#72bee9",
                "primary-200": "#86caec",
                "gray-100": "#6C757D",
                "gray-200": "#B9BFC3",
                "gray-300": "#EAEDF0",
                "gray-400": "#F8FAFC",
                white: "#ffffff",
                black: "#15181A",
                "green-500": "#16A34A",
            },
        },
    },
    plugins: [],
}
