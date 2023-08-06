<template>
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignModalTitle">
          <strong class="">{{ $t("close_Request") }}</strong>
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          @click="cancel"
        ></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
<!--            <div class="col-12 col-sm-12 mb-2">-->
<!--              <p><strong class="">{{ $t("Close_Request")}}</strong></p>-->
<!--              <small>send file to requester (optional)</small>-->
<!--            </div>-->
            <div class="col-12 col-sm-12 mb-4">
              <label>{{$t('File_Attachment')}} : </label>
              <input style="width: 24%" type="file" id="files" ref="files" v-on:change="handleFileUpload()" multiple class="form-control">
              <div class="row mt-3 mb-3">
                <div class="col-12" v-for="(file,fileIndex) in files" :key="fileIndex">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-10">
                          <h4>{{ file }}</h4>
                        </div>
                        <div class="col-2">
                          <button type="button" class="btn-close me-2 m-auto" @click="removeFile(fileIndex)"></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-12 mb-4">
              <label>{{$t('comment')}} : </label>
              <textarea name="" id="" cols="30" rows="10" v-model="comment" class="form-control"></textarea>
            </div>
          </div>
            <div v-if="templateId == 2" class="container borderd">
              <div class="row">
                <div class="col-12 col-sm-12 mb-2">
                  <h3 class="text-center">{{ $t('Close_litigation') }}</h3>
                </div>
                <div class="col-12 col-sm-12 mb-2">
                  <label class="mb-2">{{ $t('File_Attachment') }} : </label>
                  <input style="width: 24%" type="file" id="filelitigation" ref="filelitigation" multiple v-on:change="handleFileUploadlitigation()"
                         class="form-control">
                  <div class="row mt-3 mb-3">
                    <div class="col-12" v-for="(litigationFile,litigationFileIndex) in litigationFilesArr" :key="litigationFileIndex">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-10">
                              <h4>{{ litigationFile }}</h4>
                            </div>
                            <div class="col-2">
                              <button type="button" class="btn-close me-2 m-auto" @click="removelitigationFile(litigationFileIndex)"></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-12 mb-2">
                  <label class="mb-2">{{ $t('judgment') }}</label>
                    <div class="form-check">
                      <input v-model="company_judgment" value="the_company_is_convicted" :name="`company_judgment`" type="radio" class="form-check-input" />
                      <label class="form-check-label">{{ $t('the_company_is_convicted') }}</label>
                    </div>
                    <div class="form-check">
                      <input v-model="company_judgment" value="the_company_is_condemned" :name="`company_judgment`" type="radio" class="form-check-input" />
                      <label class="form-check-label">{{ $t('the_company_is_condemned') }}</label>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 mb-2">
                    <label class="mb-2">{{ $t('type_of_judge') }}</label>
                    <div class="form-check">
                      <input v-model="type_of_judge" value="initial" :name="`type_of_judge`" type="radio" class="form-check-input" />
                      <label class="form-check-label">{{ $t('initial') }}</label>
                    </div>
                    <div class="form-check">
                      <input v-model="type_of_judge" value="final" :name="`type_of_judge`" type="radio" class="form-check-input" />
                      <label class="form-check-label">{{ $t('final') }}</label>
                    </div>
                    <div class="form-check">
                      <input v-model="type_of_judge" value="resumption" :name="`type_of_judge`" type="radio" class="form-check-input" />
                      <label class="form-check-label">{{ $t('resumption') }}</label>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 mb-2">
                    <label>{{ $t("Case_date")}}</label>
                    <input type="date" class="form-control" v-model="case_date"/>
                  </div>
                  <div class="col-12 col-sm-12 mb-2">
                    <label>{{ $t("Case_number")}}</label>
                    <input type="number" class="form-control" v-model="case_number"/>
                  </div>
                  <div class="col-12 col-sm-12 mb-2">
                    <label>{{ $t("expected_result")}}</label>
                    <input type="number" class="form-control" v-model="expected_result"/>
                  </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 mb-2 text-center">
              <button type="button" @click="endRequest()" class="btn btn-primary">{{ $t('Save') }}</button>
            </div>
        </div>
      </div>
    </div>
    </div>
