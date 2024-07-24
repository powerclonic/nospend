/**
 * plugins/vuetify.ts
 *
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";

// Composables
import { createVuetify } from "vuetify";

const nospendTheme = {
  dark: true,
  colors: {
    primary: "#C6DE41",
    secondary: "#2D6E7E",
    accent: "#153B44",
    background: "#071C21",
    warning: "#DE9F41",
    error: "#DE4141",
    info: "#41A5DE",
    success: "#C6DE41",
  },
};

// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
export default createVuetify({
  theme: {
    themes: {
      nospendTheme,
    },
    defaultTheme: "nospendTheme",
  },
});
