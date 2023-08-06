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
                  {{ $t("Assigned") }}
                </h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                  <ul class="breadcrumb pt-0">
                    <li class="breadcrumb-item">
                      <nuxt-link :to="{ name: 'home' }">{{
                        $t("Home")
                      }}</nuxt-link>
                    </li>
                  </ul>
                </nav>
              </div>
              <!-- Title End -->

              <!-- Top Buttons Start -->
              <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                <!-- Add New Button Start -->
                <!--                <button-->
                <!--                  type="button"-->
                <!--                  @click="add"-->
                <!--                  class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto"-->
                <!--                >-->
                <!--                  <i data-cs-icon="plus"></i>-->
                <!--                  <span>{{ $t("Add New") }}</span>-->
                <!--                </button>-->
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
                  <!-- Review Button Start -->
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow review-datatable disabled"
                    data-bs-delay="0" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('Show_Request')"
                    type="button" @click="review">
                    <i data-cs-icon="eye"></i>
                  </button>
                  <!-- Review Button End -->


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
                  <!-- <div class="d-inline-block datatable-export" data-datatable="#datatableServerSideUrl">
                    <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                      <span class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown" data-bs-delay="0"
                        data-bs-placement="top" data-bs-toggle="tooltip" :title="$t('Export')">
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
                  </div> -->
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

                  <div class="d-inline-block shadow">
                    <select class="form-select" id="department_id" v-model="department_id" @change="filterDataTable">
                      <option value="">{{ $t("All_departments") }}</option>
                      <option v-for="(department, index) in allDepartments" :key="index" :value="department.id">{{
                        $i18n.locale === 'en' ? department.name_en : department.name_ar }}
                      </option>
                    </select>
                  </div>

                  <div class="d-inline-block shadow">
                    <select class="form-select" id="organization_id" v-model="organization_id" @change="filterDataTable">
                      <option value="">{{ $t("All_organizations") }}</option>
                      <option v-for="(organization, index) in allOrganizations" :key="index" :value="organization.id">{{
                        organization.name }}
                      </option>
                    </select>
                  </div>

                  <div class="d-inline-block shadow">
                    <select class="form-select" id="user_id" v-model="template_id" @change="filterDataTable">
                      <option value="">{{ $t("All_templates") }}</option>
                      <option v-for="(template, index) in templates" :key="index" :value="template.id">{{ ($i18n.locale ==
                        'en') ? template.name :
                        template.ar_name ? template.ar_name : template.name }}
                      </option>
                    </select>
                  </div>

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
                      {{ $t("request_subject") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Assigner") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("organization") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("department") }}
                    </th>
                    <!--                  <th class="text-muted text-small text-uppercase">-->
                    <!--                    {{ $t("type") }}-->
                    <!--                  </th>-->
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("assigned_date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("end_date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("submission_date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Priority") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Approve Secret") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("expected_amount") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("status") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("comment") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("returns") }}
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
          <!-- end request -->
          <div class="modal fade" id="closeRequestModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
            aria-hidden="true">
            <close-request :id="id" :templateId="templateId" v-on:resetID="resetID" v-if="activeModal.close" />
            <button class="invisible closeAndReload"></button>
          </div>

          <!-- Add Modal Start -->
          <div class="modal modal-right fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
            aria-hidden="true">
            <!--            <request-review-dialog :id="id" v-if="activeModal.review" v-on:resetID="resetID"/>-->
            <request-assigned-review-dialog :id="id" v-if="activeModal.review" v-on:resetID="resetID" />
            <button class="invisible closeAndReload"></button>
          </div>
          <!-- Add Modal End -->
          <!-- Add Modal Start -->
          <div class="modal modal-right fade" id="needInfoModal" tabindex="-1" role="dialog"
            aria-labelledby="needInfoModalTitle" aria-hidden="true">
            <more-info :id="id" :templateId="templateId" v-if="activeModal.needinfo" v-on:resetID="resetID" />
            <button class="invisible closeAndReload"></button>
          </div>
          <!-- Add Modal End -->
        </div>
      </div>
    </div>
  </main>
</template>

<script>

import closeRequest from "./closeRequest";
import MoreInfo from "./moreInfo";
import RequestAssignedReviewDialog from "./RequestAssigendReviewDialog";
export default {
  name: "closed",
  components: { RequestAssignedReviewDialog, MoreInfo, closeRequest },
  layout: "admin",
  middleware: ["auth", "admin"],
  head() {
    return {
      title: "closed requests"
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
      templateId: 0,
      ids: [],
      user: {},
      comment: '',
      activeModal: {
        close: true,
        review: true,
        needinfo: true
      },
      allDepartments: [],
      department_id: '',
      allOrganizations: [],
      organization_id: '',
      templates: [],
      template_id: '',
      type: '',
    };
  },
  computed: {
  },
  created() {
    // console.log('User ',this.$auth.state.user.id)
    window.ajaxHeaders = this.$axios.defaults.headers.common;
    window.datatableServerSideUrl =
      this.$axios.defaults.baseURL + "admin/closed-forms";
    window.datatableServerSideColumns = [
      { data: "form_id", orderable: false },
      { data: "form_id" },
      { data: "form_id" },
      { data: "form_id" },
      { data: 'user_id' },
      { data: 'user_id' },
      // {data: 'form_id'},
      { data: 'form_id' },
      { data: 'created_at' },
      { data: 'date' },
      { data: 'form_id' },
      { data: 'form_id' },
      { data: 'form_id' },
      { data: 'form_id' },
      { data: 'form_id' },
      { data: "id", searchable: false },
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
        targets: 2, // user object
        render: function (data, type, row, meta) {
          return (row.form) ? row.form.name : ' ';
        }
      },
      {
        targets: 3, // user object
        render: function (data, type, row, meta) {
          return (row.assigner) ? row.assigner.name : ' ';
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
        render: function (data, type, row, meta) {
          return (row.department) ? row.department.name : 'No department';
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
          return row.created_at;
        }
      },
      {
        targets: 7, // user object
        render: function (data, type, row, meta) {
          return row.date;
        }
      },
      {
        targets: 8, // user object
        render: function (data, type, row, meta) {
          return row._created_at;
        }
      },
      {
        targets: 9, // user object
        render: (data, type, row, meta) => {
          // console.log('Row is', row.amendments[0])
          return (row.amendments.length > 0) ? row.amendments[0].Priority : ' ';
        }
      },
      {
        targets: 10, // user object
        render: (data, type, row, meta) => {
          if (row.amendments.length > 0)
            for (let i = 0; i < row.amendments.length; i++) {
              return (row.amendments[0].is_secret == 1 && row.amendments[0].approve_secret == 0 && row.form.status == 'processing') ? `<span data-tooltip="` + this.$i18n.t('please approve if data sent') + `" data-tooltip-position="top" style="margin: 20px;" class='badge rounded-pill bg-danger'>No</span>`
                : `<span class='badge rounded-pill bg-success' style="margin: 20px;">Yes</span>`
            }
          else
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
        }
      },
      {
        targets: 11, // user object
        render: (data, type, row, meta) => {
          return (row.amendments.length > 0) ? row.amendments[0].expected_amount : ' ';
        }
      },
      {
        targets: 12, // user object
        render: (data, type, row, meta) => {
          // return row.form.status;
          if (row.form.status == "approved")
            return `<span class='badge rounded-pill bg-primary' style="margin: 20px;">` + this.$i18n.t('approved') + `</span>`
          // return `<span data-tooltip="`+this.$i18n.t('approved_by_legal_department_and_it_under_processing')+`" data-tooltip-position="top" style="margin: 20px;" class='badge rounded-pill bg-primary'>`+row.form.status+`</span>`
          else if (row.form.status == "closed")
            return `<span class='badge rounded-pill bg-success' style="margin: 20px;">` + this.$i18n.t("Closed") + `</span>`
          else if (row.form.status == "processing")
            return `<span class='badge rounded-pill bg-primary' style="margin: 20px;">` + this.$i18n.t("Processing") + `</span>`
          else if (row.form.status == "rejected")
            return `<span class='badge rounded-pill bg-danger' style="margin: 20px;">` + this.$i18n.t("Rejected") + `</span>`
          else
            return `<span class='badge rounded-pill bg-primary' style="margin: 20px;">` + this.$i18n.t("pending") + `</span>`

        }
      },
      {
        targets: 13, // user object
        render: function (data, type, row, meta) {
          if (row.amendments.length > 0 || row.form.parent_form)
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-file-text" onclick="this.viewText({row.form.comment})"></i></div>';
          else if (row.form.comment)
            return row.form.comment
          else
            return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
        }
      },
      {
        targets: 14, // check
        render: function (data, type, row, meta) {
          // return (row.amendments.length > 0 && row.amendments[0].status == 1) ?
          //   '<div><i class="text-center d-inline-block text-danger icon-20 cs-warning-hexagon"></i></div>' :
          //   '<div><i class="text-center d-inline-block text-success icon-20 cs-warning-hexagon"></i></div>';
          return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
        }
      },
      {
        targets: 15, // check
        render: function (data, type, row, meta) {
          return '<div class="form-check float-end mt-1"><input type="checkbox" class="form-check-input"></div>';
        }
      },
      {
        targets: 16, // check
        render: function (data, type, row, meta) {
          return row.form.created_at;
        }
      },
    ];
    // window.datatableServerSideEdit = (e, row) => this.edit(e, row);
    window.datatableServerSideReview = (e, row) => this.review(e, row);
    window.datatableServerSideCloseRequest = (e, row) => this.endRequest(e, row);
    window.datatableServerSideApproveSecretRequest = (e, row) => this.approveSecret(e, row);
    window.datatableServerSideNeedInfo = (e, row) => this.needInfo(e, row);
    // window.datatableServerSideDelete = rows => this.delete(rows);
    this.getDepartments()
    this.getFillterFormData()
  },
  methods: {
    filterDataTable() {
      if (typeof window.filter == 'undefined')
        window.filter = [];

      window.filter['department_id'] = this.department_id;
      window.filter['organization_id'] = this.organization_id;
      window.filter['template_id'] = this.template_id;

      window.jQueryDatatableReload();
    },
    getFillterFormData() {
      this.$axios
        .get("admin/forms/getFillterFormData")
        .then(response => {
          this.allOrganizations = response.data.organizations;
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
    needInfo(e, row) {
      console.log(row)
      this.activeModal.assign = false
      this.activeModal.review = false
      this.activeModal.needinfo = true
      this.id = 0
      this.templateId = 0
      if (typeof row != "undefined") {
        setTimeout(() => {
          if (row.form.children.length > 0) {
            this.id = row.form.id
            this.templateId = row.form.template.id
            console.log(this.templateId)
          }
          else {
            this.id = row.form.id;
            this.templateId = row.form.template.id
          }
          window.showModal("#needInfoModal");
        }, 200);
      }
    },
    getRequestId(requestId) {
      this.$axios.get(requestId)
      {
      }
    },
    // add() {
    //   this.id = -1;
    //   setTimeout(() => {
    //     this.id = 0;
    //     window.showModal("#addModal");
    //   }, 200);
    // },
    // edit(e, row) {
    //   if (typeof row != "undefined") {
    //     setTimeout(() => {
    //       this.id = row.id;
    //       window.showModal("#addModal");
    //     }, 200);
    //   }
    // },
    review(e, row) {
      this.activeModal.close = false
      this.activeModal.needinfo = false
      this.activeModal.review = true
      this.$modal.show("addModal")
      this.id = 0
      if (typeof row != "undefined") {
        setTimeout(() => {
          this.id = row.form.id;
          window.showModal("#addModal");
        }, 200);
      }
    },
    viewText(text) {
      console.log('paragraph is : ', text)
    },
    endRequest(e, row) {
      this.activeModal.review = false
      this.activeModal.needinfo = false
      this.activeModal.close = true
      if (typeof row != "undefined") {
        setTimeout(() => {
          this.id = row.form.id;
          this.templateId = row.form.template.id
          window.showModal("#closeRequestModal");
        }, 200);
      }
    },
    approveSecret(e, row) {
      console.log(row)
      window.jQueryStartLoading();
      if (typeof row != "undefined") {
        setTimeout(() => {
          return this.$axios.get('admin/forms/approve-secret', { params: { id: row.amendments[0].id } })
            .then(response => {
              window.jQueryToast(this.$t("Confirm the delivery of the requested information manually"), "success");
              window.jQueryDatatableReload();
              window.jQueryEndLoading()
            }).catch(error => {
              window.jQueryDatatableReload();
              window.jQueryEndLoading()
            })
        }, 200);
      }

    },
    resetID() {
      this.id = 0;
    }
    // delete(ids) {
    //   this.ids = ids;
    //   this.$modal.show("addModal");
    // }
  }
};
</script>
