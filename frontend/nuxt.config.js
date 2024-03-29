export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,

  target: 'static',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'frontend',
    htmlAttrs: {
      lang: 'en',
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/assets/css/style'
  ],

  router: {
    middleware: [
      'clearValidationErrors'
    ]
  },

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    './plugins/mixins/validation',
    './plugins/mixins/user',
    './plugins/axios',
    './plugins/vuedraggable.js',
    './plugins/element-ui.js',
    './plugins/echo.js',
    './plugins/v-mask.js',
    { src: '~/plugins/vue-datetime-picker', ssr: false },
  ],

  env: {
    baseUrl: process.env.BASE_URL || 'https://api.zh-bel.tk/api/' ,
    pusherKey: process.env.PUSHER_APP_KEY,
    pusherCluster: process.env.PUSHER_APP_CLUSTER,
  },

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    // '@nuxtjs/eslint-module',
    // https://go.nuxtjs.dev/stylelint
    // '@nuxtjs/stylelint-module',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/tailwindcss',
    '@nuxtjs/laravel-echo',
    '@nuxtjs/dotenv',
    'vue2-editor/nuxt'
  ],

  echo: {
    //
  },

  auth: {
    strategies: {
      local: {
        token: {
          property: 'token',
          required: true,
          type: 'Bearer'
        },
        user: {
          property: 'data',
          autoFetch: true
        },
        endpoints: {
          login: { url: 'auth/login', method: 'post', propertyName: 'token', redirect: 'index' },
          logout: { url: 'auth/logout', method: 'get' },
          refresh: { url: 'auth/refresh', method: 'post' },
          user: { url: '/me', method: 'get', propertyName: 'data' },
        },
      },
    },
    redirect: {
      login: '/auth/login',
      // logout: '/',
      home: '/'
    },
    watchLoggedIn: true,
    rewriteRedirects: true
  },

  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    // baseURL: '/',
    baseUrl: process.env.API_URL,
    //credentials: true,
    //proxy: true,
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {},

  vue: {
    config: {
      devtools: true,
      productionTip: false,
    },
  },

  server: {
    host: '0.0.0.0',
    port: 8080,
  }
}
