// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  css: ['~/assets/styles/main.scss'],
  routeRules: {
    '/api/**': {
      proxy: {
        to: `${process.env.NUXT_SERVER_URL}/api/**`,
        headers: {
          Accept: 'application/json',
        },
      },
    },
  },
});
