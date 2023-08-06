<template>
  <!-- Login Start -->
  <div class="modal-dialog" style="max-width: 50% !important; height: auto;">
    <div class="modal-content h-100">
      <div class="modal-header">
        <h5 class="modal-title">{{ $t("assign_admin_to_template") }}</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <section class="scroll-section" id="login">
          <form class="card mb-5 tooltip-end-top" id="loginForm" novalidate>
            <div class="card-body">
              <p class="mb-4">{{ $t('Template') }}</p>
              <div class="mb-3 filled">
                <i data-cs-icon=""></i>
                <input class="form-control" disabled v-model="template.name" />
              </div>
<!--              <div class="mb-3 filled">-->
<!--                <multiselect v-model="template.organizations" tag-placeholder="Add this as new tag"-->
<!--                             :placeholder="$t('organization')"-->
<!--                             label="name"-->
<!--                             track-by="id"-->
<!--                             :options="organizations"-->
<!--                             :multiple="true"-->
<!--                             :taggable="true"-->
<!--                ></multiselect>-->
<!--              </div>-->
<!--              <p class="mb-4">{{ $t('Organizations') }}</p>-->
              <div v-if="!admins">
<!--                <div class="mb-3 filled">-->
<!--                  <multiselect v-model="template.selectedOrganizations" tag-placeholder="Add this as new tag"-->
<!--                               :placeholder="$t('organization')"-->
<!--                               label="name"-->
<!--                               track-by="id"-->
<!--                               :options="organizations"-->
<!--                               :multiple="true"-->
<!--                               :taggable="true"-->
<!--                  ></multiselect>-->
<!--                </div>-->
                <p class="mb-4">{{ $t('Users') }}</p>
                <div class="row" style="padding: 10px; border-bottom: 3px #BFBFBF; border-bottom:ridge;"  v-for="(selectedAdmin, counter) in selectedAdmins">
                  <div class="col-10">
                    <div class="mb-3 top-label">
                      <label class="form-label">{{ $t("organization") }}</label>
                      <select class="form-control" v-model="selectedAdmin.organization_id" @change="getUsers($event)" required>
                        <option
                          v-for="(organization, organizationIndex) in organizations"
                          :key="organizationIndex"
                          :value="organizations[organizationIndex].id"
                        >{{ organization.name }}
                        </option>
                      </select>
                    </div>
                    <div class="mb-3 filled">
                      <multiselect v-model="selectedAdmin.admins" tag-placeholder="Add this as new tag"
                                   :placeholder="$t('Assign_admin')"
                                   label="name"
                                   track-by="id"
                                   :options="employees"
                                   :multiple="true"
                                   :taggable="true"
                      ></multiselect>
                    </div>
                    <div class="mb-3 filled">
                      <multiselect v-model="selectedAdmin.organizations" tag-placeholder="Add this as new tag"
                                   :placeholder="$t('organization')"
                                   label="name"
                                   track-by="id"
                                   :options="organizations"
                                   :multiple="true"
                                   :taggable="true"
                      ></multiselect>
                    </div>
                  </div>
                  <div class="col-2">
                    <span class="material-icons text-primary col-1 cursor-pointer" v-if="counter" @click="deleteOrg(counter)">
                      remove circle
                    </span>
                    <span class="material-icons text-success col-1 cursor-pointer" v-else @click="addOrg">
                      add circle
                    </span>
                  </div>
                </div>
              </div>



              <div v-else>
