<template>
  <main>
    <div class="container">
      <div class="row">
        <div class="col">
          <!-- Title and Top Buttons Start -->
          <div class="page-title-container">
            <div class="row">
              <!-- Title Start -->
              <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">
                  {{ $t("Requests_Templates") }}
                </h1>
                <nav
                  class="breadcrumb-container d-inline-block"
                  aria-label="breadcrumb"
                >
                  <ul class="breadcrumb pt-0">
                    <li class="breadcrumb-item">
                      <nuxt-link :to="{ name: 'home' }">
                        {{ $t("Home") }}
                      </nuxt-link>
                    </li>
                  </ul>
                </nav>
              </div>
              <!-- Title End -->

              <!-- Top Buttons Start -->
              <div
                class="col-12 col-md-5 d-flex align-items-start justify-content-end"
              >
                <!-- Add New Button Start -->
                <button
                  type="button"
                  class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable"
                  v-if="userPermissions.includes('create-templates')"
                >
                  <i data-cs-icon="plus"></i>
                  <span>{{ $t("Add_New") }}</span>
                </button>
                <!-- Add New Button End -->

                <!-- Check Button Start -->
                <div class="btn-group ms-1 check-all-container">
                  <div
                    class="btn btn-outline-primary btn-custom-control p-0 ps-3 pe-2"
                    id="datatableCheckAllButton"
                  >
                    <span class="form-check float-end">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="datatableCheckAll"
                      />
                    </span>
                  </div>
                  <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                    data-bs-offset="0,3"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    data-submenu
                  ></button>
                  <div class="dropdown-menu dropdown-menu-end">
                    <button
                      class="dropdown-item disabled delete-datatable"
                      type="button"
                      v-if="userPermissions.includes('delete-templates')"
                    >
                      {{ $t("Delete") }}
                    </button>
                    <button
                      class="dropdown-item disabled restore-datatable"
                      type="button"
                    >
                      {{ $t("Restore") }}
                    </button>
                  </div>
                </div>
                <!-- Check Button End -->
              </div>
              <!-- Top Buttons End -->
            </div>
          </div>
          <!-- Title and Top Buttons End -->

          <!-- Content Start -->
          <div class="data-table-rows slim">
            <!-- Controls Start -->
            <div class="row">
              <!-- Search Start -->
              <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                <div
                  class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground"
                >
                  <input
                    class="form-control datatable-search"
                    :placeholder="$t('Search')"
                    data-datatable="#datatableServerSideUrl"
                  />
                  <span class="search-magnifier-icon">
                    <i data-cs-icon="search"></i>
                  </span>
                  <span class="search-delete-icon d-none">
                    <i data-cs-icon="close"></i>
                  </span>
                </div>
              </div>
              <!-- Search End -->

              <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">
                <div
                  class="d-inline-block me-0 me-sm-3 float-start float-md-none"
                >
                  <!-- Add Button Start -->
                  <button
                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow add-datatable"
                    data-bs-delay="0"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="$t('Add')"
                    type="button"
                    v-if="userPermissions.includes('create-templates')"
                  >
                    <i data-cs-icon="plus"></i>
                  </button>
                  <!-- Add Button End -->

                  <!--  assign -->
                  <button
                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow admin-datatable disabled"
                    data-bs-delay="0"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="$t('Admins')"
                    type="button"
                  >
                    <i data-cs-icon="user"></i>
                  </button>
                  <!-- assign End -->

                  <!--  assign -->
                  <button
                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow edit-template-datatable disabled"
                    data-bs-delay="0"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="$t('Edit Template')"
                    type="button"
                    @click="editTemplate"
                  >
                    <i data-cs-icon="edit-square"></i>
                  </button>
                  <!-- assign End -->

                  <!-- Edit Button Start -->
                  <button
                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow edit-datatable disabled"
                    data-bs-delay="0"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="$t('Edit')"
                    type="button"
                    @click="edit"
                    v-if="userPermissions.includes('edit-templates')"
                  >
                    <i data-cs-icon="edit"></i>
                  </button>
                  <!-- Edit Button End -->

                  <!-- Delete Button Start -->
                  <button
                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow disabled delete-datatable"
                    data-bs-delay="0"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="$t('Delete')"
                    type="button"
                    v-if="userPermissions.includes('delete-templates')"
                  >
                    <i data-cs-icon="bin"></i>
                  </button>
                  <!-- Delete Button End -->
                  <!-- Restore Button Start -->
                  <button
                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow disabled restore-datatable"
                    data-bs-delay="0"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="$t('Restore')"
                    type="button"
                  >
                    <i data-cs-icon="rotate-left"></i>
                  </button>
                  <!-- Restore Button End -->
                </div>
                <div class="d-inline-block">
                  <!-- Print Button Start -->
                  <button
                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                    data-bs-delay="0"
                    data-datatable="#datatableServerSideUrl"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="$t('Print')"
                    type="button"
                  >
                    <i data-cs-icon="print"></i>
                  </button>
                  <!-- Print Button End -->

                  <!-- Export Dropdown Start -->
                  <div
                    class="d-inline-block datatable-export"
                    data-datatable="#datatableServerSideUrl"
                  >
                    <button
                      class="btn p-0"
                      data-bs-toggle="dropdown"
                      type="button"
                      data-bs-offset="0,3"
                    >
                      <span
                        class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
                        data-bs-delay="0"
                        data-bs-placement="top"
                        data-bs-toggle="tooltip"
                        :title="$t('Export')"
                      >
                        <i data-cs-icon="download"></i>
                      </span>
                    </button>
                    <div class="dropdown-menu shadow dropdown-menu-end">
                      <button class="dropdown-item export-copy" type="button">
                        {{ $t("Copy") }}
                      </button>
                      <button class="dropdown-item export-excel" type="button">
                        {{ $t("Excel") }}
                      </button>
                      <button class="dropdown-item export-cvs" type="button">
                        {{ $t("Cvs") }}
                      </button>
                    </div>
                  </div>
                  <!-- Export Dropdown End -->

                  <!-- Length Start -->
                  <div
                    class="dropdown-as-select d-inline-block datatable-length"
                    data-datatable="#datatableServerSideUrl"
                    data-childSelector="span"
                  >
                    <button
                      class="btn p-0 shadow"
                      type="button"
                      data-bs-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                      data-bs-offset="0,3"
                    >
                      <span
                        class="btn btn-foreground-alternate dropdown-toggle"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-delay="0"
                        :title="$t('Rows_Count')"
                      >
                        10 {{ $t("Rows") }}
                      </span>
                    </button>
                    <div class="dropdown-menu shadow dropdown-menu-end">
                      <a class="dropdown-item active" href="#"
                        >10 {{ $t("Rows") }}</a
                      >
                      <a class="dropdown-item" href="#">20 {{ $t("Rows") }}</a>
                      <a class="dropdown-item" href="#">50 {{ $t("Rows") }}</a>
                      <a class="dropdown-item" href="#">100 {{ $t("Rows") }}</a>
                    </div>
                  </div>

                  <div
                    class="dropdown-as-select d-inline-block datatable-filter"
                    data-datatable="#datatableServerSideUrl"
                    data-childSelector="span"
                  >
                    <button
                      class="btn p-0 shadow"
                      type="button"
                      data-bs-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                      data-bs-offset="0,3"
                    >
                      <span
                        class="btn btn-foreground-alternate dropdown-toggle"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-delay="0"
                        :title="$t('Rows_Type')"
                      >
                        {{ $t("Not_trashed")}}
                      </span>
                    </button>
                    <div class="dropdown-menu shadow dropdown-menu-end">
                      <a class="dropdown-item active" href="#">
                        {{ $t("Not_trashed")}}
                      </a>
                      <a class="dropdown-item" href="#">{{ $t("All")}}</a>
                      <a class="dropdown-item" href="#">{{ $t("Trashed")}}</a>
                    </div>
                  </div>
                  <!-- Length End -->
                </div>
              </div>
            </div>
            <!-- Controls End -->

            <!-- Table Start -->
            <div class="data-table-responsive-wrapper">
              <table
                id="datatableServerSideUrl"
                class="data-table nowrap hover"
              >
                <thead>
                  <tr>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Name") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Admin") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Last_Update") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Requests_Count") }}
                    </th>
                    <th class="empty">&nbsp;</th>
                    <th class="invisible">&nbsp;</th>
                  </tr>
                </thead>
              </table>
            </div>
            <!-- Table End -->
          </div>
          <!-- Content End -->

          <!-- Add Modal Start -->
          <div
            class="modal modal-right fade"
            id="addModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modalTitle"
            aria-hidden="true"
          >
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalTitle">{{ $t("Add_New")}}</h5>
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
                      <label class="form-label">{{ $t("Name")}}</label>
                      <input type="text" v-model="name" class="form-control" />
                      <div class="error" v-if="!!validationErrors.name">
                        {{ validationErrors.name[0] }}
                      </div>
                    </div>
                    <div class="mb-3 top-label">
                      <label class="form-label">{{ $t("Ar Name")}}</label>
                      <input type="text" v-model="ar_name" class="form-control" />
                      <div class="error" v-if="!!validationErrors.ar_name">
                        {{ validationErrors.ar_name[0] }}
                      </div>
                    </div>
                    <div class="mb-3 top-label" v-if="id <= 0">
                      <label class="form-label">{{ $t("Template")}}</label>
                      <div @click="getTemplates()">
                        <select v-model="template_id" class="form-control">
                        <option value=""></option>
                        <option v-for="(template, index) in templates" :value="template.id">{{
                            template.name
                          }}</option>
                      </select>
                      </div>
                      <div class="error" v-if="!!validationErrors.template_id">
                        {{ validationErrors.template_id[0] }}
                      </div>
                    </div>
