<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ $t(info_title) }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cancel"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="col-12 mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" @change="moreInfoModal()" v-model="form.moreInfoStatus" />
              <label class="form-check-label">{{ $t("Need_more_information") }} <i class="text-center text-danger mb-2"
                  v-show="errors.includes('informationText')">*</i></label>
            </div>
          </div>
          <!--              <div class="mb-3">-->
          <!--                <label class="form-label">Required information</label>-->
          <!--                <textarea required class="form-control" rows="3" v-model="form.informationText"></textarea>-->
          <!--              </div>-->
          <div class="mb-3 w-100">
            <label class="form-label">{{ $t('Priority') }} <i class="text-center text-danger">*</i></label>
            <select required id="selectBasic" class="form-control" :class="{ 'error': errors.includes('Priority') }"
              v-model="form.Priority">
              <option value="very_urgent">{{ $t("Very_Urgent") }}</option>
              <option value="urgent">{{ $t("Urgent") }}</option>
              <option value="medium">{{ $t("Medium") }}</option>
              <option value="scheduled ">{{ $t("Scheduled") }}</option>
            </select>
          </div>
          <div v-if="templateId == 2">
            <div class="mb-3 w-100">
              <label>{{ $t('case_type') }}</label>
              <div class="row">
                <div class="col-4 mb-2">
                  <input v-model="approveForm.workers" class="form-check-input" type="checkbox" />
                  <label>{{ $t('workers') }} </label>
                </div>
                <div class="col-4 mb-2">
                  <input v-model="approveForm.commercial" class="form-check-input" type="checkbox" />
                  <label>{{ $t('commercial') }} </label>
                </div>
                <div class="col-4 mb-2">
                  <input v-model="approveForm.general" class="form-check-input" type="checkbox" />
                  <label>{{ $t('general') }} </label>
                </div>
                <div class="col-4 mb-2">
                  <input v-model="approveForm.administrative" class="form-check-input" type="checkbox" />
                  <label>{{ $t('administrative') }} </label>
                </div>
                <div class="col-4 mb-2">
                  <input v-model="approveForm.executive" class="form-check-input" type="checkbox" />
                  <label>{{ $t('executive') }} </label>
                </div>
              </div>
            </div>
            <div class="mb-3 w-100">
              <label>{{ $t('expected_amount') }}</label>
              <input type="number" v-model="approveForm.expected_amount" class="form-control">
            </div>
            <div class="mb-3 w-100">
              <label>{{ $t('procedure_type') }}</label>
              <div class="form-check" v-for="(procedure, procedureIndex) in procedureType" :key="procedureIndex">
                <input v-model="approveForm.procedureType" :value="procedure.text" :name="`form_radio` + procedureIndex"
                  type="radio" class="form-check-input" />
                <label class="form-check-label">{{ $t(procedure.text) }}</label>
              </div>
            </div>
          </div>
          <div class="col-12 mb-3">
            <label>{{ $t("Service_processing_end_date") }} <i class="text-center text-danger">*</i></label>
            <input type="date" class="form-control" :class="{ 'error': errors.includes('end_date') }"
              v-model="form.end_date" :min="form.end_date" />
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="cancel">{{
          $t('Cancel')
        }}</button>
        <button type="button" @click="rejectModal" class="btn btn-danger">{{ $t('Reject') }}</button>
        <button type="button" @click="submitInformationForm" :disabled="returnDisabled" class="btn btn-primary">
          {{ $t('Return_for_modification') }}
        </button>
        <button type="button" @click="approvedForm" class="btn btn-success">{{ $t('Approve') }}</button>
      </div>
    </div>

    <modal name="informationModal" :clickToClose="false" :focusTrap="true" :draggable="true" :adaptive="true">
      <div id="moreInfoDialog" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-center">
          <form>
            <div class="mb-2">
              <label class="form-label">{{ $t("Description") }}</label>
              <textarea required class="form-control" rows="3" v-model="form.informationText"
                :class="{ 'error': errors.includes('informationText') }"></textarea>
            </div>
            <div class="mb-2">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="form.secret" />
                <label required class="form-check-label">{{ $t("Need_secret_data") }}</label>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label">{{ $t("attach_file") }}</label>
              <input class="form-control" type="file" id="files" multiple ref="files" v-on:change="handleFileUpload">
            </div>
          </form>
          <div class="mt-4 pt-2">
            <button type="button" class="btn btn-success btn-sm" @click="$modal.hide('informationModal')">
              {{ $t("Submit") }}
            </button>
            <button type="button" class="btn btn-secondary btn-sm" @click="deleteInforeason()">
              {{ $t('Cancel') }}
            </button>
          </div>
        </div>
      </div>
    </modal>

    <modal name="DeleteConfirm" :clickToClose="false" :focusTrap="true" :draggable="true" :adaptive="true">
      <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-center">
          {{ $t("Are_you_sure_that_you_want_to_reject") }}
          <div class="mt-4 pt-2">
            <label class="form-label">{{ $t('Select Reason') }} <i class="text-center text-danger">*</i></label>
            <div class="mb-2">
              <select class="form-select" style="margin-bottom: 20px" v-model="reject_comment">
                <option value="Incomplete documentation">{{ $t("Incomplete_documentation") }}</option>
                <option value="Not following policies">{{ $t("Not_following_policies") }}</option>
                <option value="lack of clarity of purpose">{{ $t("lack_of_clarity_of_purpose") }}</option>
                <option value="Incomplete description">{{ $t("incomplete_description") }}</option>
                <option value="other">{{ $t("other") }}</option>
              </select>

              <label class="form-label" v-if="reject_comment === 'other'">{{ $t('Reason') }} <i
                  class="text-center text-danger">*</i></label>
              <textarea name="" id="" v-if="reject_comment === 'other'" cols="30" rows="4" class="form-control"
                :class="{ 'error': !reject_comment }" v-model="reject_comment_text"></textarea>
            </div>
            <button type="button" class="btn btn-danger btn-sm" :disabled="rejectDisabled" @click="rejected()">
              {{ $t("Reject") }}
            </button>
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast"
              @click="$modal.hide('DeleteConfirm')">
              {{ $t("Close") }}
            </button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>

