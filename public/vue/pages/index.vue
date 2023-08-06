<template>
  <main>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="page-title-container">
            <h1 class="mb-0 pb-0 display-4" id="title">{{ $t("Analytic_Dashboard") }}</h1>
            <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
              <ul class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a onclick="window.location.href='/'" class="cursor-pointer">{{
                  $t("Home") }}</a></li>
                <!--                <li class="breadcrumb-item"><a href="#">{{ $t("Dashboards")}}</a></li>-->
              </ul>
            </nav>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-lg-12">
          <!-- Stats Start -->
          <div class="d-flex">
            <div class="dropdown-as-select me-3" data-setActive="false" data-childSelector="span">
              <a class="pe-0 pt-0 align-top lh-1 dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"
                aria-haspopup="true">
                <span class="small-title"></span>
              </a>
              <div class="dropdown-menu font-standard">
                <div class="nav flex-column" role="tablist">
                  <a class="active dropdown-item text-medium" href="#" aria-selected="true" role="tab">{{
                    $t("Today_s") }}</a>
                  <a class="dropdown-item text-medium" href="#" aria-selected="false" role="tab">{{ $t("Weekly") }}</a>
                  <a class="dropdown-item text-medium" href="#" aria-selected="false" role="tab">{{ $t("Monthly") }}</a>
                  <a class="dropdown-item text-medium" href="#" aria-selected="false" role="tab">{{ $t("Yearly") }}</a>
                </div>
              </div>
            </div>
            <h2 class="small-title">{{ $t("Stats") }}</h2>
          </div>

          <div class="mb-5" v-if="user.roles.includes('Root') || user.roles.includes('Admin')">
            <div class="row g-2">
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="user" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Users") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.users) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="duplicate" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Requests") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.requests) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="content" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Opened_Requests") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.openedRequests) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="check" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Closed_Requests") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.closedRequests) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-5" v-else>
            <div class="row g-2">
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="content" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Templates") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.templates) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="duplicate" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Requests") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.requests) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="content" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Opened_Requests") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.openedRequests) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="card sh-11 hover-scale-up">
                  <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                      <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-cs-icon="check" class="text-white"></i>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                          <div class="d-flex align-items-center lh-1-25">{{ $t("Closed_Requests") }}</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                          <div class="cta-2 text-primary">{{ (cards.closedRequests) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Stats End -->
        </div>
      </div>

      <!-- Requests Start -->
      <div class="row">
        <div class="col">
          <!-- Title and Top Buttons Start -->
          <div class="page-title-container">
            <div class="row">
              <!-- Title Start -->
              <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4">
                  {{ $t("Requests") }}
                </h1>
              </div>
              <!-- Title End -->
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
                <div class="d-inline-block">
                  <!-- Print Button Start -->
                  <button type="button" @click="addRequest"
                    class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                    <i data-cs-icon="plus"></i>
                    <span>{{ $t("Add_New_Request") }}</span>
                  </button>
                  <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                    data-bs-delay="0" data-datatable="#datatableServerSideUrl" data-bs-toggle="tooltip"
                    data-bs-placement="top" :title="$t('Print')" type="button">
                    <i data-cs-icon="print"></i>
                  </button>

                  <!-- Print Button End -->

                  <!-- Export Dropdown Start -->
                  <div class="d-inline-block datatable-export" data-datatable="#datatableServerSideUrl">
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
                  </div>
                  <!-- Export Dropdown End -->
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
                      {{ $t("request_user") }}
                    </th>
                    <!--                  <th class="text-muted text-small text-uppercase">-->
                    <!--                    {{ $t("type") }}-->
                    <!--                  </th>-->
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("submission_date") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("returned") }}
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
            <button class="invisible" id="addEditConfirmButton"></button>
          </div>
          <!-- Add Modal End -->
          <!--show message coming from assigned user or admin-->
        </div>
      </div>
      <!-- Requests End -->

      <div class="modal modal-right fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
        aria-hidden="true">
        <user-dialog :id="id" v-if="id > -1" v-on:resetID="resetID" />
        <button id="addEditConfirmButton" class="invisible"></button>
      </div>
      <div class="modal modal-right fade" id="addModalRequest" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
        aria-hidden="true">
        <template-dialog :id="2" :home="true"/>
        <button class="invisible" id="addEditConfirmButton"></button>
      </div>


      <div class="row" style="margin-top: 40px" v-if="user.roles.includes('Root') || user.roles.includes('Admin')">
        <div class="col-6">
          <!--          <employees :users="users"></employees>-->
          <!-- Hoverable Rows End -->
        </div>
        <div class="col-6">
          <!-- Striped Rows Start -->
          <!--          <requestes :forms="forms"></requestes>-->
          <!-- Striped Rows End -->
        </div>
      </div>





      <!-- Logs Start -->
      <!--      <div class="row">-->
      <!--        <div class="col-xl-12 col-lg-12 mb-5">-->
      <!--          <h2 class="small-title">{{ $t("Logs")}}</h2>-->
      <!--          <div class="card sh-35 h-xl-100-card">-->
      <!--            <div class="card-body scroll-out">-->
      <!--              <div class="scroll-by-count" data-count="3">-->
      <!--                <div class="row g-0 mb-2">-->
      <!--                  <div  v-for="log in cards.logs">-->
      <!--                    <div class="col-auto">-->
      <!--                      <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">-->
      <!--                        <div class="sh-3">-->
      <!--                          <i data-cs-icon="circle" class="text-primary align-top"></i>-->
      <!--                        </div>-->
      <!--                      </div>-->
      <!--                    </div>-->
      <!--                    <div class="col">-->
      <!--                      <div class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">-->
      <!--                        <div class="d-flex flex-column">-->
      <!--                          <div class="text-alternate mt-n1 lh-1-25">{{ log.description }}</div>-->
      <!--                        </div>-->
      <!--                      </div>-->
      <!--                    </div>-->
      <!--                    <div class="col-auto">-->
      <!--                      <div class="d-inline-block d-flex justify-content-end align-items-center h-100">-->
      <!--                        <div class="text-muted ms-2 mt-n1 lh-1-25">{{ log.created_at }}</div>-->
      <!--                      </div>-->
      <!--                    </div>-->
      <!--                  </div>-->
      <!--                </div>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </div>-->
      <!--        </div>-->
      <!--      </div>-->
      <!-- Logs End -->

      <change-password-dialog />
    </div>
  </main>
