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
        'blue-violet': {
          'base': '#8338EC',
          '600': '#6615D7',
          '700': '#4E10A4',
          '800': '#350B71',
        },
        'rose': {
          'base': '#FF006E',
          '600': '#C70056',
          '700': '#8F003E',
          '800': '#570025',
        },
        'celestial-blue': {
          'base': '#33A1FD',
          '600': '#0287F5',
          '700': '#0268BE',
          '800': '#014A86',
        },
        'hot-magenta': {
          'base': '#DF4CDA',
          '600': '#CE25C8',
          '700': '#9E1C9A',
          '800': '#6F146C',
        },
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

