<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">
          {{ $t("User") }}
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          @click="cancel"
        ></button>
      </div>
      <div class="modal-body">
        <form id="validationTopLabel" class="tooltip-end-top">
          <div class="mb-3 top-label autocomplete-container">
            <input
              name="name"
              v-model="user.name"
              type="text"
              class="form-control"
              required
              autocomplete="off"
              id="autocompleteInput"
              @input="user.guid = ''"
              :placeholder="$t('Search')"
            />
            <span>{{ $t("Name") }}</span>
            <div class="error" v-if="!!validationErrors.name">
              {{ validationErrors.name[0] }}
            </div>
            <div
              class="autocomplete-results dropdown-menu"
              id="autocompleteResults"
            ></div>
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
              :placeholder="$t('Phone')"
            />
            <span>{{ $t('Phone')}}</span>
            <div class="error" v-if="!!validationErrors.phone">
              {{ validationErrors.phone[0] }}
            </div>
          </div>
<!--          <div class="mb-3 top-label">-->
<!--            <label class="form-label">Type</label>-->
<!--            <select class="form-control" v-model="user.type">-->
<!--              <option value="admin">Admin</option>-->
<!--              <option value="employee">Employee</option>-->
<!--            </select>-->
<!--            <span>Type</span>-->
<!--            <div class="error" v-if="!!validationErrors.type">-->
<!--              {{ validationErrors.type[0] }}-->
<!--            </div>-->
<!--          </div>-->
          <div class="mb-3 top-label">
            <label class="form-label">{{ $t("organization") }}</label>
            <select class="form-control" v-model="user.organization_id" required>
              <option
                v-for="(organization, index) in allOrganizations"
                :key="index"
                :value="organization.id"
              >{{ organization.name }}
              </option
              >
            </select>
            <div class="error" v-if="!!validationErrors.organization_id">
              {{ validationErrors.organization_id[0] }}
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
          <div class="card mb-5">
            <div class="card-body" v-if="user.type === 'admin'">
              <label
                class="text-left col-12 mt-3 mb-0 d-flex align-items-center ml-2 pl-2 py-0 border-bottom"
              >
                <input
                  type="checkbox"
                  :id="'role'"
                  @click="toggleAll($event)"
                  class="mr-2 form-control-sm"
                  required
                />
                <span class="ml-1">{{ $t("All_Roles")}}</span>
              </label>
              <div class="row mx-0 mt-2">
                <div class="error" v-if="!!validationErrors.roles">
                  {{ validationErrors.roles[0] }}
                </div>
                <div
                  v-for="(role, index) in allRoles"
                  class="col-lg-4 text-left role-row pl-3 mr-0"
                  :key="index"
                >
                  <label
                    class="text-left col-12 mt-3 mb-0 d-flex align-items-center ml-2 pl-2 py-0"
                  >
                    <input
                      :id="'role-' + index"
                      type="checkbox"
                      class="form-control-sm"
                      :ref="role.name"
                      @change="syncRoles($event, role.name)"
                    />
                    <span class="ml-1">{{ role.display_name }}</span>
                  </label>
                </div>
              </div>
            </div>
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
  name: "UserDialog",
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
      user: {
        name: "",
        phone: "",
        email: "",
        password: "",
        type: "admin",
        organization_id: 1,
        department_id: "",
        guid: ""
      },
      allRoles: [],
      roles: [],
      users: [],
      allOrganizations: [],
      allDepartments: [],
    };
  },
  watch: {
    'id'(){
      // if(this.id > 0) {
      this.getRoles();
      window.jQueryAutocomplete(
        "autocompleteInput",
        "autocompleteResults",
        this.$axios.defaults.baseURL + "admin/users/ldapUsers",
        this.select
      );
      // }
    }
  },
  created() {
    this.getRoles();
    window.jQueryAutocomplete(
      "autocompleteInput",
      "autocompleteResults",
      this.$axios.defaults.baseURL + "admin/users/ldapUsers",
      this.select
    );
  },
  computed: {
    isDisable() {
      return this.user.name === "" || this.user.email === "";
    }
  },
  methods: {
    selectedType(event) {
      this.user.type = event.target.value;
      console.log(event.target.value);
    },
    select(row) {
      if (row) {
        this.user.name = row.name;
        this.user.email = row.email;
        this.user.guid = row.guid;
      }
    },
    getUser(id) {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/users/" + id)
        .then(res => {
          this.user = res.data;
          for (let i = 0; i < res.data.roles.length; i++) {
            setTimeout(() => {
              const elm = this.$refs[res.data.roles[i].name];
              if (typeof elm !== "undefined" && typeof elm[0] !== "undefined")
                elm[0].click();
            }, 500);
          }
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_users"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    getOrganizations() {
      this.$axios
        .get("admin/organizations/get/all")
        .then(response => {
          this.allOrganizations = response.data.data;
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_organizations"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
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
    getRoles() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/roles/all")
        .then(response => {
          this.allRoles = response.data;
          this.getOrganizations();
          this.getDepartments();
          window.jQueryEndLoading();
        })
        .then(() => {
          if (this.id > 0) {
            this.roles = [];
            this.getUser(this.id);
          }
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_roles"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    syncRoles(e, role) {
      if (e.target.checked) this.roles.push(role);
      else
        this.roles.splice(
          this.roles.findIndex(x => x === role),
          1
        );
    },
    toggleAll(e) {
      if (e.target.checked) {
        for (let index in this.allRoles) {
          let elm = document.getElementById("role-" + index);
          if (!elm.checked) elm.click();
        }
      } else
        for (let index in this.allRoles) {
          let elm = document.getElementById("role-" + index);
          if (elm.checked) elm.click();
        }
    },
    cancel() {
      this.user = {};
      this.roles = [];
      this.$emit('resetID');
    },
    add() {
      window.jQueryStartLoading();
      this.validationErrors = {};
      this.$axios
        .post("admin/users", {
          name: this.user.name,
          email: this.user.email,
          phone: this.user.phone,
          password: this.user.password,
          type: this.user.type,
          organization_id: this.user.organization_id,
          department_id: this.user.department_id,
          roles: this.roles,
          guid: this.user.guid
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
      if(this.user.type === "employee")
        this.roles = []
      this.$axios
        .put("admin/users", {
          id: this.id,
          name: this.user.name,
          email: this.user.email,
          type: this.user.type,
          phone: this.user.phone,
          organization_id: this.user.organization_id,
          department_id: this.user.department_id,
          roles: this.roles
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
