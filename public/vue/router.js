import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const page = path => () => import(`~/pages/${path}`)
  .then(m => m.default || m)
  .catch(() => import(`~/pages/errors/Error404`).then(m => m.default || m));

const routes = [
  {path: '/', name: 'home', component: page('index.vue')},
  {path: '/login', name: 'login', component: page('auth/login.vue')},
  {path: "/forgot", name: 'forgot', component: page(`auth/forgot.vue`)},
  {path: "/reset-password", name: 'reset-password', component: page(`auth/resetPassword`)},
  {path: '/users', name: 'users', component: page('admin/users/index.vue')},
  {path: '/roles', name: 'roles', component: page('admin/roles/index.vue')},
  {path: '/organizations', name: 'organizations', component: page('admin/organizations/index.vue')},
  {path: '/departments', name: 'departments', component: page('admin/departments/index.vue')},
  {path: '/activitylogs', name: 'activitylogs', component: page('admin/activitylogs/index.vue')},
  {path: '/request-reviews', name: 'request-reviews', component: page('admin/request-reviews/index.vue')},
  {path: '/assigend', name: 'assigned', component: page('requests/assigned.vue')},
  {path: '/closed', name: 'closedRequest', component: page('requests/closedRequest.vue')},
  {path: '/templates', name: 'templates', component: page('admin/templates/index.vue')},
  // {path: '/request-show/:id', name: 'request-show', component: page('requests/show.vue')},
  {path: '/request-show', name: 'request-show', component: page('requests/show.vue')},
  {path: '/requests', name: 'requests', component: page('requests/index.vue')},
  {path: '/settings', name: 'settings', component: page('admin/settings/index.vue')},
  {path: '/mail-templates', name: 'mail-templates', component: page('admin/mail-templates/index.vue')},
  {path: '/request-report', name: 'request-report', component: page('admin/reports/RequestReport.vue')},
  {path: "/notFound", name: 'notFound', component: page(`~/pages/errors/Error404.vue`)},
  {path: "/translations", name: 'translations', component: page(`includes/Translations.vue`)},
  {path: "*", component: page(`~/pages/errors/Error404.vue`)}
];

export function createRouter() {
  return new Router({
    routes,
    mode: 'history'
  });
}
