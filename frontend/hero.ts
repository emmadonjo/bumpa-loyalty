// hero.ts
import { heroui } from "@heroui/react";
export default heroui({
    themes: {
        light: {
            colors: {
                background: "#EFEFEF",
                foreground: '#262626',
                primary: {
                    foreground: '#262626',
                    DEFAULT: '#73c356',
                    50: '#ddffee',
                    100: '#b0ffd5',
                    200: '#80ffba',
                    300: '#50ffa0',
                    400: '#27ff85',
                    500: '#16e66b',
                    600: '#09b353',
                    700: '#00803b',
                    800: '#004d22',
                    900: '#001b08'
                },
                default: {
                    foreground: '#EFEFEF',
                    DEFAULT: '#262626',
                    50: '#f2f2f2',
                    100: '#d9d9d9',
                    200: '#bfbfbf',
                    300: '#a6a6a6',
                    400: '#8c8c8c',
                    500: '#737373',
                    600: '#595959',
                    700: '#404040',
                    800: '#262626',
                    900: '#000000',
                }
            }
        }
    }
});