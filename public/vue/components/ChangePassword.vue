<template>
  <modal
    name="ChangePasswordDialog"
    id="ChangePasswordDialog"
    :width="400"
    :height="500"
    :clickToClose="false"
    :focusTrap="true"
    :draggable="true"
    :adaptive="true"
  >
    <div
      class="toast fade show"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div class="toast-body text-center">
        {{ $t("update_profile") }}
<!--        <button-->
<!--          type="button"-->
<!--          style="color: white"-->
<!--          @click="$modal.hide('ChangePasswordDialog')"-->
<!--          class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto"-->
<!--        >-->
<!--          <i data-cs-icon="plus"></i>-->
<!--          <span>{{ $t("Close") }}</span>-->
<!--        </button>-->
<!--        <button-->
<!--          type="button"-->
<!--          class="btn btn-secondary btn-sm"-->
<!--          data-bs-dismiss="toast"-->
<!--          @click="$modal.hide('ChangePasswordDialog')"-->
<!--          style="float: right"-->
<!--          :disabled="user.department_id === null"-->
<!--        >-->
<!--          {{ $t("Close") }}-->
<!--        </button>-->
        <div class="mt-4 pt-2">
          <form id="validationTopLabel" class="tooltip-end-top">
            <div class="mb-3">
              <div class="sw-13 d-flex user position-relative mb-3">
                <img :src="$auth.user.avatar ? $auth.user.avatar : '/img/user.jpg'" id="main" class="img-fluid rounded-xl" alt="profile">
              </div>
              <input type="file" id="avatar" ref="avatar" @change="onFileChange" v-on:change="handleFileProfile()"
                     class="form-control" accept=".png, .jpg, .jpeg">

<!--              <div class="image-input image-input-outline"-->
<!--                   :style="{'background-image': 'url(' + require('./../static/img/user.jpg') + ')'}">-->
<!--                &lt;!&ndash;begin::Preview existing avatar&ndash;&gt;-->
<!--                <div class="image-input-wrapper w-125px h-125px"-->
<!--                     :style="{ 'background-image': 'url(' + $auth.user.avatar ? $auth.user.avatar : '/img/user.jpg' + ')' }"></div>-->
<!--                &lt;!&ndash;end::Preview existing avatar&ndash;&gt;-->
<!--                &lt;!&ndash;begin::Label&ndash;&gt;-->
<!--                <label-->
<!--                  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"-->
<!--                  data-kt-image-input-action="change"-->
<!--                  data-bs-toggle="tooltip" title="Change avatar">-->
<!--&lt;!&ndash;                  <i class="bi bi-pencil-fill fs-7"></i>&ndash;&gt;-->
<!--                  <i data-cs-icon="edit"></i>-->
<!--                  &lt;!&ndash;begin::Inputs&ndash;&gt;-->
<!--                  <input type="file" name="avatar" accept=".png, .jpg, .jpeg"/>-->
<!--                  <input type="hidden" name="avatar_remove"/>-->
<!--                  &lt;!&ndash;end::Inputs&ndash;&gt;-->
<!--                </label>-->
<!--                &lt;!&ndash;end::Label&ndash;&gt;-->
<!--                &lt;!&ndash;begin::Cancel&ndash;&gt;-->
<!--&lt;!&ndash;                <span&ndash;&gt;-->
<!--&lt;!&ndash;                  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"&ndash;&gt;-->
<!--&lt;!&ndash;                  data-kt-image-input-action="cancel"&ndash;&gt;-->
<!--&lt;!&ndash;                  data-bs-toggle="tooltip" title="Cancel avatar">&ndash;&gt;-->
<!--&lt;!&ndash;                                    <i class="bi bi-x fs-2"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                                </span>&ndash;&gt;-->
<!--                &lt;!&ndash;end::Cancel&ndash;&gt;-->
<!--                &lt;!&ndash;begin::Remove&ndash;&gt;-->
<!--&lt;!&ndash;                <span&ndash;&gt;-->
<!--&lt;!&ndash;                  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"&ndash;&gt;-->
<!--&lt;!&ndash;                  data-kt-image-input-action="remove"&ndash;&gt;-->
<!--&lt;!&ndash;                  data-bs-toggle="tooltip" title="Remove avatar">&ndash;&gt;-->
<!--&lt;!&ndash;                                    <i class="bi bi-x fs-2"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                                </span>&ndash;&gt;-->
<!--                &lt;!&ndash;end::Remove&ndash;&gt;-->
<!--              </div>-->
              <!--end::Image input-->
              <!--begin::Hint-->
              <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
              <div class="error" v-if="!!imgError">
                {{ imgError }}
              </div>
            </div>
            <div class="mb-3 top-label">
              <input
                name="name"
                v-model="user.name"
                type="text"
                class="form-control"
                required
              />
              <span>{{ $t("Name") }}</span>
              <div class="error" v-if="!!validationErrors.name">
                {{ validationErrors.name[0] }}
              </div>
            </div>
            <div class="mb-3 top-label">
              <input
                name="email"
                v-model="user.email"
                type="email"
                class="form-control"
                required
              />
              <span>{{ $t("Email")}}</span>
              <div id="email-error" class="error" v-if="!!validationErrors.email">
                {{ validationErrors.email[0] }}
              </div>
            </div>
            <div class="mb-3 top-label">
              <input
                name="phone"
                type="number"
                class="form-control"
                v-model="user.phone"
              />
              <span>{{ $t('Phone')}}</span>
              <div class="error" v-if="!!validationErrors.phone">
                {{ validationErrors.phone[0] }}
              </div>
            </div>
            <div class="mb-3 top-label">
              <label class="form-label">{{ $t("Department") }}</label>
              <select class="form-control" v-model="user.department_id">
                <option
                  v-for="(department, index) in allDepartments"
                  :key="index"
                  :value="department.id"
                >{{ $i18n.locale === 'en' ? department.name_en :  department.name_ar }}
                </option
                >
              </select>
            </div>
