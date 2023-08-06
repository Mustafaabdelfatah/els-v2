<template>
  <div id="root" class="h-100">
    <!-- Background Start -->
    <div class="fixed-background"></div>
    <!-- Background End -->

    <div class="container-fluid p-0 h-100 position-relative">
      <div class="row g-0 h-100">
        <!-- Left Side Start -->
        <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
          <div class="min-h-100 d-flex align-items-center">
            <div class="w-100 w-lg-75 w-xl-75 w-xxl-75 w-md-75">
              <div>
                <div class="mb-5">
                  <h1 class="display-3 text-white">{{ app_name }}</h1>
                </div>
                <p class="h6 text-white lh-1-5 mb-5">
                  {{ app_desc }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- Left Side End -->

        <!-- Right Side Start -->
        <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
          <div
            class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
            <div class="sw-lg-50 px-5">
              <div class="sh-11">
                <img :src="appLogo" v-if="appLogo" :alt="$t('APP NAME')" width="270" />
                <img src="/img/logo.png" v-else :alt="$t('APP NAME')" width="300" />
              </div>
              <div class="mb-5">
                <h2 class="cta-1 mb-0 text-primary">Password trouble?</h2>
                <h2 class="cta-1 text-primary">Renew it here!</h2>
              </div>
              <div class="mb-5">
                <p class="h6">Please use below form to reset your password.</p>
                <p class="h6">
                  If you are a member, please
                  <nuxt-link :to="{ name: 'login' }" style="color: #00979e">{{ $t('login') }}</nuxt-link>
                  .
                </p>
              </div>
              <div>
                <div v-if="validationErrors" class="error-messages" style="color:#f00">
                  <ul style="list-style: none;">
                    <li v-for="(errors, field) in validationErrors" :key="field">
                      {{ errors[0] }}
                    </li>
                  </ul>
                </div>
                <ValidationObserver slim ref="observer">
                  <form id="resetForm" class="tooltip-end-bottom" @submit.prevent="resetPassword">
                    <div class="mb-3 filled">
                      <i data-cs-icon="edit"></i>
                      <input class="form-control" id="otp" name="otp" type="text" placeholder="OTP"
                        v-model="credentials.otp" />
                    </div>
                    <div class="mb-3 filled">
                      <i data-cs-icon="lock-off"></i>
                      <input class="form-control" id="password" name="password" type="password" placeholder="Password"
                        v-model="credentials.password" />
                    </div>
                    <div class="mb-3 filled">
                      <i data-cs-icon="lock-on"></i>
                      <input class="form-control" name="password_confirmation" type="password"
                        placeholder="Verify Password" v-model="credentials.password_confirmation" />


                    </div>
                    <button type="submit" class="btn btn-lg btn-primary" @click.prevent="resetPassword">Reset
                      Password</button>
                  </form>
                </ValidationObserver>
              </div>
            </div>
          </div>
        </div>
        <!-- Right Side End -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      credentials: {
        otp: null,
        password: null,
        password_confirmation: null
      },
      validationErrors: {},
      errorMessage: '',
      // validationMessages: '',
      loading: false
    };
  },
  methods: {
    resetPassword() {
      if (this.$refs.observer.$data.flags.valid) {
        this.loading = true;
        this.validationErrors = {};
        this.errorMessage = '';

        // this.validationMessages = '';
        const { otp, password, password_confirmation } = this.credentials;
        // Remove the email property from the requestData object
        const requestData = {
          otp: otp,
          password,
          password_confirmation,
        };
        this.$axios
          .post('/auth/password/reset', requestData)
          .then(response => {
            this.validationErrors = {};

            // Password reset successful
            this.validationMessages = 'Password reset successfully.';
            this.loading = false;

            this.$router.push({
              name: 'login',

            });
          })
          .catch(error => {
            this.loading = false;

            if (error.response && error.response.data && error.response.data.errors) {
              this.validationErrors = error.response.data.errors;

            } else if (error.response && error.response.data && error.response.data.message) {
              this.errorMessage = error.response.data.message;
            } else {
              this.errorMessage = 'An error occurred while resetting your password.';
            }
          });
      }
    }
  }
};
</script>

 
