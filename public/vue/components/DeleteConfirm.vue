<template>
  <modal
    name="DeleteConfirm"
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
        {{ $t("Are_you_sure_that_you_want_to_delete") }}
        <div class="mt-4 pt-2">
          <button type="button" class="btn btn-danger btn-sm" @click="remove">
            {{ $t("Delete") }}
          </button>
          <button
            type="button"
            class="btn btn-secondary btn-sm"
            data-bs-dismiss="toast"
            @click="$modal.hide('DeleteConfirm')"
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
  name: "DeleteConfirm",
  // props: {
  //   id: [],
  //   url: ''
  // },
  props: ["id", "url"],
  data() {
    return {
      loading: false
    };
  },
  methods: {
    remove() {
      window.jQueryStartLoading();
      if (this.id < 1 && !this.id.length) {
        window.jQueryEndLoading();
        window.jQueryToast(this.$t("Not_Found"), "danger");
        window.jQueryDatatableReload();
        return;
      }

      let url = this.url;
      if (!_.isArray(this.id)) {
        url += "/" + this.id;
      }

      this.$axios
        .delete(url, {
          params: {
            ids: this.id
          }
        })
        .then(response => {
          if(response.data.message === "false")
          {
            window.jQueryEndLoading();
            window.jQueryToast(this.$t("Can't delete selected records"), "danger");
            window.jQueryDatatableReload();
            this.$emit("callback", -1);
            this.$modal.hide("DeleteConfirm");
          }
          else{
            window.jQueryEndLoading();
            window.jQueryToast(this.$t("Deleted_Successfully"), "success");
            window.jQueryDatatableReload();
            this.$emit("callback", -1);
            this.$modal.hide("DeleteConfirm");
          }
        })
        .catch(() => {
          window.jQueryToast(
            this.$t("Failed_to_delete_selected_records"),
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
