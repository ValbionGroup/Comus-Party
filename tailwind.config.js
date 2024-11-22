/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin');
const theme = require("tailwindcss/defaultTheme");
module.exports = {
  content: ["./src/**/*.{js,html,php,twig}"],
  theme: {
    fontFamily: {
      'serif': ["'Londrina Solid'", "ui-serif", "Georgia", "Cambria", "Times New Roman", "Times", "serif"],
      'sans': ["'Barlow Condensed'", "ui-sans-serif", "system-ui", "sans-serif", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"],
      'mono': ["'Fira Code'", "ui-monospace", "SFMono-Regular", "Menlo", "Monaco", "Consolas", "Liberation Mono", "Courier New", "monospace"]
    },
    extend: {
      animation: {
        gradient: 'gradientAnimation 50s ease infinite',
      },
      keyframes: {
        gradientAnimation: {
          '0%': {'background-position': '0% 50%'},
          '50%': {'background-position': '100% 50%'},
          '100%': {'background-position': '0% 50%'},
        },
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
          'base' : '#FFEC51',
          '600' : '#FFE619',
          '700' : '#E0C700',
          '800' : '#A89500'
        },
        'lavender-blush': {
          'base' : '#F7EDF0',
          '1' : '#E6C6D0',
          '2' : '#D49FAF',
          '3' : '#C3788F'
        },
        'night': {
          'base' : '#171614',
          '1' : '#2D2B27',
          '2' : '#43403A',
          '3': '#58554D',
        }
      }
    },
  },
  plugins: [
    plugin(function ({theme, addUtilities}) {
      addUtilities({
        '.animated-gradient': {
          'background': `linear-gradient(270deg, ${theme('colors.blue-violet.base')}, ${theme('colors.rose.base')}, ${theme('colors.celestial-blue.base')}, ${theme('colors.hot-magenta.base')})`,
          'background-size': '400% 400%',
        },
      });
    }),
  ],
}

