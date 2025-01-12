/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin');
const theme = require("tailwindcss/defaultTheme");
module.exports = {
    content: ["./src/**/*.{js,html,php,twig}", "./public/**/*.js"],
    theme: {
        fontFamily: {
            'serif': ["'Londrina Solid'", "ui-serif", "Georgia", "Cambria", "Times New Roman", "Times", "serif"],
            'sans': ["'Barlow'", "ui-sans-serif", "system-ui", "sans-serif", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"],
            'mono': ["'Fira Code'", "ui-monospace", "SFMono-Regular", "Menlo", "Monaco", "Consolas", "Liberation Mono", "Courier New", "monospace"]
        },
        extend: {
            animation: {
                'fade-in': 'fadeIn 1s ease-out forwards',
                gradient: 'gradientAnimation 50s ease infinite',
            },
            keyframes: {
                gradientAnimation: {
                    '0%': {'background-position': '0% 50%'},
                    '50%': {'background-position': '100% 50%'},
                    '100%': {'background-position': '0% 50%'},
                },
                fadeIn: {
                    '0%': {opacity: 0, transform: 'translateY(10px)'},
                    '100%': {opacity: 1, transform: 'translateY(0)'},
                }
            },
            backgroundImage: {
                'auth-background': "url('./assets/img/auth.jpg')",
            },
            colors: {
                'blue-violet': {
                    '200': '#C9A8F7',
                    '300': '#B182F3',
                    '400': '#9A5DF0',
                    'base': '#8338EC',
                    '600': '#6615D7',
                    '700': '#4E10A4',
                    '800': '#350B71',
                },
                'rose': {
                    '200': '#FF7AB4',
                    '300': '#FF529C',
                    '400': '#FF2985',
                    'base': '#FF006E',
                    '600': '#C70056',
                    '700': '#8F003E',
                    '800': '#570025',
                },
                'celestial-blue': {
                    '200': '#ACD9FE',
                    '300': '#84C6FE',
                    '400': '#5BB4FD',
                    'base': '#33A1FD',
                    '600': '#0287F5',
                    '700': '#0268BE',
                    '800': '#014A86',
                },
                'hot-magenta': {
                    '200': '#F2B4EF',
                    '300': '#EB91E8',
                    '400': '#E56FE1',
                    'base': '#DF4CDA',
                    '600': '#CE25C8',
                    '700': '#9E1C9A',
                    '800': '#6F146C',
                },
                'maize': {
                    '200': '#FFF9CB',
                    '300': '#FFF5A3',
                    '400': '#FFF07A',
                    'base': '#FFEC51',
                    '600': '#FFE619',
                    '700': '#E0C700',
                    '800': '#A89500'
                },
                'lavender-blush': {
                    'base': '#FAF5F7',
                    '1': '#f7edf0',
                    '2': '#f0dae0',
                    '3': '#e5bcc7'
                },
                'night': {
                    'base': '#171614',
                    '1': '#2D2B27',
                    '2': '#43403A',
                    '3': '#58554D',
                }
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        plugin(function ({theme, addUtilities}) {
            addUtilities({
                '.animated-gradient': {
                    'background': `linear-gradient(225deg, ${theme('colors.blue-violet.base')}, ${theme('colors.rose.base')}, ${theme('colors.celestial-blue.base')}, ${theme('colors.hot-magenta.base')})`,
                    'background-size': '400% 400%',
                },

                '.input': {
                    appearance: 'none',
                    lineHeight: theme('leading.thight'),
                    transition: 'all 0.3s ease-in-out',
                    borderRadius: theme('borderRadius.xl'),
                    backgroundColor: theme('colors.lavender-blush.1'),
                    borderWidth: '1px',
                    outline: 'none',
                    borderColor: theme('colors.lavender-blush.2'),
                    padding: theme('padding.2'),
                    '&:focus': {
                        outlineColor: theme('colors.celestial-blue.base'),
                        outlineWidth: '3px',
                        outlineStyle: 'solid',
                        boxShadow: theme('boxShadow.sm'),
                    },
                    '@media (prefers-color-scheme: dark)': {
                        backgroundColor: theme('colors.night.1'),
                        borderColor: theme('colors.night.2'),
                        outline: 'none',
                    },

                    '&.input-error': {
                        borderColor: theme('colors.red.500'),
                        '&:focus': {
                            outlineColor: theme('colors.red.500'),
                        },
                    }
                },

                '.input-error-text': {
                    color: theme('colors.red.500'),
                    fontSize: theme('fontSize.sm'),
                    fontStyle: 'italic',
                },

                '.btn-primary': {
                    backgroundColor: theme('colors.blue-violet.base'),
                    transition: 'all 0.3s ease-in-out',
                    padding: theme('padding.2'),
                    color: theme('colors.lavender-blush.base'),
                    borderRadius: theme('borderRadius.xl'),
                    '&:hover': {
                        backgroundColor: theme('colors.blue-violet.600'),
                    },
                    '&:active': {
                        backgroundColor: theme('colors.blue-violet.700'),
                    },
                },
                '.btn-secondary': {
                    backgroundColor: 'transparent',
                    borderWidth: '1px',
                    borderColor: theme('colors.night.base'),
                    transition: 'all 0.3s ease-in-out',
                    padding: theme('padding.2'),
                    borderRadius: theme('borderRadius.lg'),
                    '&:hover': {
                        color: theme('colors.lavender-blush.base'),
                        backgroundColor: theme('colors.night.2'),
                    },
                    '&:active': {
                        color: theme('colors.lavender-blush.base'),
                        backgroundColor: theme('colors.night.1'),
                    },
                    '@media (prefers-color-scheme: dark)': {
                        borderColor: theme('colors.lavender-blush.base'),
                        '&:hover': {
                            color: theme('colors.night.base'),
                            backgroundColor: theme('colors.lavender-blush.2'),
                        },
                        '&:active': {
                            color: theme('colors.night.base'),
                            backgroundColor: theme('colors.lavender-blush.1'),
                        },
                    }
                },
                '.btn-success': {
                    backgroundColor: theme('colors.green.500'),
                    transition: 'all 0.3s ease-in-out',
                    padding: theme('padding.2'),
                    borderRadius: theme('borderRadius.xl'),
                    '&:hover': {
                        backgroundColor: theme('colors.green.600'),
                    },
                    '&:active': {
                        backgroundColor: theme('colors.green.700'),
                    },
                },
                '.btn-danger': {
                    backgroundColor: theme('colors.red.500'),
                    transition: 'all 0.3s ease-in-out',
                    padding: theme('padding.2'),
                    borderRadius: theme('borderRadius.xl'),
                    color: theme('colors.lavender-blush.base'),
                    '&:hover': {
                        backgroundColor: theme('colors.red.600'),
                    },
                    '&:active': {
                        backgroundColor: theme('colors.red.700'),
                    },
                },
                '.btn-disabled': {
                    backgroundColor: theme('colors.gray.200'),
                    padding: theme('padding.2'),
                    borderRadius: theme('borderRadius.xl'),
                    color: theme('colors.gray.400'),
                    '@media (prefers-color-scheme: dark)': {
                        backgroundColor: theme('colors.gray.700'),
                        color: theme('colors.gray.400'),
                    }
                }
            });
        }),
    ],
}