<!--            <div class="mb-3 top-label">-->
<!--              <input-->
<!--                name="current_password"-->
<!--                v-model="current_password"-->
<!--                type="password"-->
<!--                class="form-control"-->
<!--                required-->
<!--              />-->
<!--              <span>{{ $t("Current_Password") }}</span>-->
<!--              <div class="error" v-if="!!validationErrors.current_password">-->
<!--                {{ validationErrors.current_password[0] }}-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="mb-3 top-label">-->
<!--              <input-->
<!--                name="password"-->
<!--                v-model="password"-->
<!--                type="password"-->
<!--                class="form-control"-->
<!--                required-->
<!--              />-->
<!--              <span>{{ $t("Password") }}</span>-->
<!--              <div class="error" v-if="!!validationErrors.password">-->
<!--                {{ validationErrors.password[0] }}-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="mb-3 top-label">-->
<!--              <input-->
<!--                name="password_confirmation"-->
<!--                v-model="password_confirmation"-->
<!--                type="password"-->
<!--                class="form-control"-->
<!--                required-->
<!--              />-->
<!--              <span>{{ $t("Confirm_Password") }}</span>-->
<!--            </div>-->
          </form>
          <button type="button" class="btn btn-success btn-sm" :disabled="isDisable" @click="changePassword">
            {{ $t("Change") }}
          </button>
<!--          <button-->
<!--            type="button"-->
<!--            class="btn btn-secondary btn-sm"-->
<!--            data-bs-dismiss="toast"-->
<!--            @click="$modal.hide('ChangePasswordDialog')"-->
<!--            :disabled="user.department_id === null"-->
<!--          >-->
<!--            {{ $t("Close") }}-->
<!--          </button>-->
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import { mapGetters,mapActions } from "vuex";
export default {
  name: "ChangePasswordDialog",
  data(){
    return {
      validationErrors: {},
      current_password:'',
      password:'',
      password_confirmation:'',
      avatar:'',
      url: null,
      extensions : ['png','jpg','jpeg'],
      imgError: '',
      allDepartments: [],
    }
  },
  computed: {
    ...mapGetters(["user"]),
    isDisable() {
      return this.user.name === "" ||this.user.email === "" || this.user.department_id === null
        // || this.current_password === "" || this.password === "" || this.password_confirmation === "";
    }
  },
  methods: {
    getDepartments() {
      this.$axios
        .get("admin/departments/getAllDepartments")
        .then(response => {
          this.allDepartments = response.data;
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_departments"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    handleFileProfile() {
      let self = this
      let file = this.$refs.avatar.files[0]
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function () {
        self.avatar = reader.result
      }
    },
    onFileChange(e) {
      const file = e.target.files[0];
      this.url = URL.createObjectURL(file);
      document.getElementById('main').src = this.url
    },
    // getUserData(){
    //   window.jQueryStartLoading();
    //   this.$axios
    //     .get("settings/getUserData")
    //     .then(response => {
    //       console.log((response))
    //       this.user = response.data.user;
    //       window.jQueryEndLoading();
    //     })
    //     .catch(() => {
    //       window.jQueryToast(this.$t("Failed_to_load_user"), "danger");
    //       window.jQueryDatatableReload();
    //       window.jQueryEndLoading();
    //     });
    // },
    ...mapActions(['updateUser']),
    changePassword() {
      this.validationErrors = {};
      this.imgError = '';
      const type = this.avatar.split(';')[0].split('/')[1];
      console.log(type)
        const data = {
          name: this.user.name,
          email: this.user.email,
          phone: this.user.phone,
          avatar: this.avatar,
          department_id: this.user.department_id,
          current_password: this.current_password,
          password: this.password,
          password_confirmation: this.password_confirmation,
        }
        window.jQueryStartLoading();
        this.updateUser(data).then(response => {
          window.jQueryEndLoading();
          window.jQueryToast(this.$t("Password_Updated_Successfully"), "success");
          this.current_password = ''
          this.password = ''
          this.password_confirmation = ''
          this.$emit("callback", -1);
          this.$modal.hide("ChangePasswordDialog");
          setTimeout(()=>{
            window.location.reload()
          },500)
        }).catch(error => {
          if(this.avatar) {
            if (!this.extensions.includes(type)) {
              this.imgError = 'Allowed extensions is png,jpg,jpeg'
            }
          }
          if (typeof error.response.data.errors != "undefined")
            this.validationErrors = error.response.data.errors;
          window.jQueryToast(this.$t("Validation_Error"), "danger");
          window.jQueryEndLoading();
        });

      // this.$axios
      //   .put("settings/password", {
      //     name: this.user.name,
      //     email: this.user.email,
      //     phone: this.user.phone,
      //     current_password: this.current_password,
      //     password: this.password,
      //     password_confirmation: this.password_confirmation,
      //   })
      //   .then(response => {
      //     window.jQueryToast(this.$t("Password_Updated_Successfully"), "success");
      //     this.current_password = ''
      //     this.password = ''
      //     this.password_confirmation = ''
      //     this.$emit("callback", -1);
      //     this.$modal.hide("ChangePasswordDialog");
      //   })
      //   .catch(error => {
      //     if (typeof error.response.data.errors != "undefined")
      //       this.validationErrors = error.response.data.errors;
      //     window.jQueryToast(this.$t("Validation_Error"), "danger");
      //     window.jQueryEndLoading();
      //   });
    },
  },
  created() {
    this.getDepartments();
  },
}
</script>
