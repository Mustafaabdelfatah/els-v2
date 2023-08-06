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
                  {{ $t("Request_Reviews") }}
                </h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
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
              <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
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
                    <button class="dropdown-item disabled assign-datatable" type="button" @click="assign">
                      {{ $t("Assign_Request") }}
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
                <div class="d-flex flex-wrap align-items-center">
                  <div class="me-2">
                    <!-- Add Button Start -->
                    <button
                      class="btn btn-icon btn-sm btn-icon-only btn-foreground-alternate shadow assign-datatable disabled"
                      data-bs-delay="0" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Assign_Request')"
                      type="button" @click="assign">
                      <i data-cs-icon="at-sign"></i>
                    </button>
                    <!-- Add Button End -->
                  </div>
                  <div class="me-2">
                    <!-- Review Button Start -->
                    <button
                      class="btn btn-icon btn-sm btn-icon-only btn-foreground-alternate shadow review-datatable disabled"
                      data-bs-delay="0" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Show_Request')"
                      type="button" @click="review">
                      <i data-cs-icon="eye"></i>
                    </button>
                    <!-- Review Button End -->
                  </div>
                  <div class="me-2">
                    <!-- Print Button Start -->
                    <button class="btn btn-icon btn-sm btn-icon-only btn-foreground-alternate shadow datatable-print"
                      data-bs-delay="0" data-datatable="#datatableServerSideUrl" data-bs-toggle="tooltip"
                      data-bs-placement="top" :title="$t('Print')" type="button">
                      <i data-cs-icon="print"></i>
                    </button>
                    <!-- Print Button End -->
                  </div>
                  <div class="dropdown-as-select me-2" data-datatable="#datatableServerSideUrl" data-childSelector="span">
                    <!-- Length Start -->
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
                  <div class="me-2 datatable-export" data-datatable="#datatableServerSideUrl">
                    <!-- Export Dropdown Start -->
                    <button
                      class="d-inline-block  dropdown-item export-excel btn btn-icon btn-icon-only btn-sm btn-foreground-alternate shadow dropdown">
                      <i data-cs-icon="download"></i>
                    </button>
                    <!-- <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                        <span class="btn btn-icon btn-icon-only btn-sm btn-foreground-alternate shadow dropdown"
                          data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" :title="$t('Export')">
                          <i data-cs-icon="download"></i>
                        </span>
                      </button> -->
                    <!-- Export Dropdown End -->
                  </div>
                  <div class="me-2">
                    <!-- Status Select Start -->
                    <select class="form-select custom-width" id="status" v-model="status" @change="filterDataTable">
                      <option value="">{{ $t("AllStatus") }}</option>
                      <option value="pending">{{ $t("pending") }}</option>
                      <option value="processing">{{ $t("Processing") }}</option>
                      <option value="rejected">{{ $t("Rejected") }}</option>
                      <option value="closed">{{ $t("Closed") }}</option>
                    </select>
                    <!-- Status Select End -->
                  </div>
                  <div class="me-2">
                    <!-- Department Select Start -->
                    <select class="form-select custom-big-width" id="department_id" v-model="department_id"
                      @change="filterDataTable">
                      <option value="">{{ $t("All_departments") }}</option>
                      <option v-for="(department, index) in allDepartments" :key="index" :value="department.id">
                        {{ $i18n.locale === 'en' ? department.name_en : department.name_ar }}
                      </option>
                    </select>
                    <!-- Department Select End -->
                  </div>
                  <div class="me-2">
                    <!-- Organization Select Start -->
                    <select class="form-select custom-big-width" id="organization_id" v-model="organization_id"
                      @change="filterDataTable">
                      <option value="">{{ $t("All_organizations") }}</option>
                      <option v-for="(organization, index) in allOrganizations" :key="index" :value="organization.id">
                        {{ organization.name }}
                      </option>
                    </select>
                    <!-- Organization Select End -->
                  </div>
                  <div class="me-2">
                    <!-- User Select Start -->
                    <v-select v-model="user_id" :options="employees" label="name" :reduce="employee => employee.id"
                      :clearable="true" @input="filterDataTable" :placeholder="$t('All_Employees')" class="custom-select"
                      searchable input-class="custom-input"></v-select>

                    <!-- User Select End -->
                  </div>
                  <div class="me-2">
                    <!-- Start Date Input Start -->
                    <div class="input-container">
                      <label for="start_date"> {{ $t("from") }} :</label>
                      <input type="date" id="start_date" class="form-control custom-big-width" v-model="start_date"
                        @change="filterDataTable" />
                    </div>
                    <!-- Start Date Input End -->
                  </div>
                  <div class="me-2">
                    <!-- End Date Input Start -->
                    <div class="input-container">
                      <label for="to_date"> {{ $t("to") }} :</label>
                      <input type="date" id="to_date" class="form-control custom-big-width" v-model="to_date"
                        @change="filterDataTable" />
                    </div>
                    <!-- End Date Input End -->
                  </div>
                  <div class="me-2">
                    <!-- Priority Select Start -->
                    <select required id="selectBasic" class="form-select custom-big-width" @change="filterDataTable"
                      v-model="priority">
                      <!-- <option value="" selected>asdsa</option> -->
                      <option :value="undefined">{{ $t("AllPriority") }}</option>
                      <option value="very_urgent">{{ $t("Very_Urgent") }}</option>
                      <option value="urgent">{{ $t("Urgent") }}</option>
                      <option value="medium">{{ $t("Medium") }}</option>
                      <option value="scheduled ">{{ $t("Scheduled") }}</option>
                    </select>
                    <!-- Priority Select End -->
                  </div>
                  <div class="me-2">
                    <!-- Template Select Start -->
                    <select class="form-select custom-big-width" id="user_id" v-model="template_id"
                      @change="filterDataTable">
                      <option value="">{{ $t("All_templates") }}</option>
                      <option v-for="(template, index) in templates" :key="index" :value="template.id">
                        {{ ($i18n.locale == 'en') ? template.name : template.ar_name ? template.ar_name : template.name }}
                      </option>
                    </select>
                    <!-- Template Select End -->
                  </div>
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
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("assigned_date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("submission_date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("returned") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("returned_time") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Priority") }}
                    </th>
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
            <request-assigned-review-dialog :id="id" v-if="activeModal.review" v-on:resetID="resetID" />
            <button class="invisible closeAndReload"></button>
          </div>
          <!-- Add Modal End -->

          <!-- Assign Modal Start -->
          <div class="modal modal-right fade" id="assignModal" tabindex="-1" role="dialog"
            aria-labelledby="assignModalTitle" aria-hidden="true">
            <assign-dialog :ids="ids" :requesterIds="requesterIds" :permissions="permissions" :id="id"
              v-if="activeModal.assign" v-on:resetID="resetID" />
            <button class="invisible" id="assignButton"></button>
          </div>
          <!-- Assign Modal End -->
        </div>
      </div>
    </div>
    <!--    <delete-confirm url="admin/forms/assigned" :id="ids"/>-->
    <modal name="DeleteConfirm" :width="400" :height="140" :clickToClose="false" :focusTrap="true" :draggable="true"
      :adaptive="true">
      <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-center">
          {{ $t("Are_you_sure_that_you_want_to_delete") }}
          <div class="mt-4 pt-2">
            <button type="button" class="btn btn-danger btn-sm" @click="removeAssigned()">
              {{ $t("Delete") }}
            </button>
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast"
              @click="$modal.hide('DeleteConfirm')">
              {{ $t("Close") }}
            </button>
          </div>
        </div>
      </div>
    </modal>
  </main>
</template>

<script>
import AssignDialog from "./AssignDialog";
import DeleteConfirm from "../../../components/DeleteConfirm";
import _ from "lodash";
import RequestAssignedReviewDialog from "../../requests/RequestAssigendReviewDialog";
export default {
  name: "index",
  components: { RequestAssignedReviewDialog, DeleteConfirm, AssignDialog },
  layout: "admin",
  middleware: ["auth", "admin"],
  head() {
    return {
      title: "Request Reviews"
    };
  },
  watch: {
    // loading(newValue) {
    //   this.$store.commit("setLoading", newValue)
    // },
  },
  data() {
    return {
      start_date: null,
      to_date: null,
      Priority: undefined,
      loading: false,
      row: {},
      id: 0,
      activeModal: {
        assign: true,
        review: true
      },
      ids: [],
      users: [],
      requesterIds: [],
      permissions: [],
      user: {},
      allDepartments: [],
      templates: [],
      department_id: '',
      allOrganizations: [],
      organization_id: '',
      template_id: '',
      type: '',
      employees: [],
      user_id: '',
      status: '',
      // showModal:{
      //   assignModal : true,
      //   addModal : true,
      // }
    };
  },
  computed: {
  },
  created() {
    // console.log('User ',this.$auth.state.user.id)
    window.ajaxHeaders = this.$axios.defaults.headers.common;
    window.datatableServerSideUrl =
      this.$axios.defaults.baseURL + "admin/forms";
    window.datatableServerSideColumns = [
      { data: "id", orderable: false },
      { data: "id" },
      { data: "name" },
      { data: 'user_id' },
      { data: 'user_id' },
      { data: 'user_id', searchable: false },
      { data: 'template_id' },
      { data: 'template_id' },
      { data: 'created_at' },
      { data: 'template_id' },
      { data: 'template_id' },
      { data: 'template_id' },
      { data: 'template_id' },
      { data: 'status' },
      { data: "Check", searchable: false },
      { data: "created_at", searchable: false, visible: false },
      { data: "id", searchable: false, visible: false }
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
        render: (data, type, row, meta) => {
          let userAssignName
          let userAvater
          if (typeof row.assigned != "undefined" && row.assigned.length > 0) {
            for (let i = 0; row.assigned.length; i++) {
              if (row.assigned[i]) {
                if (row.assigned[i].status === 'active') {
                  userAssignName = row.assigned[i].user.name
                  userAvater = row.assigned[i].user.avatar
                  if (userAvater === null) {
                    userAvater = '/img/user.jpg'
                  }
                  return `<div class="click-to-top"> <img style='border-radius: 50px' height='60px' width='60px'  src=` + userAvater + `>
                         <span>`+ userAssignName + `</span>
                      </div>`
                }
              }
            }
          } else if (row.status === 'rejected') {
            return `<span class='badge rounded-pill bg-danger'>Rejected</span>`
          }
          else {
            userAssignName = 'Not Assign'
            return userAssignName
          }
        }
      },
      {
        targets: 7, // user object
        render: function (data, type, row, meta) {
          let assignDate
          if (typeof row.assigned != "undefined" && row.assigned.length > 0) {
            return row._assigned_at
            // for (let i = 0; row.assigned.length; i++) {
            //   if (row.assigned[i]) {
            //     if (row.assigned[i].status === 'active') {
            //       assignDate = row.assigned[i].date
            //       return `<span class='badge rounded-pill bg-dark'>` + assignDate + `</span>`
            //     }
            //   }
            // }
          } else if (row.status === 'rejected') {
            return `<span class='badge rounded-pill bg-danger'>Rejected</span>`
          } else {
            assignDate = 'Not Assign'
            return assignDate
          }
        }
      },
      {
        targets: 8, // user object
        render: function (data, type, row, meta) {
          return (row._created_at) ? row._created_at : '...';
        }
      },
      {
        targets: 9, // user object
        render: function (data, type, row, meta) {
          let amendmentsRequest = Object
          if (typeof row.amendments != "undefined" && row.amendments.length > 0) {
            for (let i = 0; i < row.amendments.length; i++) {
              if (row.amendments[i]) {
                if (row.amendments[i].status == 1 || (row.amendments[i].is_secret == 1 && row.amendments[i].approve_secret == 0)) {
                  amendmentsRequest = Object.assign(row.amendments[i])
                  if (row.amendments[i].Priority === 'medium' || row.amendments[i].Priority === 'scheduled')
                    return '<div><i class="text-center d-inline-block text-warning icon-20 cs-warning-hexagon"></i></div>'
                  else
                    return '<div><i class="text-center d-inline-block text-danger icon-20 cs-warning-hexagon"></i></div>'
                }
                else {
                  return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
                }
              } else {
                return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
              }
            }
          } else {
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
          }
        }
      },
      {
        targets: 10, // user object
        render: function (data, type, row, meta) {
          let amendmentsRequest = Object
          if (typeof row.amendments != "undefined" && row.amendments.length > 0) {
            for (let i = 0; i < row.amendments.length; i++) {
              if (row.amendments[i]) {
                if (row.amendments[i].status == 1 || (row.amendments[i].is_secret == 1 && row.amendments[i].approve_secret == 0)) {
                  amendmentsRequest = Object.assign(row.amendments[i])
                  return row.amendments[i].created_at;
                }
                else {
                  return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
                }
              } else {
                return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
              }
            }
          } else {
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
          }
        }
      },
      {
        targets: 11, // user object
        render: function (data, type, row, meta) {
          let amendmentsRequest = Object
          if (typeof row.amendments != "undefined" && row.amendments.length > 0) {
            for (let i = 0; i < row.amendments.length; i++) {
              if (row.amendments[i]) {
                if (row.amendments[i].status == 1 || (row.amendments[i].is_secret == 1 && row.amendments[i].approve_secret == 0)) {
                  amendmentsRequest = Object.assign(row.amendments[i])
                  return `<span class='badge rounded-pill bg-dark'>` + amendmentsRequest.Priority + `</span>`
                }
                else {
                  return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
                }
              } else {
                return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>';
              }
            }
          } else {
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>';
          }
        }
      },
      {
        targets: 12, // user object
        render: function (data, type, row, meta) {
          let amendmentsRequest = Object
          if (typeof row.amendments != "undefined" && row.amendments.length > 0) {
            for (let i = 0; i < row.amendments.length; i++) {
              if (row.amendments[i]) {
                if (row.amendments[i].status == 1 || (row.amendments[i].is_secret == 1 && row.amendments[i].approve_secret == 0)) {
                  amendmentsRequest = Object.assign(row.amendments[i])
                  return `<span class='badge rounded-pill bg-dark'>` + amendmentsRequest.expected_amount + `</span>`
                }
                else {
                  return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
                }
              } else {
                return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>';
              }
            }
          } else {
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>';
          }
        }
      },
      {
        targets: 13, // check
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
            return this.$i18n.locale
          // return `<span class='badge rounded-pill bg-primary' style="margin: 20px;">` + this.$i18n.t("pending") + `</span>`
          // return (row.status == "approved") ?  `<span data-tooltip="`+this.$i18n.t('approved_by_legal_department_and_it_under_processing')+`" data-tooltip-position="top" style="margin: 20px;" class='badge rounded-pill bg-primary'>`+row.status+`</span>`: `<span class='badge rounded-pill bg-primary' style="margin: 20px;">`+row.status+`</span>`;
        }
      },
      {
        targets: 14, // check
        render: function (data, type, row, meta) {
          return '<div class="form-check float-end mt-1"><input type="checkbox" class="form-check-input"></div>';
        }
      },
    ];
    window.datatableServerSideEdit = (e, row) => this.edit(e, row);
    window.datatableServerSideReview = (e, row) => this.review(e, row);
    window.datatableServerSideAssign = (row, rows, requesterIds, permissions) => this.assign(row, rows, requesterIds, permissions);
    window.datatableServerSideDelete = rows => this.delete(rows);
    this.getDepartments()
    // this.getOrganizations()
    // this.getEmployeeUsers()
    this.getFillterFormData()
  },
  methods: {
    filterDataTable() {
      if (typeof window.filter == 'undefined')
        window.filter = [];
      window.filter['department_id'] = this.department_id;
      window.filter['organization_id'] = this.organization_id;
      window.filter['status'] = this.status;
      window.filter['user_id'] = this.user_id;
      window.filter['template_id'] = this.template_id;
      window.filter['start_date'] = this.start_date; // Add start_date filter
      window.filter['to_date'] = this.to_date; // Add to_date filter
      window.filter['priority'] = this.priority; // Add to_date filter
      window.jQueryDatatableReload();
    },

    getFillterFormData() {
      this.$axios
        .get("admin/forms/getFillterFormData")
        .then(response => {
          console.log(response);
          this.allOrganizations = response.data.organizations;
          this.employees = response.data.employees;
          this.templates = response.data.templates;
        })
        .catch(() => {
          // window.jQueryToast(this.$t("Failed_to_load_departments"), "danger");
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
    // getOrganizations() {
    //   this.$axios
    //     .get("admin/organizations")
    //     .then(response => {
    //       this.allOrganizations = response.data.data;
    //     })
    //     .catch(() => {
    //       window.jQueryToast(this.$t("Failed_to_load_organizations"), "danger");
    //       window.jQueryDatatableReload();
    //       window.jQueryEndLoading();
    //     });
    // },
    // getEmployeeUsers() {
    //   window.jQueryStartLoading();
    //   this.$axios
    //     .get("admin/forms/employees")
    //     .then(response => {
    //       this.employees = response.data.data;
    //       window.jQueryEndLoading();
    //     })
    //     .catch(() => {
    //       window.jQueryToast(this.$t("Failed_to_load_users"), "danger");
    //     window.jQueryDatatableReload();
    //     window.jQueryEndLoading();
    //   });
    // },
    edit(e, row) {
      this.activeModal.assign = true
      this.activeModal.review = false
      if (typeof row != "undefined") {
        setTimeout(() => {
          this.id = row.assigned.id;
          window.showModal("#assignModal");
        }, 200);
      }
      // else{
      //     window.jQueryToast(this.$t("Request Not Assigned!"), "danger");
      // }
    },
    review(e, row) {
      this.activeModal.assign = false
      this.activeModal.review = true
      this.id = 0
      if (typeof row != "undefined") {
        setTimeout(() => {
          this.id = row.id;
          window.showModal("#addModal");
        }, 200);
      }
    },
    assign(row, ids, requesterIds, permissions) {
      // console.log('row is :',row.assigned.length)
      this.activeModal.assign = true
      this.activeModal.review = false
      if (typeof ids != "undefined") {
        this.id = 0;
        setTimeout(() => {
          if (row.assigned.length > 0) {
            this.id = row.assigned[0].id
            // console.log('id',this.id)
          }
          this.ids = ids;
          this.requesterIds = requesterIds;
          this.permissions = permissions;
          window.showModal("#assignModal");
        }, 200);
      }
    },
    delete(ids) {
      this.ids = ids;
      this.$modal.show("DeleteConfirm");
    },
    removeAssigned() {
      window.jQueryStartLoading();
      if (this.ids < 1 && !this.ids.length) {
        window.jQueryEndLoading();
        window.jQueryToast(this.$t("Not_Found"), "danger");
        window.jQueryDatatableReload();
        return;
      }
      this.$axios({
        method: 'POST',
        data: this.ids,
        url: '/admin/forms/assigned'
      }).then(response => {
        window.jQueryEndLoading();
        window.jQueryToast(this.$t("Deleted_Successfully"), "success");
        window.jQueryDatatableReload();
        this.$emit("callback", -1);
        this.$modal.hide("DeleteConfirm");
      })
        .catch(() => {
          window.jQueryToast(
            this.$t("Failed_to_delete_selected_records"),
            "warning"
          );
          window.jQueryEndLoading();
          window.jQueryDatatableReload();
        });
    },
    resetID() {
      this.id = 0;
    }
  }
};
</script>

<style>
.custom-select .vs__search {
  font-size: 12px;
  padding: 4px 0 8px
}

.vs__dropdown-toggle {
  border: 1px solid #ddd;
  border-radius: 8px !important;
  padding: 0;
}
</style>
<style scoped>
.custom-select {
  width: 120px;
}



.custom-input input {
  font-size: 10px !important
}

.custom-width {
  width: 95px;
  font-size: 11px;
}

.custom-big-width {
  width: 97px;
  font-size: 11px;
}

.input-container {
  display: flex;
  align-items: center;
}

.input-container label {
  margin-right: 0.5rem;
}

.vs__dropdown-toggle {
  border: 1px solid #ddd;
  border-radius: 8px !important;
  padding: 0;
}
</style>
