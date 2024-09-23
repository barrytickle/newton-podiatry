const fs = require("fs");
const colors = require("tailwindcss/colors");

// Read safelist.txt file and split the contents into an array of class names
const safelist = fs
  .readFileSync("./safelist.txt", "utf-8")
  .toString()
  .split("\n")
  .map((line) => line.trim()) // Remove extra whitespace
  .filter((line) => line.length > 0); // Remove empty lines

export default {
  content: ["./theme/views/**/*.twig", "./theme/blocks/**/*.twig"],
  purge: {
    safelist: safelist,
  },
  theme: {
    colors: {
      ...colors, // Include all default Tailwind colors
      "brand-blue": {
        light: "#3376b5", // Light shade
        DEFAULT: "#0054a2", // Base color
        dark: "#004382", // Dark shade
      },
      "brand-blue-light": "#e6efff",
      "brand-grey-light": "#fafbfd",
      "brand-grey": "#d2d2d2",
      primary: {
        DEFAULT: "#222222",
      },
    },
    extend: {
      fontFamily: {
        sans: ["Inter", "sans-serif"], // Set Inter as the default sans font
      },
    },
  },
  plugins: [require("@tailwindcss/typography")],
};
