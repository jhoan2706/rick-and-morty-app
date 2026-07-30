/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,php}",
    "./index.php",
    "./assets/js/app.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          100: '#EEE3FF',
          600: '#8054C7',
          700: '#5A3696',
        },
        secondary: {
          600: '#63D838',
        },
      },
    },
  },
  plugins: [],
}
