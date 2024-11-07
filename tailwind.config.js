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
          '1' : '#FFE619',
          '2' : '#E0C700',
          '3' : '#A89500'
        },
        'lavender-blush': '#F7EDF0',
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

