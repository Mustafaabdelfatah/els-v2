<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignModalTitle">
          {{ $t("Assign_Request") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cancel"></button>
      </div>
      <div class="modal-body">
        <form id="validationTopLabel" class="tooltip-end-top">
          <div class="mb-3 top-label">
            <label class="form-label">{{ $t("User") }}</label>
            <!-- <select class="form-control" v-model="assignRequest.user_id">
                <option
                  v-for="(user, index) in users"
                  :key="index"
                  :value="user.id"
                >{{ user.name }}</option>
              </select> -->
            <v-select v-model="assignRequest.user_id" :options="users" label="name" :reduce="user => user.id">
            </v-select>
            <!-- <v-select v-model="user_id" :options="employees" label="name" :reduce="employee => employee.id"
                      :clearable="true" @input="filterDataTable" :placeholder="$t('All_Employees')" class="custom-select"
                searchable></v-select> -->

            <span>{{ $t("User") }}</span>
            <div class="error" v-if="!!validationErrors.user_id">
              {{ validationErrors.user_id[0] }}
            </div>
          </div>
          <div class="mb-3 top-label">
            <input type="date" class="form-control" v-model="assignRequest.date" placeholder="Date"
              :min="assignRequest.date" />
            <span>{{ $t("expiry_date") }}</span>
            <div class="error" v-if="!!validationErrors.date">
              {{ validationErrors.date[0] }}
            </div>
          </div>
          <modal name="RejectModal" :width="1000" :height="300" :clickToClose="false" :focusTrap="true" :draggable="true"
            :adaptive="true">
            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-body text-center">
                <div class="mb-3 top-label">
                  <textarea name="date" type="text" class="form-control" v-model="notes"
                    :placeholder="$t('Reject_Note')" />
                  <span>Notes</span>
                  <div class="error" v-if="!!validationErrors.notes">
                    {{ validationErrors.notes[0] }}
                  </div>
                </div>
                <div class="mt-4 pt-2">
                  <button type="button" class="btn btn-danger btn-sm" @click="reject()">
                    {{ $t("Reject") }}
                  </button>
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast"
                    @click="$modal.hide('RejectModal')">
                    Close
                  </button>
                </div>
              </div>
            </div>
          </modal>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="cancel">
          {{ $t("Cancel") }}
        </button>
        <button type="button" :disabled="isDisable" class="btn btn-primary" @click="assign">
          {{ $t("Approve") }}
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" @click="rejectForm">
          {{ $t("Reject") }}</button>

      </div>
    </div>

  </div>
</template>

<script>
import assigned from "../../requests/assigned";

export default {
  name: "AssignDialog",
  props: {
    id: {
      type: Number,
      required: true
    },
    ids: Array,
    requesterIds: Array,
    permissions: Array,
  },
  data() {
    return {
      typing: false,
      validationErrors: {},
      assignRequest: {
        user_id: "",
        date: new Date().toISOString().slice(0, 10),
        form_id: ""
      },
      notes: "",
      users: [],
      allOrganizations: []
    };
  },
  watch: {
    // 'id'() {
    //   if (this.id){
    //     this.getUsers();
    //     this.getAssignedRequest();
    //   }
    // },
    'requesterIds'() {
      this.getUsers();
      // if (this.id){
      //   this.getAssignedRequest();
      // }
    }
  },
  computed: {
    isDisable() {
      return this.assignRequest.user_id === "" || this.assignRequest.date === "";
    },
    userPermission() {
      let Permissions = []
      if (this.users.length > 0)
        this.users.filter(user => {
          Permissions.push(user.permissions)
        })
      return Permissions
    }
  },
  methods: {
    getAssignedRequest() {
      window.jQueryStartLoading();
      this.$axios
        .get("admin/assigned-forms/" + this.id)
        .then(response => {
          this.assignRequest = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_Assigned_request"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    getUsers() {
      console.log(this.assignRequest.date)
      window.jQueryStartLoading();
      this.$axios
        .get("admin/legalUsers", {
          params: {
            requesterIds: this.requesterIds,
            permissions: this.permissions
          }
        })
        .then(response => {
          this.users = response.data;
          window.jQueryEndLoading();
        }).then(() => {
          if (this.id) {
            this.getAssignedRequest();
          }
        })
        .catch(() => {
          window.jQueryToast(this.$t("Failed_to_load_users"), "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    cancel() {
      this.assignRequest = {
        user_id: [],
        date: new Date().toISOString().slice(0, 10),
        form_id: ""
      };
      this.$emit('resetID');
    },
    assign() {

      // console.log(this.id,this.assignRequest.form_id,this.assignRequest.user_id);
      window.jQueryStartLoading();
      this.validationErrors = {};
      // if (this.id > 0) {
      //   this.$axios
      //     .put("admin/forms/update-assigned-request", {
      //       id: this.id,
      //       form_id: this.assignRequest.form_id,
      //       user_id: this.assignRequest.user_id,
      //       date: this.assignRequest.date,
      //     })
      //     .then(response => {
      //       this.assignRequest = {}
      //       window.jQueryToast(this.$t("Assigned_Successfully"), "success");
      //       window.jQueryDatatableReload();
      //       window.jQueryEndLoading();
      //     })
      //     .catch(error => {
      //       if (typeof error.response.data.errors != "undefined")
      //         this.validationErrors = error.response.data.errors;
      //       window.jQueryToast(this.$t("Validation_Error"), "danger");
      //       window.jQueryEndLoading();
      //     });
      // } else {
      this.$axios
        .post("admin/forms/assign-request", {
          form_id: this.ids,
          user_id: this.assignRequest.user_id,
          date: this.assignRequest.date,
        })
        .then(response => {
          this.assignRequest = {
            user_id: "",
            date: new Date().toISOString().slice(0, 10),
            form_id: ""
          }
          window.jQueryToast(this.$t("Assigned_Successfully"), "success");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        })
        .catch(error => {
          if (typeof error.response.data.errors != "undefined")
            this.validationErrors = error.response.data.errors;
          window.jQueryToast(this.$t("Validation_Error"), "danger");
          window.jQueryEndLoading();
        });
      // }
    },
    reject() {
      window.jQueryStartLoading();
      this.validationErrors = {};
      this.$axios
        .post("admin/forms/reject-request", {
          form_id: this.ids,
          notes: this.notes,
        })
        .then(response => {
          window.jQueryToast(this.$t("Rejected_Successfully"), "success");
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
    rejectForm() {
      this.$modal.show("RejectModal");
    }
  }
};
</script>
