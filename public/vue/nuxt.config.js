require('dotenv').config()
export default {
  ssr: false,
  target: 'static',
  loading: '~/components/loading.vue',
  generate: {
    routes: [
      '/',
      '/login',
      '/users',
      '/roles',
      '/organizations',
      '/departments',
      '/activitylogs',
      '/request-reviews',
      '/assigend',
      '/closed',
      '/templates',
      '/request-show',
      '/requests',
      '/settings',
      '/mail-templates',
      '/request-report',
      '/notFound',
      '/translations',
    ],
  },
  head: {
    title: 'LDS',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1, maximum-scale=1' },
      { hid: 'description', name: 'description', content: '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'preconnect', href: 'https://fonts.gstatic.com' },
      {
        rel: 'stylesheet',
        type: 'text/css',
        href: 'https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600&display=swap'
      },
      {
        rel: 'stylesheet',
        type: 'text/css',
        href: 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap'
      },
      {
        rel: 'stylesheet',
        type: 'text/css',
        href: 'https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css'
      },
      { rel: 'stylesheet', type: 'text/css', href: '/font/CS-Interface/style.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/bootstrap.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/OverlayScrollbars.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/quill.bubble.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/quill.snow.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/select2-bootstrap4.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/select2.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/datatables.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/jquery.contextMenu.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/vendor/tagify.css"' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/styles.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/main.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/styles.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/ar_styles.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/dynamic-form-builder.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/fixedColumns.dataTables.min.css' },
      { type: 'text/javascript', src: '/js/base/loader.js' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/custom.css' },
    ],
    script: [
      { type: 'text/javascript', src: '/js/vendor/jquery-3.6.4.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/bootstrap.bundle.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/OverlayScrollbars.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/autoComplete.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/clamp.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/bootstrap-submenu.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/datatables.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/mousetrap.min.js', body: true },
      { type: 'text/javascript', src: '/js/dataTables.fixedColumns.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/Chart.bundle.min.js', body: true },
      { type: 'text/javascript', src: '/js/cs/responsivetab.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/jquery.contextMenu.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/jquery.validate/jquery.validate.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/jquery.validate/additional-methods.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/select2.full.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/tagify.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/quill.min.js', body: true },
      { type: 'text/javascript', src: '/js/vendor/quill.active.js', body: true },

      { type: 'text/javascript', src: '/font/CS-Line/csicons.min.js', body: true },

      { type: 'text/javascript', src: '/js/base/helpers.js', body: true },
      { type: 'text/javascript', src: '/js/base/globals.js', body: true },
      { type: 'text/javascript', src: '/js/base/nav.js', body: true },
      { type: 'text/javascript', src: '/js/base/search.js', body: true },
      { type: 'text/javascript', src: '/js/base/settings.js', body: true },
      { type: 'text/javascript', src: '/js/base/init.js', body: true },

      { type: 'text/javascript', src: '/js/cs/scrollspy.js', body: true },
      { type: 'text/javascript', src: '/js/cs/charts.extend.js', body: true },
      { type: 'text/javascript', src: '/js/pages/dashboard.analytic.js', body: true },
      { type: 'text/javascript', src: '/js/cs/datatable.extend.js', body: true },
      { type: 'text/javascript', src: '/js/plugins/datatable.serverside.js', body: true },
      // {type: 'text/javascript', src: '/js/plugins/datatable.ajax.js', body: true},
      { type: 'text/javascript', src: '/js/plugins/datatable.editablerows.js', body: true },
      { type: 'text/javascript', src: '/js/components/navs.js', body: true },
      { type: 'text/javascript', src: '/js/plugins/contextmenu.js', body: true },
      { type: 'text/javascript', src: '/js/forms/validation.js', body: true },
      { type: 'text/javascript', src: '/js/forms/controls.select2.js', body: true },
      { type: 'text/javascript', src: '/js/cs/autocomplete.custom.js', body: true },
      { type: 'text/javascript', src: '/js/forms/controls.autocomplete.js', body: true },
      { type: 'text/javascript', src: '/js/components/toasts.js', body: true },
      { type: 'text/javascript', src: '/js/forms/controls.editor.js', body: true },
      { type: 'text/javascript', src: '/js/common.js', body: true },
      { type: 'text/javascript', src: '/js/scripts.js', body: true },
    ]
  },
  css: [],
  auth: { // auth configuration
    strategies: {
      'laravelJWT': {
        provider: 'laravel/jwt',
        url: 'auth',
        endpoints: {
          login: { url: '/login', method: 'post' },
          logout: { url: '/logout', method: 'post' },
          user: { url: '/me', method: 'get' }
        },
        token: {
          property: 'token',
          maxAge: 60 * 60
        },
        refreshToken: {
          maxAge: 20160 * 60
        },
      }
    }
  },
  plugins: [
    '~/plugins/globals',
    '~/plugins/axios',
    '~/plugins/auth',
    '~/plugins/i18n',
    '~/plugins/vee-validate',
    '~/plugins/vue-notification',
    '~/plugins/vue-gates',
    '~/plugins/ej2-vue-charts',
    '~/plugins/ej2-vue-navigations',
    '~/plugins/ej2-vue-splitbuttons',
    '~/plugins/vue-js-modal',
    { src: '@/plugins/vue-select.js', mode: 'client' }
  ],
  components: true, // Auto import components
  buildModules: [ // Modules for dev and build (recommended)
    '@nuxtjs/router',
    [ // translations configuration
      'nuxt-i18n',
      {
        vueI18nLoader: true,
        defaultLocale: 'ar',
        lazy: true,
        langDir: '~/locales',
        locales: [
          { code: 'ar', iso: 'ar-EG', file: 'ar.json', dir: 'rtl' },
          { code: 'en', iso: 'en-US', file: 'en.json', dir: 'ltr' },
        ],
      }
    ],
    '@nuxtjs/dotenv'
  ],
  modules: [  // Modules
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    'nuxt-material-design-icons',
    "vue2-editor/nuxt"
  ],
  build: {  // Build Configuration
    // Add exception
    transpile: [
      "vee-validate/dist/rules"
    ],
  }
}
