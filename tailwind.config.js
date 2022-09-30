const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            gridTemplateRows: {
                // Simple 8 row grid
                8: "repeat(8, minmax(0, 1fr))",

                // Complex site-specific row configuration
                layout: "200px minmax(900px, 1fr) 100px",
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
