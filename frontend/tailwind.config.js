/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{js,jsx}'],
  theme: {
    extend: {
      colors: {
        decameron: {
          DEFAULT: '#0d6efd',
          dark: '#0a58ca',
        },
      },
    },
  },
  plugins: [],
};
