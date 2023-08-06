<template>
  <div id="nav" class="nav-container d-flex">
    <div class="nav-content d-flex">
      <!-- Logo Start -->
      <div class="logo position-relative">
        <a onclick="window.location.href='/'" class="cursor-pointer">
          <img :src="appLogo" v-if="appLogo" alt="logo" />
          <img src="/img/logo.png" v-else alt="logo" />
        </a>
      </div>
      <!-- Logo End -->
      <!-- Language Switch Start -->
      <div class="language-switch-container">
        <button class="btn btn-empty language-button text-uppercase" @click="toggle()">
          {{ secondLocal }}
        </button>
      </div>
      <!-- Language Switch End -->
      <!-- User Menu Start -->
      <div class="user-container d-flex">
        <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false">
          <img class="profile" alt="profile" :src="$auth.user.avatar ? $auth.user.avatar : '/img/user.jpg'" />
          <div class="name">{{ $auth.user.name }}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-end user-menu wide">
          <div class="row mb-3 ms-0 me-0">
            <div class="col-12 ps-1 pe-1">
              <ul class="list-unstyled">
                <li>
                  <a href="#" @click="passwordModal">
                    <i data-cs-icon="key" class="me-2" data-cs-size="14"></i>
                    <span class="align-middle">{{ $t("update_profile") }}</span>
                  </a>
                </li>
                <li>
                  <a href="#" @click="logout">
                    <i data-cs-icon="logout" class="me-2" data-cs-size="14"></i>
                    <span class="align-middle">{{ $t("Logout") }}</span>
                  </a>
                </li>
                <li>
                  <a href="#" @click="assign">
                    <i data-cs-icon="assign" class="me-2" data-cs-size="14"></i>
                    <span class="align-middle">{{ $t("assign") }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- User Menu End -->
      <!-- Icons Menu Start -->
      <ul class="list-unstyled list-inline text-center menu-icons">
        <li class="list-inline-item">
          <a href="#" id="colorButton">
            <i data-cs-icon="light-on" class="light" data-cs-size="18"></i>
            <i data-cs-icon="light-off" class="dark" data-cs-size="18"></i>
          </a>
        </li>
        <li class="list-inline-item">
          <a href="#" data-bs-toggle="dropdown" data-bs-target="#notifications" aria-haspopup="true" aria-expanded="false"
            class="notification-button">
            <div class="position-relative d-inline-flex">
              <i data-cs-icon="bell" data-cs-size="18"></i>
              <span class="position-absolute notification-dot rounded-xl"></span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end wide notification-dropdown scroll-out" id="notifications">
            <div class="scroll">
              <ul class="list-unstyled border-last-none">
                <!-- Notifications -->
                <li class="mb-3 pb-3 border-bottom border-separator-light d-flex"
                  v-for="(notification, notificationIndex) in user.notifications" :key="notificationIndex">
                  <img :src="(notification.data.from_user_avatar) ? notification.data.from_user_avatar : '/img/user.jpg'"
                    class="me-3 sw-4 sh-4 rounded-xl align-self-center" alt="..." />
                  <div class="align-self-center">
                    <strong><a href="#">{{ notification.data.from_user_name }}</a></strong>
                    <br>
                    <span><small>{{ notification.data.message }}</small></span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </li>
      </ul>
      <!-- Menu End -->
      <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">
          <li v-if="hasPermission.user || hasPermission.role">
            <a href="#users" data-href="#">
              <i data-cs-icon="home" class="icon" data-cs-size="18"></i>
              <span class="label">{{ $t("Users") }}</span>
            </a>
            <ul id="users">
              <li>
                <nuxt-link :to="{ name: 'users' }" v-if="hasPermission.user">
                  <span class="label">{{ $t("Users") }}</span>
                </nuxt-link>
              </li>
              <li>
                <nuxt-link :to="{ name: 'roles' }" v-if="hasPermission.role">
                  <span class="label">{{ $t("Roles") }}</span>
                </nuxt-link>
              </li>
              <li v-if="user.roles.includes('Root') || user.roles.includes('Admin')">
                <nuxt-link :to="{ name: 'activitylogs' }">
                  <span class="label">{{ $t("Activity_logs") }}</span>
                </nuxt-link>
              </li>
            </ul>
          </li>
          <li>
            <a href="#requests" data-href="#">
              <i data-cs-icon="screen" class="icon" data-cs-size="18"></i>
              <span class="label">{{ $t("Requests") }}</span>
            </a>
            <ul id="requests">
              <li>
                <nuxt-link :to="{ name: 'templates' }" v-if="hasPermission.template">
                  <span class="label">{{ $t("Templates") }}</span>
                </nuxt-link>
              </li>
              <li>
                <nuxt-link :to="{ name: 'requests' }">
                  <span class="label">{{ $t("Requests") }}</span>
                </nuxt-link>
              </li>
              <li>
                <nuxt-link :to="{ name: 'request-reviews' }" v-if="hasPermission.review || hasPermission.assign">
                  <span class="label">{{ $t("Request_Reviews") }}</span>
                </nuxt-link>
              </li>
              <li v-if="hasPermission.track || user.roles.includes('Root') || user.roles.includes('Admin')">
                <a href="#assigned" data-href="Pages.Authentication.html">
                  <span class="label">{{ $t("Assigned") }}</span>
                </a>
                <ul id="assigned">
                  <li>
                    <nuxt-link :to="{ name: 'assigned' }"
                      v-if="hasPermission.track || user.roles.includes('Root') || user.roles.includes('Admin')">
                      <span class="label">{{ $t("request_under_process") }}</span>
                    </nuxt-link>
                  </li>
                  <li>
                    <nuxt-link :to="{ name: 'closedRequest' }">
                      <span class="label">{{ $t("closed_request") }}</span>
                    </nuxt-link>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li v-if="user.roles.includes('Root')">
            <a href="#settings" data-href="#">
              <i data-cs-icon="screen" class="icon" data-cs-size="18"></i>
              <span class="label">{{ $t("Settings") }}</span>
            </a>
            <ul id="settings">
              <li>
                <nuxt-link :to="{ name: 'settings' }">
                  <span class="label">{{ $t("Settings") }}</span>
                </nuxt-link>
              </li>
              <li>
                <nuxt-link :to="{ name: 'organizations' }">
                  <span class="label">{{ $t("Organizations") }}</span>
                </nuxt-link>
              </li>
              <li>
                <nuxt-link :to="{ name: 'departments' }">
                  <span class="label">{{ $t("Departments") }}</span>
                </nuxt-link>
              </li>
              <li>
                <nuxt-link :to="{ name: 'translations' }">
                  <span class="label">{{ $t("translations") }}</span>
                </nuxt-link>
              </li>
            </ul>
          </li>
          <!--          v-if="hasPermission.report"-->
          <li v-if="user.roles.includes('Root')">
            <a href="#reports" data-href="#">
              <i data-cs-icon="screen" class="icon" data-cs-size="18"></i>
              <span class="label">{{ $t("reports") }}</span>
            </a>
            <ul id="reports">
              <li>
                <nuxt-link :to="{ name: 'request-report' }">
                  <span class="label">{{ $t("request_report") }}</span>
                </nuxt-link>
              </li>
            </ul>
          </li>
        </ul>
      </div>

      <!-- Mobile Buttons Start -->
      <div class="mobile-buttons-container">
        <!-- Scrollspy Mobile Button Start -->
        <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
          <i data-cs-icon="menu-dropdown"></i>
        </a>
        <!-- Scrollspy Mobile Button End -->

        <!-- Scrollspy Mobile Dropdown Start -->
        <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
        <!-- Scrollspy Mobile Dropdown End -->

        <!-- Menu Button Start -->
        <a href="#" id="mobileMenuButton" class="menu-button">
          <i data-cs-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
      </div>
      <!-- Mobile Buttons End -->
    </div>
    <div class="nav-shadow"></div>

    <change-password-dialog />
    <modal name="assignDialog" id="assignDialog" :width="400" :height="500" :clickToClose="false" :focusTrap="true"
      :draggable="true" :adaptive="true">
      <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-center">
          {{ $t("update_profile") }}
          <!--        </button>-->
          <div class="mt-4 pt-2">
            <form id="validationTopLabel" class="tooltip-end-top">
              <div class="mb-3">
                <div class="sw-13 d-flex user position-relative mb-3">
                  <img :src="$auth.user.avatar ? $auth.user.avatar : '/img/user.jpg'" id="main"
                    class="img-fluid rounded-xl" alt="profile">
                </div>


                <!--begin::Hint-->
                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>

              </div>


            </form>
            <button type="button" class="btn btn-success btn-sm" :disabled="isDisable" @click="changePassword">
              {{ $t("Change") }}
            </button>

          </div>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
import _ from "lodash";
import ChangePasswordDialog from "./ChangePassword";
export default {
  components: { ChangePasswordDialog },
  data() {
    return {
      isActive: false,
      imgError: false,
      templates: [],
      appLogo: null,
      appUrl: '',
      showModal: false,
      current_password: '',
      password: '',
      password_confirmation: '',
    }
  },
  // watch:{
  //   "list":function(){
  //     this.listTemp()
  //   }
  // },
  created() {
    this.listTemp()
    this.getSettingData()
  },
  computed: {
    ...mapGetters(["loggedIn", "user", "roles", "userPermissions", "userOrganization", "userTemplates"]),
    hasPermission() {
      let permissionAllowed = {
        track: false,
        user: false,
        template: false,
        role: false,
        report: false,
        review: false,
        assign: false,
      }
      this.userPermissions.filter((permission) => {
        if (permission.split('-')[0] == 'tracking') {
          permissionAllowed.track = true
        }
        if (permission.split('-')[0] == 'assign') {
          permissionAllowed.assign = true
        }
        if (permission == 'list-templates') {
          permissionAllowed.template = true
        }
        if (permission == 'list-roles') {
          permissionAllowed.role = true
        }
        if (permission == 'list-users') {
          permissionAllowed.user = true
        }
        if (permission.split('-')[1] == 'report') {
          permissionAllowed.report = true
        }
        if (permission == 'review') {
          permissionAllowed.review = true
        }
      })
      this.userOrganization.filter((Organization) => {
        if (Organization)
          permissionAllowed.review = true
      })
      this.userTemplates.filter((Template) => {
        if (Template)
          permissionAllowed.review = true
      })
      return permissionAllowed
    },
    secondLocal() {
      return this.$i18n.locale == "ar" ? "en" : "ar"
    }
  },
  methods: {
    getImageUrl(name) {
      return this.appUrl + "/" + name.replace("public", "storage");
    },
    assign() {
      this.$modal.show("assignDialog")
    },
    imageUrlAlt() {
      this.imgError = true;
    },
    getSettingData() {
      window.jQueryStartLoading()
      return this.$axios.get("admin/settings")
        .then(response => {
          this.appLogo = response.data.logoFile
          this.appUrl = response.data.appUrl
          window.jQueryEndLoading();
        }).catch(error => {
          console.log(error)
          window.jQueryEndLoading()
        })
    },
    passwordModal() {
      this.$modal.show("ChangePasswordDialog")
    },
    toggle() {
      return (this.secondLocal == 'ar' ? this.changeLocale('ar') : this.changeLocale('en'))
    },
    changeLocale(locale) {
      this.$store.commit("SET_LOCALE", locale);
      this.$store.$i18n.setLocale(locale);
      location.reload();
    },
    logout() {
      this.$axios.post('/auth/logout').then(response => {
        this.$router.push("/login")
      }).catch(error => {
        location.reload();
      });
    },
    listTemp() {
    },
  }
};
</script>
