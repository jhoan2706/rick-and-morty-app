/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,php}",
    "./index.php",
  ],
  theme: {
    extend: {
      // We will extend this theme according to the Figma design
      colors: {
        // Analizing the Figma, I define custom colors  
        'rm-green': '#97ce4c',
        'rm-dark': '#1a1a2e',
        'rm-dark-blue': '#16213e',
        'rm-card-bg': '#0f3460',
        'rm-alive': '#55cc44',
        'rm-dead': '#d63d2e',
        'rm-unknown': '#9e9e9e',
      },
    },
  },
  plugins: [],
}