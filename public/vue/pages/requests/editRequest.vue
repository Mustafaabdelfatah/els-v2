<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">
          {{ $t('edit_Request') }}
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body" v-if="data">
        <div class="container">
          <div class="card-header border-0 pb-0">
            <ul class="nav nav-pills responsive-tabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button
                  style="display:inline-block"
                  v-for="(page, pageIndex) in data.pages"
                  :key="pageIndex"
                  class="nav-link"
                  :class="{ 'show active': activeTab == pageIndex , 'errorTap': validationError.includes(data.pages[pageIndex]) }"
                  data-bs-toggle="tab"
                  :data-bs-target="'#menu-' + pageIndex"
                  role="tab"
                  aria-selected="true"
                  type="button"
                >
                  {{
                    (data.pages[pageIndex].title.split(",").length > 1) ?
                      (data.pages[pageIndex].title.split(",")[0]) ? ($i18n.locale == 'en') ? data.pages[pageIndex].title.split(",")[0] : data.pages[pageIndex].title.split(",")[1]
                        : data.pages[pageIndex].title
                      : data.pages[pageIndex].title + ` :  `
                  }}
                </button>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div
                v-for="(page, pageIndex) in data.pages"
                :key="pageIndex"
                :class="{ 'show active': activeTab == pageIndex }"
                class="tab-pane fade"
                :id="'menu-' + pageIndex"
                role="tabpanel"
              >
                <!--{{validationError}}-->
                <div v-for="(item,itemIndex) in data.pages[pageIndex].items" :key="itemIndex" class="mb-3">
                  <div v-if="item.type == 'line'"></div>
                  <div v-else-if="item.type == 'radio'">
                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }} <i v-if="item.required == 1" style="color: darkred">*</i></label>
                    <div class="form-check" v-for="(radio,radioIndex) in JSON.parse(item.childList)"
                         :key="radioIndex">
                      <input v-model="data.pages[pageIndex].items[itemIndex].filling.value" :value="radio.text"
                             :name="`form_radio`+itemIndex" type="radio" :required="item.required == 1"
                             class="form-check-input"/>
                      <label class="form-check-label">{{ radio.text }}</label>
                    </div>
                    <small v-for="(v,i) in validationError" :key="i"
                           v-if="v.label  == data.pages[pageIndex].items[itemIndex].label" style="color: darkred">{{
                        data.pages[pageIndex].items[itemIndex].label + 'is required'
                      }}</small>
                  </div>
                  <div v-else-if="item.type == 'label'">

                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }}<i v-if="item.required == 1" style="color: darkred">*</i></label>
                    <small v-for="(v,i) in errors" :key="i"
                           v-if="v.label  == data.pages[pageIndex].items[itemIndex].label" style="color: darkred">{{
                        data.pages[pageIndex].items[itemIndex].label + 'is required'
                      }}</small>
                  </div>
                  <div v-else-if="item.type == 'text'">
                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }} <i v-if="item.required == 1" style="color: darkred">*</i></label>
                    <input type="text" v-model="data.pages[pageIndex].items[itemIndex].filling.value"
                           :width="item.width" :height="item.height" :required="item.required == 1"
                           class="form-control"
                           :class="{'error': validationError.includes(data.pages[pageIndex].items[itemIndex])}"/>
                  </div>
                  <div v-else-if="item.type == 'textarea'">
                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }} <i v-if="item.required == 1" style="color: darkred">*</i></label>
                    <textarea v-model="data.pages[pageIndex].items[itemIndex].filling.value" class="form-control"
                              :width="item.width" :height="item.height"
                              :class="{'error': validationError.includes(data.pages[pageIndex].items[itemIndex])}"
                              :required="item.required == 1"></textarea>
                  </div>
                  <div v-else-if="item.type == 'checkbox'">
                    <input v-model="data.pages[pageIndex].items[itemIndex].value" class="form-check-input"
                           :width="item.width" :height="item.height" :required="item.required == 1"
                           :class="{'error': validationError.includes(data.pages[pageIndex].items[itemIndex])}"
                           type="checkbox"/>
                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }} <i v-if="item.required == 1" style="color: darkred">*</i></label>
                  </div>
                  <div v-else-if="item.type == 'select'">
                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }} <i v-if="item.required == 1" style="color: darkred"></i></label>
                    <select
                      v-model="data.pages[pageIndex].items[itemIndex].filling.value"
                      class="form-select"
                      :width="item.width"
                      :height="item.height"
                      :required="item.required == 1"
                      :class="{'error': validationError.includes(data.pages[pageIndex].items[itemIndex])}"
                    >
                      <option
                        v-for="(selector, indexSelect) in JSON.parse(item.childList)"
                        :value="selector.text"
                        :key="indexSelect"
                      >{{ selector.text }}</option
                      >
                    </select>
                  </div>
                  <div v-else-if="item.type == 'table'">
                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }}</label>
                    <!-- {{item.childList}}-->
                  </div>
                  <div v-else-if="item.type == 'tree'"></div>
                  <div v-else-if="item.type == 'file'">
                    <label>{{
                        (item.label.split(",").length > 1) ?
                          (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                            : item.label
                          : item.label + ` :  `
                      }}</label>
                    <input
                      type="file"
                      :width="item.width"
                      :height="item.height"
                      :required="item.required == 1"
                      id="file"
                      ref="file"
                      v-on:change="handleFileUpload($event,pageIndex,itemIndex)"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-primary"
            data-bs-dismiss="modal"
          >
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-primary"
            v-on:click="updateForm()">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props:{
    id:{
      type: Number,
      required: true,
    }
  },
  data (){
    return {
      data: {},
      activeTab:0,
      submitClicked: false,
      test:[],
      file: '',
      fileName: '',
    }
  },
  computed:{
    validationError(){
      let errors = []
      if(this.submitClicked)
        for (let i = 0; i < this.data.pages.length; i++) {
          if(typeof this.data.pages != "undefined")
            for (let j = 0; j < this.data.pages[i].items.length; j++) {
              // console.log('item is :',this.templateSelect.pages[i].items[j])
              if (typeof this.data.pages[i].items[j].id != "undefined")
                if (this.data.pages[i].items[j].required == 1 &&  !this.data.pages[i].items[j].filling.value) {
                  errors.push(this.data.pages[i].items[j])
                  if(!errors.includes(this.data.pages[i]))
                    errors.push(this.data.pages[i])
                }
            }
        }
      return errors
    },
    userpermission(){
      let userPermissions = []
      this.$auth.user.permissions.filter((permission) => {
        userPermissions.push(permission.name)
      })
      return userPermissions
    }
  },
  watch:{
    "id": function(){
      this.getData()
    }
  },
  created() {
    // this.getData()
  },
  methods:{
    getData()
    {
      return this.$axios.get('admin/forms/get/'+this.id)
        .then(response => {

          this.data = Object.assign(response.data[0])
        }).catch(error => {
          console.log(error)
        })
    },
    handleFileUpload(event,page,item) {
      let self = this
      let file = event.target.files[0]
      if (file) {
        this.fileName = event.target.files[0].name.split('.')[0]
        this.fileExtension = event.target.files[0].name.split('.')[1]
        // console.log(event,page,item,this.fileName,this.fileExtension)
        // return false;
        self.data.pages[page].items[item].file_name = this.fileName+'.'+this.fileExtension
        console.log('file name is: ',self.data.pages[page].items[item].file_name)
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          self.data.pages[page].items[item].value = reader.result
          self.data.pages[page].items[item].required = 0
        };
      }
    },
    updateForm()
    {
      this.submitClicked = true
      if (this.validationError.length > 0)
      {
        window.jQueryToast(this.$t("Error_has_been_occurred"), "danger");
      }else {
        window.jQueryStartLoading()
        const bodyFormData = new FormData
        console.log('data is',this.data)
        for (const key in this.data) {
          let value = this.data[key]
          bodyFormData.set(key, JSON.stringify(value))
        }
        bodyFormData.append('form_id',this.id);
        bodyFormData.append('file', this.file)
        bodyFormData.append('fileName', this.fileName)
        return this.$axios({
          url: 'admin/forms/update_form',
          method: "POST",
          data: bodyFormData,
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          window.jQueryToast(this.$t("Waiting_to_conform_your_update"), "success");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
          // this.$modal.hide("#editModal");
          this.submitClicked = false
        }).catch(error => {
          console.log(error)
          window.jQueryDatatableReload()
          window.jQueryEndLoading()
        })
      }
    }
  }
}
</script>
