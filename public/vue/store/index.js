import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios)

export const state = () => ({
  locale: localStorage.getItem('locale') || 'ar',
  templates: []
});

export const strict = false

export const getters = {
  loggedIn(state) {
    return state.auth.loggedIn
  },
  user(state) {
    if(state.auth.user)
      return state.auth.user
  },

  roles(state){
    let roles = []
    state.auth.user.roles.filter((role) => {
      roles.push(role)
  })
    return roles
 },

  userPermissions(state){
    let permissions = []
    state.auth.user.permissions.filter((permission) => {
      permissions.push(permission.name)
  })
    return permissions
  },
  userOrganization(state) {
    let organizations = []
    state.auth.user.organizations.filter((organization) => {
      organizations.push(organization.is_admin)
    })
    return organizations
  },
  userTemplates(state){
   let templates = []
    state.auth.user.templates.filter((template) => {
      templates.push(template.id)
    })
    return templates
  },
  organizations(state) {
    let organizations = []
    state.auth.user.organizations.filter((organization) => {
      organizations.push(organization.organization)
    })
    return organizations
  },
  templates(state){
    return state.templates
  },
  locale(state) {
    return state.local;
  },
};

export const actions = {
    getTemplates({commit}){
      axios({
        url: 'admin/templates/all',
        method: "POST",
      }).then(response => {
        response.data
        commit("TEMPLATE_DATA",response.data)
      })
    },
    updateUser({commit},data){
      return this.$axios.put('settings/password', data)
        .then(response => {
          // console.log(response)
          // if (response.data) {
            // commit("UPDATE_USER",response.data)
            // localStorage.setItem('user', JSON.stringify(state.auth.user));
          // }
      })
    },
  }

export const mutations = {
  SET_LOCALE (state, locale) {
    state.locale = locale;
    localStorage.setItem('locale', locale);
  },
  TEMPLATE_DATA(state, templates)
  {
    state.templates = templates
  }
};
