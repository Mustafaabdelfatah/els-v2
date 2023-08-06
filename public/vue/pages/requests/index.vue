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
                  {{ $t("Requests") }}
                </h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                  <ul class="breadcrumb pt-0">
                    <li class="breadcrumb-item">
                      <nuxt-link :to="{ name: 'home' }">{{
                        $t("Home")
                      }}
                      </nuxt-link>
                    </li>
                  </ul>
                </nav>
              </div>
              <!-- Title End -->

              <!-- Top Buttons Start -->
              <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                <!-- Add New Button Start -->
                <button type="button" @click="add"
                  class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                  <i data-cs-icon="plus"></i>
                  <span>{{ $t("Add_New") }}</span>
                </button>
                <!-- Add New Button End -->

                <!-- Check Button Start -->
                <div class="btn-group ms-1 check-all-container">
                  <div class="btn btn-outline-primary btn-custom-control p-0 ps-3 pe-2" id="datatableCheckAllButton">
                    <span class="form-check float-end">
                      <input type="checkbox" class="form-check-input" id="datatableCheckAll" />
                    </span>
                  </div>
                  <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                    data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    data-submenu></button>
                  <div class="dropdown-menu dropdown-menu-end">
                    <button class="dropdown-item disabled delete-datatable" type="button">
                      {{ $t("Delete") }}
                    </button>
                    <button class="dropdown-item disabled restore-datatable" type="button">
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
                <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                  <input class="form-control datatable-search" :placeholder="$t('Search')"
                    data-datatable="#datatableServerSideUrl" />
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
                <div class="d-inline-block me-0 me-sm-3 float-start float-md-none">
                  <!-- Add Button Start -->
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow" data-bs-delay="0"
                    data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Add')" type="button" @click="add">
                    <i data-cs-icon="plus"></i>
                  </button>
                  <!-- Add Button End -->

                  <!-- Edit Button Start -->
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow edit-datatable disabled"
                    data-bs-delay="0" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Edit')" type="button"
                    @click="edit">
                    <i data-cs-icon="edit"></i>
                  </button>
                  <!-- Edit Button End -->

                  <!-- Delete Button Start -->
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow disabled delete-datatable"
                    data-bs-delay="0" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Delete')"
                    type="button">
                    <i data-cs-icon="bin"></i>
                  </button>
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow message-datatable disabled"
                    data-bs-delay="0" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Show Request')"
                    type="button" @click="showMessage">
                    <i data-cs-icon="eye"></i>
                  </button>
                  <!-- Delete Button End -->
                  <!-- Restore Button Start -->
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow disabled restore-datatable"
                    data-bs-delay="0" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Restore')"
                    type="button">
                    <i data-cs-icon="rotate-left"></i>
                  </button>
                  <!-- Restore Button End -->
                </div>
                <div class="d-inline-block">
                  <!-- Print Button Start -->
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                    data-bs-delay="0" data-datatable="#datatableServerSideUrl" data-bs-toggle="tooltip"
                    data-bs-placement="top" :title="$t('Print')" type="button">
                    <i data-cs-icon="print"></i>
                  </button>
                  <!-- Print Button End -->

                  <!-- Export Dropdown Start -->

                  <div class="d-inline-block datatable-export" data-datatable="#datatableServerSideUrl">
                    <button
                      class="d-inline-block  dropdown-item export-excel btn btn-icon btn-icon-only btn-sm btn-foreground-alternate shadow dropdown">
                      <i data-cs-icon="download"></i>
                    </button>

                  </div>
                  <!-- Export Dropdown End -->

                  <!-- Length Start -->
                  <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableServerSideUrl"
                    data-childSelector="span">
                    <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                      aria-expanded="false" data-bs-offset="0,3">
                      <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-delay="0" :title="$t('Rows_Count')">
                        10 {{ $t("Rows") }}
                      </span>
                    </button>
                    <div class="dropdown-menu shadow dropdown-menu-end">
                      <a class="dropdown-item active" href="#">10 {{ $t("Rows") }}</a>
                      <a class="dropdown-item" href="#">20 {{ $t("Rows") }}</a>
                      <a class="dropdown-item" href="#">50 {{ $t("Rows") }}</a>
                      <a class="dropdown-item" href="#">100 {{ $t("Rows") }}</a>
                    </div>
                  </div>

                  <div class="dropdown-as-select d-inline-block datatable-filter" data-datatable="#datatableServerSideUrl"
                    data-childSelector="span">
                    <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                      aria-expanded="false" data-bs-offset="0,3">
                      <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-delay="0" :title="$t('Rows_Type')">
                        {{ $t("Not_trashed") }}
                      </span>
                    </button>
                    <div class="dropdown-menu shadow dropdown-menu-end">
                      <a class="dropdown-item active" href="#">
                        {{ $t("Not_trashed") }}
                      </a>
                      <a class="dropdown-item" href="#">{{ $t("All") }}</a>
                      <a class="dropdown-item" href="#">{{ $t("Trashed") }}</a>
                    </div>
                  </div>

                  <!--                  <div class="d-inline-block shadow">-->
                  <!--                    <select class="form-select"-->
                  <!--                            id="department_id" v-model="department_id"-->
                  <!--                            @change="filterDataTable">-->
                  <!--                      <option value="">{{ $t("All_departments") }}</option>-->
                  <!--                      <option-->
                  <!--                        v-for="(department, index) in allDepartments"-->
                  <!--                        :key="index"-->
                  <!--                        :value="department.id"-->
                  <!--                      >{{ $i18n.locale === 'en' ? department.name_en :  department.name_ar }}-->
                  <!--                      </option>-->
                  <!--                    </select>-->
                  <!--                  </div>-->

                  <!--                  <div class="d-inline-block shadow">-->
                  <!--                    <select class="form-select"-->
                  <!--                            id="organization_id" v-model="organization_id"-->
                  <!--                            @change="filterDataTable">-->
                  <!--                      <option value="">{{ $t("All_organizations") }}</option>-->
                  <!--                      <option-->
                  <!--                        v-for="(organization, index) in allOrganizations"-->
                  <!--                        :key="index"-->
                  <!--                        :value="organization.id"-->
                  <!--                      >{{ organization.name }}-->
                  <!--                      </option>-->
                  <!--                    </select>-->
                  <!--                  </div>-->

                  <!--                  <div class="d-inline-block shadow">-->
                  <!--                    <select class="form-select"-->
                  <!--                            id="type" v-model="type"-->
                  <!--                            @change="filterDataTable">-->
                  <!--                      <option value="">{{ $t("All_request_types") }}</option>-->
                  <!--                      <option value="form">form</option>-->
                  <!--                      <option value="application">application</option>-->
                  <!--                    </select>-->
                  <!--                  </div>-->

                  <!-- Length End -->
                </div>
              </div>
            </div>
            <!-- Controls End -->

            <!-- Table Start -->
            <div class="data-table-responsive-wrapper">
              <table id="datatableServerSideUrl" class="data-table nowrap hover">
                <thead>
                  <tr>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("#") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("request_no") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("type") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("user_benefit") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("organization") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("department") }}
                    </th>
                    <!--                    <th class="text-muted text-small text-uppercase">-->
                    <!--                      {{ $t("type") }}-->
                    <!--                    </th>-->
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("assigned_User") }}
                    </th>
                    <!--                  <th class="text-muted text-small text-uppercase">-->
                    <!--                    {{ $t("assigned_date") }}-->
                    <!--                  </th>-->
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("submission_date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("returned") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("returned_time") }}
                    </th>
                    <!--                  <th class="text-muted text-small text-uppercase">-->
                    <!--                    {{ $t("Priority") }}-->
                    <!--                  </th>-->
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("expected_amount") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Status") }}
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
          <div class="modal modal-right fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
            aria-hidden="true">
            <template-dialog :id="2" v-if="show" />
            <button class="invisible" id="addEditConfirmButton"></button>
          </div>
          <!-- Add Modal End -->
          <!-- edit modal -->
          <div class="modal modal-right fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
            aria-hidden="true">
            <edit-request :id="id" v-if="show" />
            <button class="invisible closeAndReload"></button>
          </div>
          <!-- end edit modal -->
          <!--show message coming from assigned user or admin-->

          <div class="modal modal-right fade" id="showMessageElement" tabindex="-1" role="dialog"
            aria-labelledby="modalTitle" aria-hidden="true">
            <!--            <form-dialog :id="2" v-if="show"/>-->
            <review-dialog :id="id" v-if="show" />
            <button class="invisible" id="showMessageElementButton"></button>
          </div>
        </div>
      </div>
    </div>
    <delete-confirm url="admin/forms" :id="ids" />
    <restore-dialog url="admin/forms/restore" :id="ids" />
  </main>
</template>

<script>
import DeleteConfirm from "../../components/DeleteConfirm";
import RestoreDialog from "../../components/RestoreDialog";
import TemplateDialog from "./TemplateDialog";
import EditRequest from "./editRequest";
import ReviewDialog from "./RequestAssigendReviewDialog";

export default {
  components: {
    EditRequest,
    TemplateDialog,
    RestoreDialog,
    DeleteConfirm,
    ReviewDialog
  },
  name: "index",
  layout: "admin",
  middleware: ["auth", "admin"],
  head() {
    return {
      title: "Requests"
    };
  },
  watch: {
    // loading(newValue) {
    //   this.$store.commit("setLoading", newValue)
    // },
  },
  data() {
    return {
      loading: false,
      row: {},
      id: 0,
      ids: [],
      user: {},
      editModalAction: false,
      allDepartments: [],
      department_id: '',
      allOrganizations: [],
      organization_id: '',
      type: '',
    };
  },
  computed: {
    show() {
      if (this.id !== -1) return true;
      return false
    }
  },
  created() {
    console.log('User ', this.$auth.state.user.id)
    window.ajaxHeaders = this.$axios.defaults.headers.common;
    window.datatableServerSideUrl =
      this.$axios.defaults.baseURL + "admin/forms?review=me";
    window.datatableServerSideColumns = [
      { data: "id", orderable: false },
      { data: "id" },
      { data: "name" },
      { data: 'user_id' },
      { data: 'user_id' },
      { data: 'user_id', searchable: false },
      // { data: 'template_id' },
      { data: 'template_id' },
      // {data: 'template_id'},
      { data: 'created_at' },
      { data: 'template_id' },
      { data: 'template_id' },
      { data: 'template_id' },
      { data: 'status' },
      { data: "Check", searchable: false },
      { data: "created_at", searchable: false, visible: false },
      { data: "deleted_at", searchable: false, visible: false },
      { data: "id", searchable: false, visible: false },
    ];
    window.datatableServerSideColumnDefs = [
      {
        targets: 0, // user object
        render: function (data, type, row, meta) {
          return row.index;
        }
      },
      {
        targets: 1, // user object
        render: function (data, type, row, meta) {
          return row._id;
        }
      },
      {
        targets: 3, // user object
        render: function (data, type, row, meta) {
          return row.user.name;
        }
      },
      {
        targets: 4, // user object
        render: function (data, type, row, meta) {
          return (row.organization) ? row.organization.name : 'No organization';
        }
      },
      {
        targets: 5, // user object
        render: (data, type, row, meta) => {
          if (row.department)
            return (this.$i18n.locale === 'en') ? row.department.name_en : row.department.name_ar
          else
            return 'No department'
        }
      },
      // {
      //   targets: 6, // user object
      //   render: function (data, type, row, meta) {
      //     return (row.template) ? row.template.type : 'No template type';
      //   }
      // },
      {
        targets: 6, // user object
        render: function (data, type, row, meta) {
          return (row.assigned.length > 0) ? row.assigned[0].user.name : 'Still pending';
        }
      },
      // {
      //   targets: 7, // user object
      //   render: function (data, type, row, meta) {
      //     return (row.assigned.length > 0) ? row.assigned[0].date : 'Still pending';
      //   }
      // },
      {
        targets: 7, // user object
        render: function (data, type, row, meta) {
          return (row._created_at) ? row._created_at : '...';
        }
      },
      {
        targets: 8, // user object
        render: function (data, type, row, meta) {
          if (row.amendments.length > 0)
            for (let i = 0; i < row.amendments.length; i++) {
              if (row.amendments[i]) {
                if (row.amendments[i].status === 1 || (row.amendments[i].is_secret == 1 && row.amendments[i].approve_secret == 0))
                  if (row.amendments[i].Priority === 'medium' || row.amendments[i].Priority === 'scheduled')
                    return '<div><i class="text-center d-inline-block text-warning icon-20 cs-warning-hexagon"></i></div>'
                  else
                    return '<div><i class="text-center d-inline-block text-danger icon-20 cs-warning-hexagon"></i></div>'
                else
                  return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
              }
            }
          else
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
        }
      },
      {
        targets: 9,
        render: function (data, type, row, meta) {
          if (row.amendments.length > 0)
            for (let i = 0; row.amendments.length; i++) {
              if (row.amendments[i].status == 1 || (row.amendments[i].is_secret == 1 && row.amendments[i].approve_secret == 0))
                return row.amendments[i].created_at
              else
                return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
            }
          else
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
        }
      },
      // {
      //   targets: 10, // user object
      //   render: function (data, type, row, meta) {
      //     if (row.amendments.length > 0)
      //       for (let i = 0; row.amendments.length; i++) {
      //         // if (row.amendments[i].status === 1)
      //         //   return row.amendments[0].Priority
      //         // else
      //         return row.amendments[0].Priority
      //       }
      //     else
      //       return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
      //   }
      // },
      {
        targets: 10, // user object
        render: function (data, type, row, meta) {
          if (row.amendments.length > 0)
            for (let i = 0; row.amendments.length; i++) {
              // if (row.amendments[i].status === 1)
              //   return row.amendments[0].Priority
              // else
              return row.amendments[0].expected_amount
            }
          else
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
        }
      },
      {
        targets: 11, // check
        render: (data, type, row, meta) => {
          if (row.status == "approved")
            return `<span data-tooltip="` + this.$i18n.t('approved_by_legal_department_and_it_under_processing') + `" data-tooltip-position="top" style="margin: 20px;" class='badge rounded-pill bg-primary'>` + this.$i18n.t("approved") + `</span>`
          else if (row.status == "closed")
            return `<span class='badge rounded-pill bg-success' style="margin: 20px;">` + this.$i18n.t("Closed") + `</span>`
          else if (row.status == "returned")
            return `<span class='badge rounded-pill bg-warning' style="margin: 20px;">` + this.$i18n.t("returned") + `</span>`
          else if (row.status == "processing")
            return `<span class='badge rounded-pill bg-primary' style="margin: 20px;">` + this.$i18n.t("Processing") + `</span>`
          else if (row.status == "rejected")
            return `<span class='badge rounded-pill bg-danger' style="margin: 20px;">` + this.$i18n.t("Rejected") + `</span>`
          else
            return `<span class='badge rounded-pill bg-primary' style="margin: 20px;">` + this.$i18n.t("pending") + `</span>`
          // return (row.status == "approved") ?  `<span data-tooltip="`+this.$i18n.t('approved_by_legal_department_and_it_under_processing')+`" data-tooltip-position="top" style="margin: 20px;" class='badge rounded-pill bg-primary'>`+row.status+`</span>`: `<span class='badge rounded-pill bg-primary' style="margin: 20px;">`+row.status+`</span>`;
        }
      },
      {
        targets: 12, // check
        render: function (data, type, row, meta) {
          return '<div class="form-check float-end mt-1"><input type="checkbox" id="checkInput" class="form-check-input checkInput"></div>';
        }
      },
    ];
    window.datatableServerSideEdit = (e, row) => this.edit(e, row);
    window.datatableServerSideViewMessage = (e, row) => this.showMessage(e, row);
    window.datatableServerSideDelete = rows => this.delete(rows);
    window.datatableServerSideRestore = rows => this.restore(rows);
    this.getDepartments()
    this.getOrganizations()
  },
  methods: {
    filterDataTable() {
      if (typeof window.filter == 'undefined')
        window.filter = [];

      window.filter['department_id'] = this.department_id;
      window.filter['organization_id'] = this.organization_id;
      window.filter['type'] = this.type;

      window.jQueryDatatableReload();
    },
    getRequestId(requestId) {
      this.$axios.get(requestId)
      {
      }
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
    getOrganizations() {
      this.$axios
        .get("admin/organizations")
        .then(response => {
          this.allOrganizations = response.data.data;
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_organizations"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    add() {
      this.id = -1;
      setTimeout(() => {
        this.id = 0;
        window.showModal("#addModal");
      }, 200);
    },
    edit(e, row) {
      if (typeof row != "undefined") {
        // if (row.children.length > 0)
        //   this.id = row.children[0].id
        // else
        // setTimeout(() => {
        this.id = row.id
        this.editModalAction = true
        window.showModal("#editModal")
        // }, 200);
      }
    },
    delete(ids) {
      // console.log(ids)
      this.ids = ids;
      this.$modal.show("DeleteConfirm");
    },
    restore(ids) {
      this.ids = ids;
      this.$modal.show("RestoreDialog");
    },
    showMessage(e, row) {
      if (typeof row != "undefined") {
        setTimeout(() => {
          this.id = row.id;
          window.showModal("#showMessageElement");
        }, 200);
      }
    }
  }
};
</script>
