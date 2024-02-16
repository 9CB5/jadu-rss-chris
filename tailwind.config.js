/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.{js,ts}",
    "./templates/**",
    "./src/Form/**",
  ],
  theme: {
    extend: {
      colors: {
        "blue": {
          "dark": "hsl(234, 85%, 45%)",
          "white": "hsl(0, 0%, 100%)",
        },
        "gray": {
          "light": "#F8F8FB",
          "dark": "#808080"
        },
        "purple": {
          "dark": "#615DFA",
          "light": "#7F7CFE"
        },
        "green": {
          "light": "#22D0E0"
        },
        "black": "#000000",
        "white": "#FFFFFF",
        "red": "#FF0000"
      }
    },
  },
  plugins: [],
}

