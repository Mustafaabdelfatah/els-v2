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
                  {{ $t("Activity_logs") }}
                </h1>
                <nav
                  class="breadcrumb-container d-inline-block"
                  aria-label="breadcrumb"
                >
                  <ul class="breadcrumb pt-0">
                    <li class="breadcrumb-item">
                      <nuxt-link :to="{ name: 'home' }">
                        {{ $t("Home") }}</nuxt-link>
                    </li>
                  </ul>
                </nav>
              </div>
              <!-- Title End -->

              <!-- Top Buttons Start -->
              <div
                class="col-12 col-md-5 d-flex align-items-start justify-content-end"
              >
                <!-- Check Button Start -->
<!--                <div class="btn-group ms-1 check-all-container">-->
<!--                  <div-->
<!--                    class="btn btn-outline-primary btn-custom-control p-0 ps-3 pe-2"-->
<!--                    id="datatableCheckAllButton"-->
<!--                  >-->
<!--                    <span class="form-check float-end">-->
<!--                      <input-->
<!--                        type="checkbox"-->
<!--                        class="form-check-input"-->
<!--                        id="datatableCheckAll"-->
<!--                      />-->
<!--                    </span>-->
<!--                  </div>-->
<!--                  <button-->
<!--                    type="button"-->
<!--                    class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"-->
<!--                    data-bs-offset="0,3"-->
<!--                    data-bs-toggle="dropdown"-->
<!--                    aria-haspopup="true"-->
<!--                    aria-expanded="false"-->
<!--                    data-submenu-->
<!--                  ></button>-->
<!--                  <div class="dropdown-menu dropdown-menu-end">-->
<!--                    <button-->
<!--                      class="dropdown-item disabled delete-datatable"-->
<!--                      type="button"-->
<!--                    >-->
<!--                      {{ $t("Delete") }}-->
<!--                    </button>-->
<!--                  </div>-->
<!--                </div>-->
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
                  <!-- Delete Button Start -->
<!--                  <button-->
<!--                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow disabled delete-datatable"-->
<!--                    data-bs-delay="0"-->
<!--                    data-bs-toggle="tooltip"-->
<!--                    data-bs-placement="top"-->
<!--                    :title="$t('Delete')"-->
<!--                    type="button"-->
<!--                  >-->
<!--                    <i data-cs-icon="bin"></i>-->
<!--                  </button>-->
                  <!-- Delete Button End -->
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
                      {{ $t("Description") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Subject_Type") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Subject_Id") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Model") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Properties") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("User_Id") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("User") }}
                    </th>
                    <th class="text-muted text-small text-uppercase">
                      {{ $t("Registered") }}
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
            <button class="invisible" id="addEditConfirmButton"></button>
          </div>
          <!-- Add Modal End -->
        </div>
      </div>
    </div>
    <delete-confirm url="admin/roles" :id="ids" />
  </main>
</template>


<script>
import DeleteConfirm from "../../../components/DeleteConfirm";

export default {
  components: {
    DeleteConfirm
  },
  name: "index",
  layout: "admin",
  middleware: ["auth", "admin"],
  head() {
    return {
      title: "Activity log"
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
    };
  },
  created() {
    window.ajaxHeaders = this.$axios.defaults.headers.common;
    window.datatableServerSideUrl =
      this.$axios.defaults.baseURL + "admin/activitylogs";
    window.datatableServerSideColumns = [
      { data: "description" },
      { data: "subject_type" },
      { data: "subject_id" },
      { data: "causer_type" },
      { data: "properties" },
      { data: "causer_id" },
      { data: "causer_id" },
      { data: "created_at" },
      { data: "Check", searchable: false },
      { data: "id", searchable: false, visible: false }
    ];
    window.datatableServerSideColumnDefs = [
      {
        targets: 4,
        render: function (data, type, row, meta) {
          let badges = ''
          for (let key in row.properties)
          {
            const value = row.properties[key]
             badges += "<span class='badge rounded-pill bg-primary'>"+key+":"+value+"</span>"
            badges += ' '
          }
          return badges
        }
      },
      {
        targets: 6, // user object
        render: function(data, type, row, meta) {
          if(row.user != null){
            return row.user.name
          }
          else{
            return ''
          }
        }
      },
      {
        targets: 8, // check
        render: function(data, type, row, meta) {
          return '<div class="form-check float-end mt-1"><input type="checkbox" class="form-check-input"></div>';
        }
      }
    ];
    window.datatableServerSideDelete = rows => this.delete(rows);
  },
  methods: {
    delete(ids) {
      this.ids = ids;
      this.$modal.show("DeleteConfirm");
    }
  }
};
</script>
