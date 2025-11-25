/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.jsx',
    './resources/css/**/*.css',
  ],
  theme: {
    extend: {
colors: {
        primary: {
          DEFAULT: '#4f46e5', // Replace with your primary color
          dark: '#4338ca',    // Darker shade for hover effect
        },
      },
    },
  },
  plugins: [],
}