<!--                    <div class="mb-3 top-label" v-if="organizations.length > 1">-->
<!--                      <label class="form-label">{{ $t("organizations")}}</label>-->
<!--                      <select id="template_id" class="form-control" v-model="organization_id"-->
<!--                              :class="{'is-invalid': !!validationErrors.organization_id }">-->
<!--                        <option v-for="(organization,organizationIndex) in organizations" :value="organization.id" :key="organizationIndex">{{ organization.name }}</option>-->
<!--                      </select>-->
<!--                      <div class="error" v-if="!!validationErrors.organization_id">-->
<!--                        {{ validationErrors.organization_id[0] }}-->
<!--                      </div>-->
<!--                    </div>-->
                    <div class="mb-3 top-label">
                      <label class="form-label">{{ $t("Icon")}}</label>
                      <select v-model="icon" class="form-control">
                        <option value=""></option>
                        <option v-for="(icon,iconIndex) in icons" :value="icon.value" :key="iconIndex" >
<!--                          <i :class="`text-center mb-3 d-inline-block text-primary icon-20 `+ icon.value"></i>-->
                          {{ icon.text }}
                        </option>
                      </select>
                    </div>
                    <strong>{{ $t('Template_Icon_Is') }} : </strong><i :class="`text-center mb-4 d-inline-block text-primary icon-20 `+ icon"></i>
                    <div class="mb-3 filled">
                      <multiselect v-model="organization_id" tag-placeholder="Add this as new tag"
                                   :placeholder="$t('organizations')"
                                   label="name"
                                   track-by="id"
                                   :options="organizations"
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
                  >
                    {{ $t("Cancel")}}
                  </button>
                  <button type="button" class="btn btn-primary" @click="add">
                    {{ $t("Save")}}
                  </button>
                  <button class="invisible" id="addEditConfirmButton"></button>
                </div>
              </div>
            </div>
          </div>
          <!-- Add Modal End -->
          <!-- Edit Modal Start -->
          <div
            class="modal modal-right fade"
            id="editModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modalTitle"
            aria-hidden="true"
          >
            <div class="modal-dialog" style="max-width: 90% !important;">
              <div class="modal-content h-100 overflow-hidden">
                <div class="modal-header">
                  <h5 class="modal-title">{{ $t("Dynamic_Form_Builder") }}</h5>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                  ></button>
                </div>
                <dynamic-form-builder :id="id" v-if="id" />
              </div>
            </div>
          </div>
          <!-- Add Edit End -->
        </div>
      </div>
    </div>
    <delete-confirm url="admin/templates" :id="ids" />
    <restore-dialog url="admin/templates/restore" :id="ids" />
    <div
      class="modal modal-right fade"
      id="adminsModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalTitle"
      aria-hidden="true"
    >
      <template-admin v-if="id > -1" :template="template" :organizations="organizations" :employees="employees"></template-admin>
      <button class="invisible closeAndReload"></button>
    </div>
  </main>