<!--                <div class="mb-3 filled">-->
<!--                  <multiselect v-model="template.selectedOrganizations" tag-placeholder="Add this as new tag"-->
<!--                               :placeholder="$t('organization')"-->
<!--                               label="name"-->
<!--                               track-by="id"-->
<!--                               :options="organizations"-->
<!--                               :multiple="true"-->
<!--                               :taggable="true"-->
<!--                  ></multiselect>-->
<!--                </div>-->
                <p class="mb-4">{{ $t('Users') }}</p>
                <div class="row" style="padding: 10px; border-bottom: 3px #BFBFBF; border-bottom:ridge;" v-for="(selectedAdmin, counter) in template.selectedAdmins">
                  <div class="col-10">
                    <div class="mb-3 top-label">
                      <label class="form-label">{{ $t("organization") }}</label>
                      <select class="form-control" v-model="selectedAdmin.organization_id" @change="getUsers($event)" required>
                        <option
                          v-for="(organization, organizationIndex) in organizations"
                          :key="organizationIndex"
                          :value="organizations[organizationIndex].id"
                        >{{ organization.name }}
                        </option>
                      </select>
                    </div>
                    <div class="mb-3 filled">
                      <multiselect v-model="selectedAdmin.admins" tag-placeholder="Add this as new tag"
                                   :placeholder="$t('Assign_admin')"
                                   label="name"
                                   track-by="id"
                                   :options="employees"
                                   :multiple="true"
                                   :taggable="true"
                                   @open="getUsers(selectedAdmin.organization_id)"
                      ></multiselect>
                    </div>
                    <div class="mb-3 filled">
                      <multiselect v-model="selectedAdmin.organizations" tag-placeholder="Add this as new tag"
                                   :placeholder="$t('organization')"
                                   label="name"
                                   track-by="id"
                                   :options="organizations"
                                   :multiple="true"
                                   :taggable="true"
                      ></multiselect>
                    </div>
                  </div>
                  <div class="col-2">
                    <span class="material-icons text-primary col-1 cursor-pointer" v-if="counter" @click="deleteOrg(counter)">
                      remove circle
                    </span>
                    <span class="material-icons text-success col-1 cursor-pointer" v-else @click="addOrg">
                      add circle
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </section>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-primary"
          @click="assignAdmin"

        >
          {{ $t("Save") }}
          <!--            :disabled="template.admins < 1"-->
        </button>
      </div>
    </div>
  </div>
  <!-- Login End -->

</template>

<script>
import Multiselect from 'vue-multiselect'

export default {
  components: {
    Multiselect
  },
  props: {
    template: {
      type: Object,
      required: true,
    },
    // employees: {
    //   type: Array,
    //   required: true,
    // },
    organizations: {
      type: Array,
      required: true,
    }
  },
  data() {
    return {
      employees: [],
      organizations_id:null,
      admins: false,
      // selectedAdmins: this.template.selectedAdmins
      selectedAdmins:[{
        organization_id: '',
        admins: [],
        organizations: []
      }],
    }
  },
  watch:{
    'template'(){
      // if(this.template && this.template.organizations.length > 0)
      // {
      //   this.organizations = this.template.organizations
      // }
      if(this.template.selectedAdmins.length > 0)
        this.admins = true
      else
        this.admins = false
    },
    // "selectedAdmins"(value){
    //   console.log(value)
    //   this.getUsers(value)
    // }
  },
  methods:{
    assignAdmin() {
      window.jQueryStartLoading();
      let selectedOrgAdmins = []
      // selectedOrgAdmins = this.selectedAdmins ? this.selectedAdmins : this.template.selectedAdmins
      if(this.selectedAdmins[0].admins.length > 0)
        selectedOrgAdmins = this.selectedAdmins
      else
        selectedOrgAdmins = this.template.selectedAdmins

      console.log(selectedOrgAdmins)
      // return false
      this.$axios({
        url: 'admin/templates/assign',
        method: 'POST',
        data: { selectedAdmins:selectedOrgAdmins, template_id: this.template.id }
      }).then(response => {
        if (response.data.code == 200) {
          // this.$modal('adminsModal').hide()
          window.jQueryEndLoading();
          window.jQueryToast(this.$t("Assigned_Successfully"), "success");
          this.selectedAdmins = [{
            organization_id: '',
            admins: [],
            organizations: []
          }]
          window.jQueryDatatableReload();
        } else {
          window.jQueryToast(this.$t("Assigned_unSuccessfully"), "danger");
          this.selectedAdmins = [{
            organization_id: '',
            admins: [],
            organizations: []
          }]
          window.jQueryEndLoading();
        }
      }).catch(error => {
        this.selectedAdmins = [{
          organization_id: '',
          admins: [],
          organizations: []
        }]
        window.jQueryEndLoading()
      })
    },
    getUsers(event)
    {
      const el = event?.target?.value ?? event
      this.employees= []

      // let selectedOrganizationIds = []
      // for (const key in selectedOrganizations) {
      //   let value = selectedOrganizations[key]
      //   selectedOrganizationIds.push(value.id)
      // }
      // console.log(selectedOrganizationIds)

      this.$axios({
        method: "GET",
        url: "admin/templates/getUserOrganization",
        params: {'organization_id':el},
      }).then(response => {
        this.employees = response.data.data
      }).catch(error => {
        console.log(error)
      })
    },
    addOrg(){
      this.template.selectedAdmins.push({
        organization_id:'',
        admins:[],
        organizations:[]
      })
      this.selectedAdmins.push({
        organization_id:'',
        admins:[],
        organizations:[]
      })
    },
    deleteOrg(counter) {
      this.template.selectedAdmins.splice(counter,1);
      this.selectedAdmins.splice(counter,1);
    },
  }
}
</script>
