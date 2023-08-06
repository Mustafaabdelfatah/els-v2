<template>
  <modal
    name="ChangeUserPasswordDialog"
    id="ChangeUserPasswordDialog"
    :width="400"
    :height="400"
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
        {{ $t("Change_User_Password") }}
        <div class="mt-4 pt-2">
          <form id="validationTopLabel" class="tooltip-end-top">
            <div class="mb-3 top-label">
              <input
                name="password"
                v-model="password"
                type="password"
                class="form-control"
                required
              />
              <span>{{ $t("Password") }}</span>
              <div class="error" v-if="!!validationErrors.password">
                {{ validationErrors.password[0] }}
              </div>
            </div>
            <div class="mb-3 top-label">
              <input
                name="password_confirmation"
                v-model="password_confirmation"
                type="password"
                class="form-control"
                required
              />
              <span>{{ $t("Confirm_Password") }}</span>
              <div class="error" v-if="!!validationErrors.password_confirmation">
                {{ validationErrors.password_confirmation[0] }}
              </div>
            </div>
          </form>
          <button type="button" class="btn btn-success btn-sm" @click="changeUserPassword">
            {{ $t("Change") }}
          </button>
          <button
            type="button"
            class="btn btn-secondary btn-sm"
            data-bs-dismiss="toast"
            @click="$modal.hide('ChangeUserPasswordDialog')"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import _ from "lodash";
export default {
  name: "ChangeUserPasswordDialog",
  props: ["id"],
  data() {
    return {
      loading: false,
      validationErrors: {},
      password: '',
      password_confirmation: '',
    };
  },
  methods: {
    changeUserPassword() {
      window.jQueryStartLoading();
      if (this.id < 1 && !this.id.length) {
        window.jQueryEndLoading();
        window.jQueryToast(this.$t("Not Found"), "danger");
        window.jQueryDatatableReload();
        return;
      }
      this.$axios
        .put("admin/users/change-user-password", {
          id: this.id,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .then(response => {
          this.validationErrors = {}
          this.password = ''
          this.password_confirmation = ''
          window.jQueryEndLoading();
          window.jQueryToast(this.$t("Password_Updated_Successfully"), "success");
          window.jQueryDatatableReload();
          this.$emit("callback", -1);
          this.$modal.hide("ChangeUserPasswordDialog");
        }).catch(error => {
        if (typeof error.response.data.errors != "undefined")
          this.validationErrors = error.response.data.errors;
        window.jQueryToast(this.$t("Validation_Error"), "danger");
        window.jQueryEndLoading();
      });
    }

  }
};
</script>

