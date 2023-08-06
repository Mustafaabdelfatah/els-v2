<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">
          {{ $t("Role") }}
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          @click="cancel"
        ></button>
      </div>
      <div class="modal-body" id="topLabel">
        <form id="validationTopLabel" class="tooltip-end-top">
          <div class="mb-3 top-label">
            <input
              name="name"
              v-model="role.name"
              type="text"
              class="form-control"
              required
            />
            <span>{{ $t("Name") }}</span>
            <div id="name-error" class="error" v-if="!!validationErrors.name">
              {{ validationErrors.name[0] }}
            </div>
          </div>
          <div class="mb-3 top-label">
            <input
              name="display_name"
              type="text"
              class="form-control"
              v-model="role.display_name"
            />
            <span>{{ $t("Display_Name") }}</span>
            <div
              id="display_name-error"
              class="error"
              v-if="!!validationErrors.display_name"
            >
              {{ validationErrors.display_name[0] }}
            </div>
          </div>
          <div class="mb-3 top-label" v-if="organizations.length > 0">
            <multiselect v-model="role.organizations" tag-placeholder="Add this as new tag"
                         :placeholder="$t('organization')"
                         label="name"
                         track-by="id"
                         :options="organizations"
                         :multiple="true"
                         :taggable="true"
            ></multiselect>
            <div
              id="organization_id-error"
              class="error"
              v-if="!!validationErrors.organization_id"
            >
              {{ validationErrors.organization_id[0] }}
            </div>
          </div>
          <div class="card mb-5">
            <div class="card-header border-0 pb-0">
              <ul class="nav nav-pills responsive-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button
                    style="display:inline-block"
                    v-for="(permissions, group) in allPermissions"
                    :key="group"
                    class="nav-link"
                    :class="{ 'show active': activeTab === group }"
                    data-bs-toggle="tab"
                    :data-bs-target="'#menu-' + group"
                    role="tab"
                    aria-selected="true"
                    type="button"
                  >
                    {{ group }}
                  </button>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div
                  v-for="(permissions, group) in allPermissions"
                  :key="group"
                  :class="{ 'show active': activeTab === group }"
                  class="tab-pane fade"
                  :id="'menu-' + group"
                  role="tabpanel"
                >
                  <div
                    class="tab-pane"
                    role="tabpanel"
                    :aria-labelledby="'v-pills-' + group"
                  >
                    <p
                      class="text-left col-12 mt-3 mb-0 d-flex align-items-center ml-2 pl-2 py-0 border-bottom"
                    >
                      <input
                        type="checkbox"
                        :id="'permission-' + group"
                        class="mr-2 form-control-sm"
                        required
                        @click="toggleAll($event, group)"
                      />
                      <span class="ml-1">{{ $t("All")}} {{ group }}</span>
                    </p>
                    <div class="row mx-0 mt-2">
                      <div
                        id="permissions-error"
                        class="error"
                        v-if="!!validationErrors.permissions"
                      >
                        {{ validationErrors.permissions[0] }}
                      </div>
                      <div
                        v-for="(permission, index) in permissions"
                        :key="index"
                        class="col-lg-6 text-left role-row pl-3 mr-0"
                        v-if="permission.display_name.split('-')[0] != 'assign'"
                      >
                        <label
                          class="text-left col-12 mt-3 mb-0 d-flex align-items-center ml-2 pl-2 py-0"
                        >
                          <input
                            :id="'permission-' + group + '-' + index"
                            type="checkbox"
                            class="form-control-sm"
                            :ref="permission.name"
                            @change="syncPermissions($event, permission.name)"
                          />
                          <span class="ml-1">{{ permission.display_name.replace() }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
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
import Multiselect from 'vue-multiselect'
export default {
  name: "RoleDialog",
  props: {
    id: {
      type: Number,
      required: true
    },
    organizations:{
      type: Array,
      required: true
    }
  },
  components: {
    Multiselect
  },
  data() {
    return {
      validationErrors: {},
      allPermissions: [],
      activeTab: "",
      role: {
        name: "",
        display_name: "",
        organizations:[],
      },
      permissions: []
    };
  },
  watch: {
    'id'(){
      this.getPermissions();
    }
  },
  created() {
    this.getPermissions();
  },
  computed: {
    isDisable() {
      return (
        // this.permissions.length <= 0 ||
        this.role.name === "" ||
        this.role.display_name === ""
      );
    }
  },
  methods: {
    cancel() {
      this.role = {};
      this.permissions = [];
      this.$emit('resetID');
    },
    getRole(id) {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/roles/" + id)
        .then(res => {
          this.role.name = res.data.name;
          this.role.display_name = res.data.display_name;
          this.role.organizations = res.data.organizations;
          for (let i = 0; i < res.data.permissions.length; i++) {
            const elm = this.$refs[res.data.permissions[i].name];
            if (typeof elm !== "undefined" && typeof elm[0] !== "undefined")
              elm[0].click();
          }
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_role"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    getPermissions() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/permissions")
        .then(response => {
          this.allPermissions = response.data;
          this.activeTab = Object.keys(this.allPermissions)[0];
          window.jQueryEndLoading();
        })
        .then(() => {
          if (this.id) {
            this.permissions = [];
            this.getRole(this.id);
          }
        })
        .catch(function(error) {
          window.jQueryToast(this.$t("Failed to load permissions"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    syncPermissions(e, permission) {
      if (e.target.checked) this.permissions.push(permission);
      else
        this.permissions.splice(
          this.permissions.findIndex(x => x === permission),
          1
        );
    },
    toggleAll(e, target_group = "") {
      if (e.target.checked) {
        for (let group in this.allPermissions) {
          if (target_group !== "" && target_group !== group) continue;
          for (let index in this.allPermissions[group]) {
            let elm = document.getElementById(
              "permission-" + group + "-" + index
            );
            if (!elm.checked) elm.click();
          }
        }
      } else
        for (let group in this.allPermissions) {
          if (target_group !== "" && target_group !== group) continue;
          for (let index in this.allPermissions[group]) {
            let elm = document.getElementById(
              "permission-" + group + "-" + index
            );
            if (elm.checked) elm.click();
          }
        }
    },
    add() {
      window.jQueryStartLoading();
      this.validationErrors = {};
      const bodyFormData = new FormData
      for (const key in this.role.organizations) {
        let value = this.role.organizations[key]
        bodyFormData.set('organizations['+key+']',value.id)
      }
      for (const key in this.permissions) {
        let value = this.permissions[key]
        bodyFormData.set('permissions['+key+']',value)
      }
      bodyFormData.append('name',this.role.name)
      bodyFormData.append('display_name',this.role.display_name)
      this.$axios({
        url:"admin/roles",
        method: 'POST',
        data: bodyFormData
      }).then(() => {
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
      window.jQueryStartLoading()
      this.validationErrors = {}
      const bodyFormData = new FormData
      for (const key in this.role.organizations) {
        let value = this.role.organizations[key]
        bodyFormData.set('organizations['+key+']',value.id)
      }
      for (const key in this.permissions) {
        let value = this.permissions[key]
        bodyFormData.set('permissions['+key+']',value)
      }
      bodyFormData.append('id',this.id)
      bodyFormData.append('name',this.role.name)
      bodyFormData.append('display_name',this.role.display_name)
      bodyFormData.append('_method','put')
      this.$axios({
        url:"admin/roles",
        method: 'POST',
        data: bodyFormData
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
