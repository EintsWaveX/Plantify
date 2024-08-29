/** @type {import('tailwindcss').Config} */

module.exports = {
  content: ["./src/**/*.{html,js}"],
  theme: {
      screens: {
          screen_s: "340px",
          screen_m: "540px",
          screen_l: "768px",
          screen_xl: "1180px",
        },
        extend: {}
    },
    plugins: [],
}