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
        'lavender-blush': '#F7EDF0',
        'night': '#171614'
      }
    },
  },
  plugins: [],
}

