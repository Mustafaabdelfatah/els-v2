<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">
          {{ $t("Department") }}
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
              v-model="department.name_en"
              type="text"
              class="form-control"
              required
            />
            <span>{{ $t("Name_en") }}</span>
            <div class="error" v-if="!!validationErrors.name_en">
              {{ validationErrors.name_en[0] }}
            </div>
          </div>
          <div class="mb-3 top-label">
            <input
              name="name"
              v-model="department.name_ar"
              type="text"
              class="form-control"
              required
            />
            <span>{{ $t("Name_ar") }}</span>
            <div class="error" v-if="!!validationErrors.name_ar">
              {{ validationErrors.name_ar[0] }}
            </div>
          </div>
          <div class="mb-3 top-label">
            <input
              name="description"
              v-model="department.description"
              type="text"
              class="form-control"
              required
            />
            <span>{{ $t("Description") }}</span>
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
export default {
  name: "DepartmentDialog",
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      typing: false,
      validationErrors: {},
      department: {
        name_en: "",
        name_ar: "",
        description: ""
      },
    };
  },
  // created() {
  //   if(this.id > 0){
  //     this.getOrganization();
  //   }
  // },
  watch: {
    'id'() {
      if (this.id > 0) {
        this.getDepartment();
      }
    }
  },
  computed: {
    isDisable() {
      return this.department.name === "";
    }
  },
  methods: {
    getDepartment() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/departments/" + this.id)
        .then(response => {
          this.department = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_departments"), "danger");
          window.jQueryEndLoading();
        });
    },
    cancel() {
      this.department = {};
      this.$emit('resetID');
    },
    add() {
      window.jQueryStartLoading();
      this.validationErrors = {};
      this.$axios
        .post("admin/departments", {
          name_en: this.department.name_en,
          name_ar: this.department.name_ar,
          description: this.department.description,
        })
        .then(response => {
          window.jQueryToast(this.$t("Added_Successfully"), "success");
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
        .put("admin/departments", {
          id: this.id,
          name_en: this.department.name_en,
          name_ar: this.department.name_ar,
          description: this.department.description,
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
    }
  }
};
</script>
