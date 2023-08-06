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
          <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
            <div class="sw-lg-50 px-5">
              <div class="sh-11">
                <img :src="appLogo" v-if="appLogo" :alt="$t('APP NAME')" width="270"/>
                <img src="/img/logo.png" v-else :alt="$t('APP NAME')" width="300"/>
              </div>
              <div class="mb-5">
                <h2 class="cta-1 mb-0 text-primary">{{ $t('Password is gone?') }}</h2>
                <h2 class="cta-1 text-primary">Let's reset it!</h2>
              </div>
              <div class="mb-5">
                <p class="h6">Please enter your email to receive a link to reset your password.</p>
                <p class="h6">
                  If you are a member, please
                  <nuxt-link :to="{name:'login'}" style="color: #00979e">{{ $t('login') }}</nuxt-link>
                  .
                </p>
              </div>
              <div>
                <ValidationObserver slim ref="observer">
                  <form id="forgotPasswordForm" class="tooltip-end-bottom" @submit.prevent="sendReset">
                  <ValidationProvider rules="required" v-slot="{ errors }">
                    <i v-if="errors[0]" class="has-error text-danger">{{ errors[0] }}</i>
<!--                    <div v-else-if="validationMessages" id="forgotPasswordEmail-error" class="error">{{ validationMessages }}</div>-->
                    <i v-else-if="validationMessages" class="has-error  text-danger">{{ validationMessages }}</i>
                    <div class="mb-3 filled form-group tooltip-end-top">
                      <i data-cs-icon="email"></i>
                      <input class="form-control" :placeholder="$t('Email')"
                             v-model="email" name="email" id="email"/>

                    </div>
                  </ValidationProvider>
                  <button type="submit" class="btn btn-lg btn-primary"
                          :disabled="loading||!email"
                          :class="{
                              'disabled': loading||!email,
                              'cursor-progress': loading,
                            }"
                          @click.prevent="sendReset">Send Reset Email</button>
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
  name: "forgot",
  auth: 'guest',
  layout: 'default',
  data() {
    return {
      loading: false,
      email: '',
      validationMessages: '',
      app_name: '',
      app_desc: '',
      appLogo: null,
    }
  },
  created() {
    return this.$axios
      .get("getLoginSetting")
      .then(response => {
        this.app_name = response.data.appName ?? 'Legal System';
        this.app_desc = response.data.appDesc;
        this.appLogo = response.data.logoFile
      })
      .catch(error => {
        //
      });
  },
  methods:{
    sendReset() {
      if (this.$refs.observer.$data.flags.valid) {
        this.loading = true;
        this.validationMessages = '';
        this.$axios
          .post("auth/password/email",{
            email: this.email,
          })
          .then(response => {
            this.validationMessages = this.$t('We have emailed your password reset link.');
            this.loading = false;
          })
          .catch(e => {
            this.loading = false;
            this.validationMessages = this.$t('The given data was invalid.');
            // if (typeof e.response.data.email != 'undefined') {
            //   if (e.response.data.email === 'We can\'t find a user with that email address.') {
            //     this.validationMessages = this.$t('We can\'t find a user with that email address.');
            //   }
            // } else {
            //   this.validationMessages = this.$t('The given data was invalid.');
            // }
          })
      }
    },

  }
}
</script>

<style scoped>

</style>