</template>

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
    },
  },
  watch:{
    "id": function (){

    }
  },
  data(){
    return {
      fileNames: [],
      litigationFileNames: [],
      closeFiles:[],
      litigationFiles:[],
      comment:null,
      filelitigation:'',
      company_judgment:null,
      type_of_judge:null,
      case_date:null,
      case_number:null,
      expected_result:null,
      files:[],
      litigationFilesArr:[]
    }
  },
  methods:{
    handleFileUpload(){
      let self = this
      let filesArr = this.$refs.files.files
      if (filesArr.length > 0) {
        for (let i = 0; i < filesArr.length; i++) {
          self.files.push(filesArr[i].name)
          self.fileNames.push(filesArr[i].name)
          let reader = new FileReader();
          reader.readAsDataURL(filesArr[i]);
          reader.onload = function () {
            self.closeFiles.push(reader.result)
            console.log(self.closeFiles)
          }
        }
      }
    },
    handleFileUploadlitigation() {
      let self = this
      // let file = this.$refs.filelitigation.files[0]
      // var reader = new FileReader();
      // reader.readAsDataURL(file);
      // reader.onload = function () {
      //   self.filelitigation = reader.result
      // }
      let litigationArr = this.$refs.filelitigation.files
      if (litigationArr.length > 0) {
        for (let i = 0; i < litigationArr.length; i++) {
          this.litigationFilesArr.push(litigationArr[i].name)
          this.litigationFileNames.push(litigationArr[i].name)
          let reader = new FileReader();
          reader.readAsDataURL(litigationArr[i]);
          reader.onload = function () {
            self.litigationFiles.push(reader.result)
          }
        }
      }
    },
    endRequest(){
      window.jQueryStartLoading()
      let formData = new FormData()
      for (let key in this.closeFiles) {
        const value = this.closeFiles[key];
        if(value !='undefined' || value != null)
          formData.append('files['+key+']', value);
      }
      for (let key in this.fileNames) {
        const value = this.fileNames[key];
        if(value !='undefined' || value != null)
          formData.append('fileNames['+key+']', value);
      }
      for (let key in this.litigationFileNames) {
        const value = this.litigationFileNames[key];
        if(value !='undefined' || value != null)
          formData.append('litigationFileNames['+key+']', value);
      }
      for (let key in this.litigationFiles) {
        const value = this.litigationFiles[key];
        if(value !='undefined' || value != null)
          formData.append('litigation_files['+key+']', value);
      }
      formData.append('form_id', this.id)
      formData.append('comment',this.comment)
      formData.append('filelitigation',this.filelitigation)
      formData.append('company_judgment',this.company_judgment)
      formData.append('type_of_judge',this.type_of_judge)
      formData.append('case_date',this.case_date)
      formData.append('case_number',this.case_number)
      formData.append('expected_result',this.expected_result)
      return this.$axios({
        url: "admin/forms/close_request",
        method: "POST",
        data: formData,
        headers: {'Content-Type': 'multipart/form-data'}
      }).then(response => {
          this.fileNames= []
          this.litigationFileNames= []
          this.closeFiles= []
          this.litigationFiles= []
          this.comment= null
          this.filelitigation= ''
          this.company_judgment= null
          this.type_of_judge= null
          this.case_date= null
          this.case_number= null
          this.expected_result= null
          this.files= []
          this.litigationFilesArr= []
        window.jQueryEndLoading();
        window.jQueryToast(this.$t("Closed_Successfully"), "success");
        window.jQueryDatatableReload();
        console.log(response)
      }).catch(error => {
        console.log(error)
        window.jQueryEndLoading();
      })
    },
    cancel() {
      // this.assignRequest = {};
      this.$emit('resetID');
    },
    removeFile(index) {
      this.closeFiles.splice(index, 1);
      this.files.splice(index, 1);
    },
    removelitigationFile(index) {
      this.litigationFiles.splice(index, 1);
      this.litigationFilesArr.splice(index, 1);
    }
  }
}
</script>
