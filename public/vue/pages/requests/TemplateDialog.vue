<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">
          {{ $t("Request")}}
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div v-if="!temPageShow" class="row">
            <div
              class="col-md-4 col-sm-12 col-lg-4 col-xl-4 col-xxl-4 col-4"
              v-for="(temp, tempIndex) in FormElments"
              :key="tempIndex"
            >
              <div class="card mb-5 text-center" style="cursor:pointer;box-shadow:0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%) !important;height: 85%" v-on:click="getTemplateById(FormElments[tempIndex].id)" >
                <div class="row">
                  <div
                    class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xxl-12 col-12"
                  >
                    <div class="card-body" style="height:100%">
                      <i
                        :class="`text-center mb-3 d-inline-block text-primary icon-20 ` + FormElments[tempIndex].icon ? FormElments[tempIndex].icon : 'cs-more-horizontal'"></i>
                      <h5 class="card-title">
                        {{ ($i18n.locale == 'en') ? FormElments[tempIndex].name :
                        FormElments[tempIndex].ar_name ? FormElments[tempIndex].ar_name :  FormElments[tempIndex].name}}
                      </h5>
                      <p class="card-text">
                        {{ FormElments[tempIndex].type }}
                      </p>
                      <!--                        <div class="text-center">-->
                      <!--                          <button type="button" v-on:click="getTemplateById(FormElments[tempIndex].id)" class="btn btn-primary mb-1">{{ $t('use') }}</button>-->
                      <!--                        </div>-->
                    </div>
                  </div>
                  <div
                    class="col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xxl-4 col-4"
                  >
                    <div class="mt-5">
                      <!--                      <i v-on:click="getTemplateById(FormElments[tempIndex].id)" class="mb-3 d-inline-block text-primary icon-20 cs-arrow-double-right"></i>-->
                    </div>
                  </div>
                </div>
                <!--                  <img src="img/product/small/panettone.jpg" class="card-img-top" alt="card image" />-->
              </div>
            </div>
          </div>
          <div class="row" v-else>
            <div
              class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xxl-12 col-12"
            >
              <i
                v-on:click="back()"
                class="mb-3 d-inline-block text-primary icon-20 cs-arrow-double-left"
              ></i>
            </div>
            <div
              class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xxl-12 col-12"
            >
              <div class="container">
                <div class="card-header border-0 pb-0">
                  <ul class="nav nav-pills responsive-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button
                        style="display:inline-block"
                        v-for="(page, pageIndex) in templateSelect.pages"
                        :key="pageIndex"
                        class="nav-link"
                        :class="{ 'show active': activeTab == pageIndex , 'errorTap': validationError.includes(templateSelect.pages[pageIndex]) }"
                        data-bs-toggle="tab"
                        :data-bs-target="'#menu-' + pageIndex"
                        role="tab"
                        aria-selected="true"
                        type="button"
                      >
                        {{ (templateSelect.pages[pageIndex].title.split(",").length > 1)
                        ? ($i18n.locale == 'en') ? templateSelect.pages[pageIndex].title.split(",")[0]
                          : templateSelect.pages[pageIndex].title.split(",")[1]
                        : templateSelect.pages[pageIndex].title }}
                      </button>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div
                      v-for="(page, pageIndex) in templateSelect.pages"
                      :key="pageIndex"
                      :class="{ 'show active': activeTab == pageIndex }"
                      class="tab-pane fade"
                      :id="'menu-' + pageIndex"
                      role="tabpanel"
                    >
                      <!--                      {{validationError}}-->
                      <div class="row">
                        <div v-for="(item,itemIndex) in templateSelect.pages[pageIndex].items" :key="itemIndex" class="mb-3" :class="item.width">
                          <div v-if="item.type == 'line'"></div>
                          <div v-else-if="item.type == 'radio'">
                            <label>{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                            <div class="form-check" v-for="(radio,radioIndex) in item.childList" :key="radioIndex">
                              <input v-model="templateSelect.pages[pageIndex].items[itemIndex].value" :value="radio.text"
                                     :name="`form_radio`+itemIndex" type="radio" :required="item.required == 1"
                                     class="form-check-input"/>
                              <label>{{
                                  (radio.text.split(",").length > 1) ?
                                    (radio.text.split(",")[0]) ? ($i18n.locale == 'en') ? radio.text.split(",")[0] : radio.text.split(",")[1]
                                      : radio.text
                                    : radio.text
                                }} : <i v-if="item.required == 1" style="color: darkred">*</i>
                              </label>
                            </div>
                            <small v-for="(v,i) in validationError" :key="i" v-if="v.label  == templateSelect.pages[pageIndex].items[itemIndex].label" style="color: darkred">{{ templateSelect.pages[pageIndex].items[itemIndex].label+'is required' }}</small>
                          </div>
                          <div v-else-if="item.type == 'label'">
                            <label class="labelNewLine">{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                            <small v-for="(v,i) in errors" :key="i" v-if="v.label  == templateSelect.pages[pageIndex].items[itemIndex].label" style="color: darkred">{{ templateSelect.pages[pageIndex].items[itemIndex].label+'is required' }}</small>
                          </div>
                          <div v-else-if="item.type == 'text'">
                            <label>{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                            <input :maxlength="item.length" :type="(item.input_type) ? item.input_type : 'text'" v-model="templateSelect.pages[pageIndex].items[itemIndex].value" :height="item.height" :required="item.required == 1" class="form-control" :class="{'error': validationError.includes(templateSelect.pages[pageIndex].items[itemIndex])}" />
                          </div>
                          <div v-else-if="item.type == 'textarea'">
                            <label>{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                            <textarea :maxlength="item.length" v-model="templateSelect.pages[pageIndex].items[itemIndex].value" class="form-control" :height="item.height" :class="{'error': validationError.includes(templateSelect.pages[pageIndex].items[itemIndex])}" :required="item.required == 1"></textarea>
                          </div>
                          <div v-else-if="item.type == 'checkbox'">
                            <input v-model="templateSelect.pages[pageIndex].items[itemIndex].value" class="form-check-input" :height="item.height" :required="item.required == 1" :class="{'error': validationError.includes(templateSelect.pages[pageIndex].items[itemIndex])}" type="checkbox" />
                            <label>{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                          </div>
                          <div v-else-if="item.type == 'select'">
                            <label>{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                            <select
                              v-model="
                              templateSelect.pages[pageIndex].items[itemIndex]
                                .value
                            "
                              class="form-select"
                              :height="item.height"
                              :required="item.required == 1"
                              :class="{'error': validationError.includes(templateSelect.pages[pageIndex].items[itemIndex])}"
                            >
                              <option
                                v-for="(selector, indexSelect) in item.childList"
                                :value="selector.text"
                                :key="indexSelect"
                              >{{ (selector.text.split(",").length > 1) ?
                                (selector.text.split(",")[0]) ? ($i18n.locale == 'en') ? selector.text.split(",")[0] : selector.text.split(",")[1]
                                  : selector.text
                                : selector.text}}</option
                              >
                            </select>
                          </div>
                          <div v-else-if="item.type == 'table'">
                            <label>{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                            <!--                          {{item.childList}}-->
                          </div>
                          <div v-else-if="item.type == 'tree'"></div>
                          <div v-else-if="item.type == 'file'">
                            <label>{{ (item.label.split(",").length > 1) ?
                              (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                : item.label
                              : item.label}} : <i v-if="item.required == 1" style="color: darkred">*</i>
                            </label>
                            <input
                              type="file"
                              :height="item.height"
                              :required="item.required == 1"
                              id="file"
                              ref="file"
                              v-on:change="handleFileUpload($event,pageIndex,itemIndex)"
                            />
                          </div>
                          <small v-for="(v,i) in validationError" :key="i"
                                 v-if="v.label  == templateSelect.pages[pageIndex].items[itemIndex].label"
                                 style="color: darkred">{{
                              templateSelect.pages[pageIndex].items[itemIndex].label + 'is required'
                            }}</small>
                        </div>
                      </div>
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
              {{ $t("Cancel") }}
            </button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="temPageShow"
              v-on:click="submitForm()"
            >
              {{ $t("Save_Changes") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TemplateDialog",
  props: {
    id: {
      type: Number,
      required: true
    },
    home: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      FormElments: {},
      temPageShow: false,
      templateSelect: {},
      activeTab: "",
      requestData: [],
      errors: [],
      submitClicked: false,
      file: '',
      fileName: '',
    };
  },
  watch: {
    Number: function() {
      this.getTempPage();
    }
  },
  created() {
    this.getTempPage();
  },
  computed:{
    validationError(){
      let errors = []
      if(this.submitClicked)
        for (let i = 0; i < this.templateSelect.pages.length; i++) {
          if(typeof this.templateSelect.pages != "undefined")
            for (let j = 0; j < this.templateSelect.pages[i].items.length; j++) {
              // console.log('item is :',this.templateSelect.pages[i].items[j])
              if (typeof this.templateSelect.pages[i].items[j].id != "undefined")
                if (this.templateSelect.pages[i].items[j].required == 1 &&  !this.templateSelect.pages[i].items[j].value) {
                  errors.push(this.templateSelect.pages[i].items[j])
                  if(!errors.includes(this.templateSelect.pages[i]))
                    errors.push(this.templateSelect.pages[i])
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
  methods:{
    async getTempPage(){
      window.jQueryStartLoading()
      await this.$axios
        .get("admin/templates/getUserTemplates")
        .then(response => {
          // console.log("check begin data", response.data);
          this.FormElments = Object.assign(response.data);
          window.jQueryEndLoading();
        })
        .catch(function(error) {
          window.jQueryToast("Failed to load templates", "danger");
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
        });
    },
    back() {
      this.temPageShow = false;
      this.getTempPage();
    },
    getTemplateById(tempId) {
      this.temPageShow = true;
      window.jQueryStartLoading();
      return this.$axios
        .get("admin/templates/" + tempId + "/get")
        .then(response => {
          this.templateSelect = response.data;
          this.activeTab = Object.keys(this.templateSelect.pages)[0];
          window.jQueryEndLoading();
        })
        .catch(error => {
          window.jQueryToast("Failed to load", "danger");
          window.jQueryEndLoading();
        });
    },
    submitForm()
    {
      this.submitClicked = true
      if (this.validationError.length > 0)
      {
        window.jQueryToast(this.$t("Validation Error"), "danger");
      }else{
        // console.log('request data',this.templateSelect)
        window.jQueryStartLoading()
        const bodyFormData = new FormData();
        // console.log('form data : ',this.templateSelect)
        for (const key in this.templateSelect) {
          let value = this.templateSelect[key]
          // console.log('value is : ',value.pages)
          bodyFormData.set(key, JSON.stringify(value))
        }
        // console.log(this.templateSelect)
        bodyFormData.append('file', this.file)
        bodyFormData.append('fileName', this.fileName)
        return this.$axios({
          url: 'admin/forms/store_form',
          method: "POST",
          data: bodyFormData,
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          // window.jQueryToast(this.$t("Your request No. "+response.data.data.id+" has been submitted successfully"), "success");
          window.jQueryToast(this.$t("Your_request_No")+response.data.data.id+` `+this.$t("hasSubmitSuccess"),"success");
          if(this.home)
            window.location.reload()
          window.jQueryDatatableReload();
          window.jQueryEndLoading();
          this.submitClicked = false
        }).catch(error => {
          console.log(error)
          window.jQueryDatatableReload()
          window.jQueryEndLoading()
        })
      }
    },
    handleFileUpload(event,page,item) {
      let self = this
      let file = event.target.files[0]
      if (file) {
        this.fileName = event.target.files[0].name.split('.')[0]
        this.fileExtension = event.target.files[0].name.split('.')[1]
        self.templateSelect.pages[page].items[item].file_name = this.fileName+'.'+this.fileExtension
        console.log('file name is: ',self.templateSelect.pages[page].items[item].file_name)
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          self.templateSelect.pages[page].items[item].value = reader.result
          self.templateSelect.pages[page].items[item].required = 0
        };
      }
    },
  }
};
</script>

<style>
.labelNewLine {
  white-space: pre;
}
</style>
