// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  css: ["~/assets/styles/main.scss"],
  routeRules: {
    "/api/**": {
      proxy: {
        to: "http://127.0.0.1:8000/api/**",
        headers: {
          Accept: "application/json",
        },
      },
    },
  },
});