</template>

<script>
import ChangePasswordDialog from "../components/ChangePassword";
import UserDialog from "../components/users/UserDialog";
import TemplateDialog from "./requests/TemplateDialog";

import { mapGetters } from "vuex";
import Employees from "./includes/employees";
import Requestes from "./includes/requestes";
export default {
  components: {
    Requestes, Employees,
    UserDialog,
    TemplateDialog,
    ChangePasswordDialog
  },
  layout: 'admin',
  middleware: ['auth', 'admin'],
  data() {
    return {
      cards: {},
      users: [],
      forms: [],
    };
  },
  computed: {
    ...mapGetters(["loggedIn", "user", "roles", "userPermissions"]),
  },
  created() {
    this.reportCards()
    this.getRequests()
    this.getEmployees()
    this.getForms()
  },
  methods: {
    getRequests() {
      if (this.user.roles.includes('Root')) {
        window.ajaxHeaders = this.$axios.defaults.headers.common;
        window.datatableServerSideUrl =
          this.$axios.defaults.baseURL + "admin/forms";
        window.datatableServerSideColumns = [
          { data: "id", orderable: false },
          { data: "id" },
          { data: "name" },
          { data: 'user_id' },
          // {data: 'template_id'},
          { data: 'template_id' },
          { data: 'created_at' },
          { data: 'template_id' },
          { data: 'status' },
          { data: 'template_id' },
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
          // {
          //   targets: 4, // user object
          //   render: function (data, type, row, meta) {
          //     return (row.template) ? row.template.type : 'No template type';
          //   }
          // },
          {
            targets: 4, // user object
            render: function (data, type, row, meta) {
              return (row._created_at) ? row._created_at : '...';
            }
          },
          {
            targets: 5, // user object
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
            targets: 6, // user object
            render: function (data, type, row, meta) {
              // if (row.amendments.length > 0)
              //   for (let i = 0; row.amendments.length; i++) {
              //     // if (row.amendments[i].status === 1)
              //     //   return row.amendments[0].Priority
              //     // else
              //     return row.amendments[0].Priority
              //   }
              // else
              //   return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
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
            targets: 7, // user object
            render: function (data, type, row, meta) {
              // if (row.amendments.length > 0)
              //   for (let i = 0; row.amendments.length; i++) {
              //     // if (row.amendments[i].status === 1)
              //     //   return row.amendments[0].Priority
              //     // else
              //     return row.amendments[0].expected_amount
              //   }
              // else
              //   return '<div><i class="text-center d-inline-block text-primary icon-20 cs-more-horizontal"></i></div>'
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
            targets: 8,
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
        ];
      } else {
        window.ajaxHeaders = this.$axios.defaults.headers.common;
        window.datatableServerSideUrl =
          this.$axios.defaults.baseURL + "admin/forms?review=me";
        window.datatableServerSideColumns = [
          { data: "id", orderable: false },
          { data: "id" },
          { data: "name" },
          { data: 'user_id' },
          // {data: 'template_id'},
          { data: 'template_id' },
          { data: 'created_at' },
          { data: 'template_id' },
          { data: 'status' },
          { data: 'template_id' },
          { data: "created_at", searchable: false, visible: false },
          { data: "deleted_at", searchable: false, visible: false },
          { data: "id", searchable: false, visible: false },
        ];
        window.datatableServerSideColumnDefs = [
          {
            targets: 0, // user object
            render: function (data, type, row, meta) {
              return row.index
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
          // {
          //   targets: 4, // user object
          //   render: function (data, type, row, meta) {
          //     return (row.template) ? row.template.type : 'No template type';
          //   }
          // },
          {
            targets: 4, // user object
            render: function (data, type, row, meta) {
              return (row._created_at) ? row._created_at : '...';
            }
          },
          {
            targets: 5, // user object
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
            targets: 6, // user object
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
            targets: 7, // user object
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
            targets: 8,
            render: (data, type, row, meta) => {
              // return (row.status == "approved") ?  `<span data-tooltip="`+this.$i18n.t('approved_by_legal_department_and_it_under_processing')+`" data-tooltip-position="top" style="margin: 20px;" class='badge rounded-pill bg-primary'>`+row.status+`</span>`: `<span class='badge rounded-pill bg-primary' style="margin: 20px;">`+row.status+`</span>`;
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
            }
          },
        ];
      }
    },
    addRequest() {
      this.id = -1;
      setTimeout(() => {
        this.id = 0;
        window.showModal("#addModalRequest");
      }, 200);
    },
    getEmployees() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/reports/getEmployees")
        .then(response => {
          this.users = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryEndLoading();
        });
    },
    getForms() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/reports/getForms")
        .then(response => {
          this.forms = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryEndLoading();
        });
    },
    reportCards() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/reports/reportCards")
        .then(response => {
          this.cards = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryEndLoading();
        });
    },
    requestHome() {
      this.$router.push({ path: "/requests" });
    }
  }
}

</script>
