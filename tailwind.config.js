/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,html,php,twig}"],
  theme: {
    extend: {
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
          '3' : '#43403A',
        }
      }
    },
  },
  plugins: [],
}

