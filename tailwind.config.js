/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,html,php,twig}"],
  theme: {
    extend: {
      backgroundImage: {
        'auth-background': "url('./assets/img/auth.jpg')",
      },
      colors: {
        'blue-violet':'#8338EC',
        'rose': '#FF006E',
        'celestial-blue': '#33A1FD',
        'hot-magenta': '#DF4CDA',
        'maize': '#FFEC51',
        'lavender-blush': {
          'base':'#F7EDF0',
          '1': '#E6C6D0',
          '2': '#D49FAF',
          '3': '#C3788F',},
        'night': {
          'base':'#171614',
          '1': '#2D2B27',
          '2': '#43403A',
          '3': '#58554D',}
      }
    },
  },
  plugins: [],
}