<style>
#moreInfoDialog {
  /*position: fixed !important;*/
  /*transform:translate(-50%, 0%)*/
}
</style>


<script>
export default {
  props: {
    id: {
      type: Number,
      required: true
    },
    templateId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      info_title: 'Need_Info',
      form: {
        moreInfoStatus: false,
        informationText: '',
        Priority: null,
        secret: false,
        end_date: new Date().toISOString().slice(0, 10),
        file: '',
        fileName: '',
      },
      files: [],
      returnFiles: [],
      approveForm: {
        expected_amount: null,
        workers: false,
        commercial: false,
        general: false,
        administrative: false,
        executive: false,
        procedureType: '',
      },
      reject_comment: '',
      reject_comment_text: '',
      errors: [],
      procedureType: [
        {
          text: 'litigate',
          value: 'litigate'
        },
        {
          text: 'civil_defense',
          value: 'Civil_defense'
        },
        {
          text: 'reconciliation',
          value: 'reconciliation'
        },
      ],
    }
  },
  computed: {
    rejectDisabled() {
      if (this.reject_comment === 'other')
        return this.reject_comment === "" || this.reject_comment_text === ""
      else
        return this.reject_comment === ""
    },
    returnDisabled() {
      return this.form.informationText === "" || this.form.Priority === null
    }
    // validationError() {
    //   let errors = []
    //   if(this.form.Priority == null || this.form.Priority == '' || typeof this.form.Priority == undefined){
    //     errors.push('Priority')
    //   }
    //   if(this.form.end_date == null || this.form.end_date == '' || typeof this.form.end_date == undefined){
    //     errors.push('end_date')
    //   }
    //   return errors
    // }
  },
  methods: {
    submitInformationForm() {
      this.errors = []
      if (this.form.Priority == null || this.form.Priority == '' || typeof this.form.Priority == undefined) {
        this.errors.push('Priority')
      }
      if (this.form.end_date == null || this.form.end_date == '' || typeof this.form.end_date == undefined) {
        this.errors.push('end_date')
      }
      if (this.form.moreInfoStatus && this.form.informationText == null || this.form.informationText == '') {
        this.errors.push('informationText')
      }
      if (this.errors.length <= 0) {
        window.jQueryStartLoading();
        let formData = new FormData
        for (let key in this.returnFiles) {
          const value = this.returnFiles[key];
          if (value != 'undefined' || value != null)
            formData.append('files[' + key + ']', value);
        }

        for (const key in this.form) {
          let value = this.form[key]
          // formData.set(key, value)
          if (Array.isArray(value)) {
            for (let i = 0; i < value.length; i++) {
              formData.append('files[]', value[i]);
            }
          } else {
            formData.set(key, value);
          }
        }
        formData.append('form_id', this.id);
        formData.append('fileName', this.fileName);
        this.$axios({
          url: 'admin/forms/return_request',
          data: formData,
          method: "POST",
          config: { headers: { 'Content-Type': 'multipart/form-data' } }
        }).then(response => {
          console.log('response is :', response.data)
          this.form = {
            moreInfoStatus: false,
            informationText: '',
            Priority: null,
            secret: false,
            end_date: new Date().toISOString().slice(0, 10),
            file: '',
            fileName: '',
          };
          window.jQueryToast(this.$t("returned_Successfully"), "success");
          window.jQueryDatatableReload();
          window.jQueryEndLoading()
        }).catch(error => {
          window.jQueryDatatableReload();
          window.jQueryEndLoading()

        })
      } else {
        window.jQueryToast(this.$t("Validation_Error"), "danger");
      }
    },
    moreInfoModal() {
      if (this.form.moreInfoStatus)
        this.$modal.show('informationModal')
      else
        this.errors.splice(this.errors.indexOf('informationText'), 1)
    },
    deleteInforeason() {
      this.form.informationText = ''
      this.$modal.hide('informationModal')
    },
    rejectModal() {
      this.$modal.show('DeleteConfirm')
    },
    approvedForm() {
      window.jQueryStartLoading();
      let formData = new FormData
      // Append the additional fields
      formData.set('secret', this.form.secret);
      formData.set('moreInfo', this.form.moreInfo)

      for (const key in this.approveForm) {
        let value = this.approveForm[key]
        formData.set(key, value)
      }
      formData.append('form_id', this.id);
      formData.append('status', 'approved');


      // return this.$axios.get( 'admin/forms/approve',{params:{ form_id:this.id , status: 'approved',data:this.approveForm}})
      this.$axios({
        url: 'admin/forms/approve',
        data: formData,
        method: "POST",
      }).then(response => {
        window.jQueryToast(this.$t("approved_by_legal_department_and_it_under_processing"), "success");
        window.jQueryDatatableReload();
        window.jQueryEndLoading()
      }).catch(error => {
        window.jQueryDatatableReload();
        window.jQueryEndLoading()
      })
    },
    rejected() {
      window.jQueryStartLoading();
      if (this.reject_comment && this.reject_comment === 'other') {
        this.reject_comment = this.reject_comment_text
      }
      return this.$axios.get('admin/forms/action', {
        params: {
          form_id: this.id,
          status: 'rejected',
          comment: this.reject_comment
        }
      })
        .then(response => {
          window.jQueryToast(this.$t("Rejected_Successfully"), "success");
          window.jQueryDatatableReload();
          window.jQueryEndLoading()
        }).catch(error => {
          window.jQueryDatatableReload();
          window.jQueryEndLoading()
        })
    },
    cancel() {
      this.$emit('resetID');
    },
    handleFileUpload() {
      let self = this
      let filesArr = this.$refs.files.files
      if (filesArr.length > 0) {
        for (let i = 0; i < filesArr.length; i++) {
          console.log(this.files);
          self.files.push(filesArr[i].name)
          // self.fileNames.push(filesArr[i].name)
          let reader = new FileReader();
          reader.readAsDataURL(filesArr[i]);
          reader.onload = function () {
            self.returnFiles.push(reader.result)
            console.log(self.returnFiles, 'test')
          }
        }
      }
    }
  }
}
</script>