</template>

<script>
// import DeleteCheck from "~/components/dynamic-elements/DeleteCheck";
import Create from "./Create";
import DynamicFormBuilder from "./dynamic-form-builder";
import DeleteConfirm from "../../../components/DeleteConfirm";
import RestoreDialog from "../../../components/RestoreDialog";
import templateAdmin from "./template-admin"
import {mapActions, mapGetters} from "vuex";
import axios from "axios";
import Multiselect from 'vue-multiselect'

export default {
  name: "index",
  components: { DynamicFormBuilder, Create, DeleteConfirm,
    RestoreDialog,templateAdmin,Multiselect },
  layout: "admin",
  middleware: ["auth", "admin"],
  head() {
    return {
      title: "Templates"
    };
  },
  // watch: {
  //   loading(newValue) {
  //     this.$store.commit("setLoading", newValue)
  //   },
  // },
  data() {
    return {
      id: 0,
      ids: [],
      reload: false,
      validationErrors: {},
      templates: [],
      template:{},
      employees:[],
      organizations:[],
      name: "",
      ar_name: "",
      organization_id:[],
      template_id: null,
      icon:'cs-star-full',
      icons: [
        {text: 'scissors', value: 'cs-circle'},
        {text: 'scissors', value: 'cs-scissors'},
        {text: 'question hexagon', value: 'cs-question-hexagon'},
        {text: 'save', value: 'cs-save'},
        {text: 'search', value: 'cs-search'},
        {text: 'send', value: 'cs-send'},
        {text: 'settings-2', value: 'cs-settings-2'},
        {text: 'sort', value: 'cs-sort'},
        {text: 'star', value: 'cs-star'},
        {text: 'star-full', value: 'cs-star-full'},
        {text: 'user', value: 'cs-user'},
        {text: 'warning hexagon', value: 'cs-warning-hexagon'},
        {text: 'archive', value: 'cs-archive'},
        {text: 'arrow double left', value: 'cs-arrow-double-left'},
        {text: 'arrow double right', value: 'cs-arrow-double-right'},
        {text: 'bin', value: 'cs-bin'},
        {text: 'calendar', value: 'cs-calendar'},
        {text: 'check square', value: 'cs-check-square'},
        {text: 'chevron bottom', value: 'cs-chevron-bottom'},
        {text: 'chevron left', value: 'cs-chevron-left'},
        {text: 'chevron right', value: 'cs-chevron-right'},
        {text: 'chevron top', value: 'cs-chevron-top'},
        {text: 'clipboard', value: 'cs-clipboard'},
        {text: 'clock', value: 'cs-clock'},
        {text: 'close', value: 'cs-close'},
        {text: 'close circle', value: 'cs-close-circle'},
        {text: 'duplicate', value: 'cs-duplicate'},
        {text: 'edit square', value: 'cs-edit-square'},
        {text: 'error hexagon', value: 'cs-error-hexagon'},
        {text: 'file text', value: 'cs-file-text'},
        {text: 'gear', value: 'cs-gear'},
        {text: 'info hexagon', value: 'cs-info-hexagon'},
        {text: 'more horizontal', value: 'cs-more-horizontal'},
      ],
    };
  },
  computed: {
    ...mapGetters(["userPermissions"]),
  },
  watch: {
    'id'(){
      if(this.id > 0) {
        this.getTemplate();
      }
    }
  },
  created() {
    window.jQueryStartLoading();
    window.ajaxHeaders = this.$axios.defaults.headers.common;
    window.datatableServerSideUrl =
      this.$axios.defaults.baseURL + "admin/templates";
    window.datatableServerSideColumns = [
      { data: "name" },
      { data: "user_id", searchable: false },
      { data: "created_at" },
      { data: "updated_at" },
      { data: "requests_count", searchable: false },
      { data: "Check", searchable: false },
      { data: "deleted_at", searchable: false, visible: false },
      { data: "id", searchable: false, visible: false },
      // { data: "organization_id", visible: false}
    ];
    window.datatableServerSideColumnDefs = [
      {
        targets: 0, // user object
        render: function(data, type, row, meta) {
          // localStorage.get('locale')
         if(localStorage.getItem('locale') == 'ar' && row.ar_name == null)
            return row.name;
         else if(localStorage.getItem('locale') == 'ar')
            return row.ar_name;
         else
            return row.name;
        }
      },
      {
        targets: 1, // user object
        render: function(data, type, row, meta) {
          return row.user.name;
        }
      },
      {
        targets: 5,
        render: function(data, type, row, meta) {
          return '<div class="form-check float-end mt-1"><input type="checkbox" class="form-check-input"></div>';
        }
      }
    ];
    window.datatableServerSideEdit = (e, row) => this.edit(e, row);
    window.datatableServerSideEditTemplate = (e, row) => this.editTemplate(e, row);
    window.datatableServerSideAssignAdmins = (e, row) => this.admins(e, row);
    window.datatableServerSideDelete = rows => this.delete(rows);
    window.datatableServerSideRestore = rows => this.restore(rows);

    // load templates
    this.getTemplates()
    this.getEmployees()
    this.getOrganizations()

  },
  methods: {
    getTemplate() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/templates/" + this.id + "/get")
        .then(response => {
          console.log('response',response.data)
          this.name = response.data.name
          this.ar_name = response.data.ar_name
          this.organization_id = response.data.organizations
          this.icon = response.data.icon
          // if(this.organizations.length > 1)
          // {
          //   this.organizations.forEach(org => {
          //     this.organization_id.push(org.id)
          //   });
          // }
          // this.organization = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_organizations"), "danger");
          window.jQueryEndLoading();
        });
    },
    getTemplates(){
      this.$axios
        .get("admin/templates")
        .then(response => {
          this.templates = response.data.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_templates"), "danger");
          window.jQueryEndLoading();
        });
    },
    getOrganizations() {
      this.$axios({
        method: "GET",
        url: "admin/getAdminOrganizations"
      }).then(response => {
        this.organizations = response.data.data
      }).catch(error => {
        console.log(error)
      })
    },
    add() {
      window.jQueryStartLoading();
      if(this.id > 0)
      {
        this.$axios
          .post("admin/templates/updateTemplate", {
            id: this.id,
            name: this.name,
            ar_name: this.ar_name,
            organization_id: this.organization_id,
            icon: this.icon,
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
      else {
        let organization_id = [];
        if (this.organizations.length > 1)
          organization_id = this.organization_id
        else if (this.$auth.user.roles.includes('Admin'))
          organization_id.push(this.organizations[0])
        this.$axios
          .post("admin/templates", {
            name: this.name,
            ar_name: this.ar_name,
            template_id: this.template_id,
            organization_id: organization_id,
            icon: this.icon,
          })
          .then(() => {
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
      }
    },
    edit(e, template) {
      if (typeof template != "undefined") {
        this.id = 0;
        setTimeout(() => {
          this.id = template.id;
          window.showModal("#editModal");
        }, 200);
      }
    },
    editTemplate(e, template) {
      if (typeof template != "undefined") {
        this.id = 0;
        setTimeout(() => {
          this.id = template.id;
          window.showModal("#addModal");
        }, 200);
      }
    },
    admins(e, template) {
      console.log(template)
      if (typeof template != "undefined") {
        setTimeout(() => {
          this.id = template.id;
          if(template.organizations.length > 0)
          {
            this.organizations = template.organizations
          }
          else{
            this.getOrganizations()
          }
          this.template = Object.assign(template)
          window.showModal("#adminsModal");
        }, 200);
        this.id = 0;
      }
    },
    getEmployees() {
      this.$axios.get("admin/forms/employees_Available").then(response => {
        this.employees = response.data.data
      })
    },
    delete(ids) {
      this.ids = ids;
      this.$modal.show("DeleteConfirm");
    },
    restore(ids) {
      this.ids = ids;
      this.$modal.show("RestoreDialog");
    }
  }
};
</script>
