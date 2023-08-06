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
                <!--                <div class="mb-5">-->
                <!--                  <a class="btn btn-lg btn-outline-white" href="index.html">Learn More</a>-->
                <!--                </div>-->
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
              <div class="">
                <!--                <img src="/img/logo.png" :alt="$t('APP NAME')" width="300">-->
                <img :src="appLogo" v-if="appLogo" :alt="$t('APP NAME')" width="270" />
                <img src="/img/logo.png" v-else :alt="$t('APP NAME')" width="300" />
                <a href="#">
                  <div class=""></div>
                </a>
              </div>
              <div class="mb-5">
                <h2 class="cta-1 mb-0 text-primary">{{ $t('Welcome') }},</h2>
                <h2 class="cta-1 text-primary">{{ $t('let_s_get_started') }}!</h2>
              </div>
              <div class="mb-5">
                <p class="h6">{{ $t('Please_use_your_credentials_to_login') }}.</p>
                <!--                <p class="h6">-->
                <!--                  If you are not a member, please-->
                <!--                  <a href="Pages.Authentication.Register.html">register</a>-->
                <!--                  .-->
                <!--                </p>-->
              </div>
              <div>
                <ValidationObserver slim ref="observer">
                  <form id="loginForm" class="tooltip-end-bottom" autocomplete="off" @submit.prevent="login">
                    <ValidationProvider rules="required" v-slot="{ errors }">
                      <i v-if="errors[0]" class="has-error text-danger">{{ errors[0] }}</i>
                      <i v-else-if="validationMessages" class="has-error  text-danger">{{ validationMessages }}</i>
                      <div class="mb-3 filled form-group tooltip-end-top">
                        <i data-cs-icon="email"></i>
                        <input class="form-control" :placeholder="$t('Email_or_Username')" v-model="credentials.email"
                          name="email" id="email" />
                      </div>
                    </ValidationProvider>

                    <ValidationProvider rules="required" v-slot="{ errors }">
                      <i class="has-error  text-danger">{{ errors[0] }}</i>
                      <div class="mb-3 filled form-group tooltip-end-top">
                        <i data-cs-icon="lock-off"></i>
                        <input class="form-control pe-7" v-model="credentials.password" type="password"
                          :placeholder="$t('Password')" name="password" id="password" />
                        <nuxt-link class="text-small position-absolute t-3 e-3" style="color: #00979e"
                          :to="{ name: 'forgot' }">{{ $t('Forgot') }}?</nuxt-link>
                      </div>
                    </ValidationProvider>

                    <button type="submit" class="btn btn-lg btn-primary"
                      :disabled="loading || !credentials.email || !credentials.password" :class="{
                        'disabled': loading || !credentials.email || !credentials.password,
                        'cursor-progress': loading,
                      }" @click.prevent="login">
                      {{ $t('Login') }}
                    </button>
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
  auth: 'guest',
  layout: 'default',
  data() {
    return {
      loading: false,
      credentials: {
        email: '',
        password: ''
      },
      validationMessages: '',
      app_name: '',
      app_desc: '',
      appLogo: null,
    }
  },
  methods: {
    async login() {
      if (this.$refs.observer.$data.flags.valid) {
        this.loading = true;
        // this.credentials.password = this.hashPw(this.credentials.password);
        this.validationMessages = '';
        await this.$auth.loginWith('laravelJWT', {
          data: { 'email': this.credentials.email, "password": this.credentials.password }
          // data: btoa(JSON.stringify(this.credentials))
        })
          .then(() => {
            this.$notify({
              group: 'general',
              type: 'success',
              title: 'Success',
              text: 'Welcome !',
            });
            this.loading = false;
            this.$router.push({ path: "/" });
          }).then(() => {
            setTimeout(() => {
              if (this.$auth.$state.user.email.split('@')[1] === 'ncms.sa'
                && this.$auth.$state.user.department_id === null) {
                this.$modal.show("ChangePasswordDialog")
              }
            }, 500);
            // setTimeout(() => {
            //   if(this.$auth.$state.user.user_reset_password != null && this.$auth.$state.user.id != this.$auth.$state.user.user_reset_password){
            //     this.$modal.show("ChangePasswordDialog")
            //   }
            // }, 500);
          })
          .catch(e => {
            this.loading = false;
            if (typeof e.response.data.errors != 'undefined') {
              if (e.response.data.errors.email === 'Your account is locked,please contact admin') {
                this.validationMessages = this.$t('Your account is locked, Please contact Administrator.');
              }
              else if (e.response.data.errors.email[0] == 'Authentication failed')
                this.validationMessages = this.$t('The given data was invalid.');
            } else {
              this.validationMessages = this.$t('The given data was invalid.');
            }

            // this.validationMessages = this.$t('Please contact Administrator.');
            // if(e.response.data.errors.email[0] == 'Authentication failed')
            // {
            //   this.loading = false;
            //   this.validationMessages = this.$t('Please contact Administrator.');
            // }
            // this.validationMessages = this.$t('The given data was invalid.');
            //if(e.response.data.message == 'The given data was invalid.'){
            //  this.validationMessages = e.response.data.message;
            //}
            //else{
            //  alert('Failed to login')
            //}
          })
      }
    },


    genRandomNum(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    },

    hashPw(password) {
      if (!password) return;
      let randomNum1 = this.genRandomNum(1000, 9000);
      let randomNum2 = this.genRandomNum(2000, 9000);
      let encrypedPw = `$2y$10$${randomNum1}$/${this.encryptPw()}/$${randomNum2}`;
      return encrypedPw;
    },
    encryptPw() {

      let newPw = '';
      for (let i = 0; i < this.credentials.password.length; i++) {
        let randomstr = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, i + 1 * 4);
        randomstr = randomstr.substring(0, 3) + "--?" + randomstr.substring(4, randomstr.length);
        newPw += this.credentials.password[i] + `%-${randomstr}-%`;
      }
      return newPw

    }



  },
  created() {
    // console.log("process",process.env)
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
  }
}
</script>
