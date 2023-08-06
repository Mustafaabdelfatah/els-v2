<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">
          {{ $t("Organization") }}
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <form id="validationTopLabel" class="tooltip-end-top">
          <div class="mb-3 top-label">
            <input
              name="name"
              v-model="organization.name"
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
              name="description"
              v-model="organization.description"
              type="text"
              class="form-control"
              required
            />
            <span>{{ $t("Description") }}</span>
          </div>
          <div class="mb-3 top-label">
            <multiselect v-model="organization.users" tag-placeholder="Add this as new tag"
                         :placeholder="$t('Assign_admin')"
                         label="name"
                         track-by="id"
                         :options="employees"
                         :multiple="true"
                         :taggable="true"
            ></multiselect>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-outline-primary"
          data-bs-dismiss="modal"
          @click="cancel"
        >
          {{ $t("Cancel") }}
        </button>
        <button
          v-if="id"
          type="button"
          :disabled="isDisable"
          class="btn btn-primary"
          @click="edit"
        >
          {{ $t("Save_Changes") }}
        </button>
        <button
          v-else
          type="button"
          :disabled="isDisable"
          class="btn btn-primary"
          @click="add"
        >
          {{ $t("Create_New") }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
export default {
  name: "OrganizationDialog",
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      typing: false,
      ids:[],
      validationErrors: {},
      organization: {
        name: "",
        description: ""
      },
      employees: [],
    };
  },
  created() {
    this.getEmployees()
  },
  watch: {
    'id'(){
      if(this.id > 0) {
        this.getOrganization();
      }
    }
  },
  computed: {
    isDisable() {
      return this.organization.name === "";
    },
  },
  components: {
    Multiselect
  },
  methods: {
    getEmployees() {
      this.$axios.get("admin/forms/employees").then(response => {
        this.employees = response.data.data
      })
    },
    getOrganization() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/organizations/" + this.id)
        .then(response => {
          this.organization = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_organizations"), "danger");
          window.jQueryEndLoading();
        });
    },
    cancel() {
      this.organization = {};
      this.$emit('resetID');
    },
    add() {
      window.jQueryStartLoading();
      this.validationErrors = {};
      this.$axios
        .post("admin/organizations", {
          name: this.organization.name,
          description: this.organization.description,
          users: this.organization.users
        })
        .then(response => {
          window.jQueryToast(this.$t("Added_Successfully"), "success");
          this.organization = {};
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        })
        .catch(error => {
          if (typeof error.response.data.errors != "undefined")
            this.validationErrors = error.response.data.errors;
          window.jQueryToast(this.$t("Validation_Error"), "danger");
          window.jQueryEndLoading();
        });
    },
    edit() {
      window.jQueryStartLoading();
      this.validationErrors = {};
      this.$axios
        .put("admin/organizations", {
          id: this.id,
          name: this.organization.name,
          description: this.organization.description,
          users: this.organization.users
        })
        .then(() => {
          window.jQueryToast(this.$t("Updated_Successfully"), "success");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        })
        .catch(error => {
          if (typeof error.response.data.errors != "undefined")
            this.validationErrors = error.response.data.errors;
          window.jQueryToast(this.$t("Validation_Error"), "danger");
          window.jQueryEndLoading();
        });
    },
  }
};
</script>
