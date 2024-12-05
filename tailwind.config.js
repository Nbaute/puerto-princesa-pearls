import colors from "tailwindcss/colors";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import daisyui from "daisyui";

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/filament/**/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php",
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.teal,
                success: colors.green,
                warning: colors.yellow,
                primary: {
                    50: "#FFFDFA",
                    100: "#FFFAF0",
                    200: "#FEF5E1",
                    300: "#FEF2D7",
                    400: "#FDEDC8",
                    500: "#FDE7B9",
                    600: "#FBCB65",
                    700: "#F8AF11",
                    800: "#A97505",
                    900: "#543A02",
                    950: "#2D1F01",
                },
                accent: {
                    50: "#FFFFFF",
                    100: "#FFFDFA",
                    200: "#FFFDFA",
                    300: "#FFFCF5",
                    400: "#FFFAF0",
                    500: "#FFF9EC",
                    600: "#FFDA8A",
                    700: "#FFBB29",
                    800: "#C78800",
                    900: "#614200",
                    950: "#332300",
                },
            },
        },
    },
    daisyui: {
        themes: [
            {
                light: {
                    ...require("daisyui/src/theming/themes")["light"],
                    primary: "#FDE7B9",
                    "primary-focus": "#FDE7B9",
                    primary600: "#FBCB65",
                    accent: "#332300",

                    secondary: "#FFBB29",
                },
            },
        ],
    },
    plugins: [forms, typography, daisyui],
};
