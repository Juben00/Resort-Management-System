/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,php}", // Captures all .php files inside src and its subfolders
    "./components/**/*.{html,js,php}", // Captures all .php files inside components and its subfolders
    "./**/*.{html,php}", // Captures .php files inside any folder and subfolder
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#E73023',
        'primary-dark': '#B01B1B',
      }
    },
  },
  plugins: [
    // require('@tailwindcss/forms'),
  ],
};
