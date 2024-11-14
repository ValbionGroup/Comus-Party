/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,html,php,twig}"],
  theme: {
    fontFamily: {
      'serif': ["'Londrina Solid'", "ui-serif", "Georgia", "Cambria", "Times New Roman", "Times", "serif"],
      'sans': ["'Barlow Condensed'", "ui-sans-serif", "system-ui", "sans-serif", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"],
      'mono': ["'Fira Code'", "ui-monospace", "SFMono-Regular", "Menlo", "Monaco", "Consolas", "Liberation Mono", "Courier New", "monospace"]
    },
    extend: {
      backgroundImage: {
        'auth-background': "url('./assets/img/auth.jpg')",
      },
      colors: {
        'blue-violet': '#8338EC',
        'rose': '#FF006E',
        'celestial-blue': '#33A1FD',
        'hot-magenta': '#DF4CDA',
        'maize': {
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
  plugins: [],
}

