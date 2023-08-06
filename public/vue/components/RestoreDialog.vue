<template>
  <modal
    name="RestoreDialog"
    :width="400"
    :height="140"
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
        {{ $t("Do_you_want_to_restore") }}
        <div class="mt-4 pt-2">
          <button type="button" class="btn btn-success btn-sm" @click="restore">
            {{ $t("Restore") }}
          </button>
          <button
            type="button"
            class="btn btn-secondary btn-sm"
            data-bs-dismiss="toast"
            @click="$modal.hide('RestoreDialog')"
          >
            {{ $t("Close")}}
          </button>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import _ from "lodash";
export default {
  name: "RestoreDialog",
  props: ["id", "url"],
  data() {
    return {
      loading: false
    };
  },
  methods: {
    restore() {
      window.jQueryStartLoading();
      if (this.id < 1 && !this.id.length) {
        window.jQueryEndLoading();
        window.jQueryToast(this.$t("Not_Found"), "danger");
        return;
      }

      let url = this.url;
      if (!_.isArray(this.id)) {
        url += "/" + this.id;
      }
      this.$axios
        .post(url, {
            ids: this.id
        })
        .then(response => {
          window.jQueryEndLoading();
          window.jQueryToast(this.$t("Restored_Successfully"), "success");
          window.jQueryDatatableReload();
          this.$emit("callback", -1);
          this.$modal.hide("RestoreDialog");
        })
        .catch(() => {
          window.jQueryToast(
            this.$t("Failed_to_restore_selected_records"),
            "warning"
          );
          window.jQueryEndLoading();
          window.jQueryDatatableReload();
        });
    }
  }
};
</script>

<style scoped></style>
