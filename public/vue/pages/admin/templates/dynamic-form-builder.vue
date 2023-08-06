<template>
  <div class="h-100" id="dynamic-form-builder">
    <div class="modal-body p-0 overflow-scroll" style="height: auto !important;">
      <section>
        <div id="dynamic-form-builder-tools" class="row text-center w-100">
          <div class="row form-tools">
            <div class="cursor-pointer col" @click="addPage">
              <img src="/img/dynamic-form-builder/icon-page.svg" class="m-auto">
              <h6>{{ $t("Page")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addLabelElement">
              <img src="/img/dynamic-form-builder/label.svg" class="m-auto">
              <h6>{{ $t("Label")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addTextElement">
              <img src="/img/dynamic-form-builder/icon-text.svg" class="m-auto">
              <h6>{{ $t("Text")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addTableElement">
              <img src="/img/dynamic-form-builder/icon-table.svg" class="m-auto">
              <h6>{{ $t("Table")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addCheckboxElement">
              <img src="/img/dynamic-form-builder/icon-checklist.svg" class="m-auto">
              <h6>{{ $t("Checklist") }}</h6>
            </div>
            <div class="cursor-pointer col" @click="addTextAreaElement">
              <img src="/img/dynamic-form-builder/icon-description.svg" class="m-auto">
              <h6>{{ $t("TextArea") }}</h6>
            </div>
            <div class="cursor-pointer col" @click="addSelectElement">
              <img src="/img/dynamic-form-builder/icon-selection.svg" class="m-auto">
              <h6>{{ $t("Selection")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addTreeElement">
              <img src="/img/dynamic-form-builder/icon-tree.svg" class="m-auto">
              <h6>{{ $t("Tree")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addLineElement">
              <img src="/img/dynamic-form-builder/icon-addline.svg" class="m-auto">
              <h6>{{ $t("Line")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addRadioElement">
              <img src="/img/dynamic-form-builder/icon-radio.svg" class="m-auto">
              <h6>{{ $t("Radio")}}</h6>
            </div>
            <div class="cursor-pointer col" @click="addAttachmentElement">
              <img src="/img/dynamic-form-builder/icon-file.svg" class="m-auto">
              <h6>{{ $t("Attachment")}}</h6>
            </div>
          </div>
        </div>

        <div class="row Edit-form w-100" style="overflow-x: auto;height: 500px;">
          <div class="col-lg-11">
            <div v-for="error in errors" class="m-3">
              <div class="alert alert-danger row p-2">
                <i class="material-icons col-1">{{ $t("clear")}}</i>
                <p class="col m-0">{{ error }}</p>
              </div>
            </div>
            <ejs-tab id="TabInstance" ref="TabInstance"
                     :showCloseButton=true
                     heightAdjustMode='Auto' overflowMode='Scrollable'
                     :selecting="function(e) {if (e.isSwiped) e.cancel = true}">
              <div class="e-tab-header">
                <div>
                </div>
              </div>
              <div
                class="vue-tabs-attaching-line"
                style="display: none"
                :class="{'hidden': tabs < 2}"
                :style="{'width': (tabs*145-145)+'px'}"></div>
              <div class="e-content">
                <div class="row ">
                </div>
              </div>
            </ejs-tab>
          </div>
        </div>
      </section>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
        {{ $t("Cancel")}}
      </button>
      <button type="button" class="btn btn-primary" @click="update">
        {{ $t("Save")}}
      </button>
    </div>
  </div>
</template>
<style>
@import "~/node_modules/@syncfusion/ej2-base/styles/material.css";
/*@import ".~/node_modules/@syncfusion/ej2-vue-buttons/styles/material.css";*/
/*@import "~/node_modules/@syncfusion/ej2-vue-popups/styles/material.css";*/
@import "~/node_modules/@syncfusion/ej2-vue-navigations/styles/material.css";

#e-item-TabInstance_0, #e-item-TabInstance_1 .e-icons.e-close-icon {
  display: none;
}

.e-tab .e-tab-header.e-close-show .e-icons.e-close-icon {
  margin-top: -25px;
  margin-right: -15px;
}

#dynamic-form-builder-tools {
  border: 1px solid #ddd;
  padding: 5px 0;
  background: #f1f1f1;
  width: 100% !important;
  margin: 0 auto;
}

.e-tab .e-tab-header .e-toolbar-item:not(.e-separator) {
  margin: 0;
  min-height: 75px;
  padding: 0;
  width: 120px;
  text-align: center;
}

.e-tab .e-tab-header .e-toolbar-item .e-tab-wrap {
  height: 36px;
  padding: 0 18px;
  margin-top: -20px;
  width: 100%;
}

.element {
  float: left;
  margin: 10px 0;
  padding: 0 5px;
}
.element .menu-icon {
  width: 24px;
  padding: 0;
}
.element .moving-tool {
  padding: 0;
  width: 24px;
  top: -20px;
  position: relative;
  margin: 0;
}
.element .context-menu-item {
  line-height: 24px;
  padding: 0 !important;
}
.element .context-menu-item span {
  padding: 0 !important;
}
.element .context-menu-root {
  width: max-content;
  top: 25px;
  z-index: 9;
  padding: 5px;
}
.element .hidden {
  display: none;
}
.element .form-control-sm {
  padding: 3px;
  margin: 0;
  border: 1px solid #eee;
  float: right;
  width: 110px;
}
.element .notes-text {
  position: absolute;
  top: 0;
  left: 103%;
  width: 300px;
  height: 100px;
  background: #f9f9f9;
  padding: 5px;
}
.element .ml-5 {
  margin-left: 5px;
}
.element .text-right {
  text-align: right;
}
.rotate-90 {
  transform: rotate(90deg);
}
</style>
<script>
import Vue from 'vue'
import PageTitle from "~/components/dynamic-elements/PageTitle";
import TextElement from '~/components/dynamic-elements/TextElement'
import LabelElement from "~/components/dynamic-elements/LabelElement";
import TableElement from "~/components/dynamic-elements/TableElement";
import TextAreaElement from "~/components/dynamic-elements/TextAreaElement";
import CheckboxElement from "~/components/dynamic-elements/CheckboxElement";
import SelectElement from "~/components/dynamic-elements/SelectElement";
import TreeElement from "~/components/dynamic-elements/TreeElement";
import LineElement from "~/components/dynamic-elements/LineElement";
import RadioElement from "~/components/dynamic-elements/RadioElement";
import AttachmentElement from "~/components/dynamic-elements/AttachmentElement";

const _PageTitle = Vue.extend(PageTitle)
const _TextElement = Vue.extend(TextElement)
const _LabelElement = Vue.extend(LabelElement)
const _TableElement = Vue.extend(TableElement)
const _TextAreaElement = Vue.extend(TextAreaElement)
const _CheckboxElement = Vue.extend(CheckboxElement)
const _SelectElement = Vue.extend(SelectElement)
const _TreeElement = Vue.extend(TreeElement)
const _LineElement = Vue.extend(LineElement)
const _RadioElement = Vue.extend(RadioElement)
const _AttachmentElement = Vue.extend(AttachmentElement)

export default {
  name: 'dynamic-form-builder',
  props: ['id'],
  components: {PageTitle},
  head() {
    return {
      title: 'Dynamic Form Builder',
      loading: false,
    }
  },
  watch: {
    // loading(val) {
    //   this.$store.commit("setLoading", val)
    // },
    validationErrors(val) {
      this.errors = []
      for (let group in val) {
        for (let error in val[group]) {
          if (this.errors.findIndex(x => x === val[group][error]) === -1)
            this.errors.push(val[group][error])
        }
      }
    }
  },
  data() {
    return {
      showTools: false,
      tabs: 0,
      current_tab: 0,
      template: {
        name: 'Template Sample',
        pages: [
          {
            title: 'Page Title',
            items: []
          }
        ]
      },
      validationErrors: [],
      errors: [],
      titles: [],
    }
  },
  mounted() {
    window.jQueryStartLoading();
    document.body.style.overflowY = 'hidden'
    // init
    const tabObj = this.$refs.TabInstance.ej2Instances
    let temp = this
    tabObj.spaceKeyDown = function (e) {
      if (e.key === ' ' && e.target.localName === 'input') {
        e.target.value += ' '
      }
    }
    tabObj.removed = function (e) {
      // e.removedIndex
      let index = e.removedIndex
      temp.titles[index].$el.remove()
      temp.tabs--
      temp.current_tab = 0
      tabObj.select(0)
      temp.titles.splice(index, 1)
      temp.template.pages.splice(index, 1)
    }
    tabObj.removing = function (e) {
      if (e.removedIndex === 0) {
        alert('can\'t remove this page')
        e.stop()
      } else {
        if (!confirm('Are you sure ?')) {
          e.stop()
        } else {
          if (document.querySelector('.tab-' + (temp.tabs - 1)) && document.querySelector('.tab-' + (temp.tabs - 1)).closest('.e-toolbar-item'))
            document.querySelector('.tab-' + (temp.tabs - 1)).closest('.e-toolbar-item').classList.remove('tab-item')

        }
      }
    }
    tabObj.animation.previous.effect = 'FadeOut'
    tabObj.animation.next.effect = 'FadeIn'
    tabObj.selected = (e) => {
      this.current_tab = e.selectedIndex
    }
    //tabObj.refresh()
    // load template
    this.$axios.get('admin/templates/' + this.id + '/get')
      .then(response => {
        if (typeof response.data.id !== 'undefined' && response.data.id) {
          let template = response.data

          // mount template pages
          // const tabObj = this.$refs.TabInstance.ej2Instances
          for (let i = 0; i < template.pages.length; i++) {
            // add page
            setTimeout(function () {
              temp.addPage(null, template.pages[i].title)
              // select page
              tabObj.select(i)
              // add page items
              for (let j = 0; j < template.pages[i].items.length; j++) {
                switch (template.pages[i].items[j].type) {
                  case 'text':
                    temp.addTextElement()
                    break;
                  case 'label':
                    temp.addLabelElement()
                    break;
                  case 'textarea':
                    temp.addTextAreaElement()
                    break;
                  case 'checkbox':
                    temp.addCheckboxElement()
                    break;
                  case 'select':
                    temp.addSelectElement()
                    break;
                  case 'table':
                    temp.addTableElement()
                    temp.template.pages[i].items[j].columns = template.pages[i].items[j].childList[0].length
                    temp.template.pages[i].items[j].rows = template.pages[i].items[j].childList.length
                    break;
                  case 'tree':
                    temp.addTreeElement()
                    break;
                  case 'line':
                    temp.addLineElement()
                    break;
                  case 'radio':
                    temp.addRadioElement()
                    break;
                  case 'file':
                    temp.addAttachmentElement()
                    break;
                }
                temp.template.pages[i].items[j].label = template.pages[i].items[j].label
                temp.template.pages[i].items[j].excel_name = template.pages[i].items[j].excel_name
                temp.template.pages[i].items[j].notes = template.pages[i].items[j].notes
                temp.template.pages[i].items[j].childList = template.pages[i].items[j].childList
                temp.template.pages[i].items[j].width = template.pages[i].items[j].width
                temp.template.pages[i].items[j].height = template.pages[i].items[j].height
                temp.template.pages[i].items[j].length = template.pages[i].items[j].length
                temp.template.pages[i].items[j].input_type = template.pages[i].items[j].input_type
                temp.template.pages[i].items[j].enabled = template.pages[i].items[j].enabled === 1
                temp.template.pages[i].items[j].required = template.pages[i].items[j].required === 1
              }
            }, 700 * i)
            setTimeout(function () {
              tabObj.select(0)
              // document.getElementById('editor').style.visibility = 'visible'
              // document.body.style.overflowY = 'auto'
              temp.loading = false
              window.jQueryEndLoading();
            }, 701 * template.pages.length)
          }

          return
        }
      })
      .catch(error => {
        if (error.response.data.message === 'Validation Error')
          this.validationErrors = error.response.data.errors
        window.jQueryToast(this.$t('Validation_Error'), 'danger');
        window.jQueryEndLoading();
        this.$emit('error', this.$t('Can_not_load_this_templates'))
      })
  },
  methods: {
    addPage(e, title = 'Page Title ..') {
      this.tabs++
      const tabObj = this.$refs.TabInstance.ej2Instances
      const item = {
        header: {
          text: '' +
            '<div class="text-center tab-' + this.tabs + '">\n' +
            '      <div class="badge bg-secondary">' + this.tabs + '</div>\n' +
            '      <div class="nav-link vue-tab-link">' +
            '          ' +
            '      </div>\n' +
            '    </div>'
        }, content: '<div class="row "></div>'
      }

      tabObj.addTab([item], this.tabs - 1)
      this.template.pages[this.tabs - 1] = {title: title, items: []}

      const pageTitle = new _PageTitle()
      pageTitle.$data.title = title
      pageTitle.$data.editing = true
      pageTitle.$mount()
      document.querySelector('.tab-' + this.tabs).closest('.e-toolbar-item').appendChild(pageTitle.$el)
      if (this.tabs > 1)
        document.querySelector('.tab-' + (this.tabs - 1)).closest('.e-toolbar-item').classList.add('tab-item')
      this.template.pages[this.tabs - 1].title = pageTitle.$data
      this.titles[this.titles.length] = pageTitle

      tabObj.select(this.tabs - 1)

      return pageTitle
    },
    addTextElement() {
      const textElement = new _TextElement()
      textElement.moveUp = this.moveUp
      textElement.moveDown = this.moveDown
      textElement.$data.referenceX = this.current_tab
      textElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      textElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(textElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = textElement.$data
    },
    addLabelElement() {
      const labelElement = new _LabelElement()
      labelElement.moveUp = this.moveUp
      labelElement.moveDown = this.moveDown
      labelElement.$data.referenceX = this.current_tab
      labelElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      labelElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(labelElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = labelElement.$data
    },
    addTableElement() {
      const tableElement = new _TableElement()
      tableElement.moveUp = this.moveUp
      tableElement.moveDown = this.moveDown
      tableElement.$data.referenceX = this.current_tab
      tableElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      tableElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(tableElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = tableElement.$data
    },
    addTextAreaElement() {
      const textAreaElement = new _TextAreaElement()
      textAreaElement.moveUp = this.moveUp
      textAreaElement.moveDown = this.moveDown
      textAreaElement.$data.referenceX = this.current_tab
      textAreaElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      textAreaElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(textAreaElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = textAreaElement.$data
    },
    addCheckboxElement() {
      const checkboxElement = new _CheckboxElement()
      checkboxElement.moveUp = this.moveUp
      checkboxElement.moveDown = this.moveDown
      checkboxElement.$data.referenceX = this.current_tab
      checkboxElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      checkboxElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(checkboxElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = checkboxElement.$data
    },
    addSelectElement() {
      const selectElement = new _SelectElement()
      selectElement.moveUp = this.moveUp
      selectElement.moveDown = this.moveDown
      selectElement.$data.referenceX = this.current_tab
      selectElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      selectElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(selectElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = selectElement.$data
    },
    addTreeElement() {
      const treeElement = new _TreeElement()
      treeElement.moveUp = this.moveUp
      treeElement.moveDown = this.moveDown
      treeElement.$data.referenceX = this.current_tab
      treeElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      treeElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(treeElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = treeElement.$data
    },
    addLineElement() {
      const lineElement = new _LineElement()
      lineElement.moveUp = this.moveUp
      lineElement.moveDown = this.moveDown
      lineElement.$data.referenceX = this.current_tab
      lineElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      lineElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(lineElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = lineElement.$data
    },
    addRadioElement() {
      const radioElement = new _RadioElement()
      radioElement.moveUp = this.moveUp
      radioElement.moveDown = this.moveDown
      radioElement.$data.referenceX = this.current_tab
      radioElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      radioElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(radioElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = radioElement.$data
    },
    addAttachmentElement() {
      const attachmentElement = new _AttachmentElement()
      attachmentElement.moveUp = this.moveUp
      attachmentElement.moveDown = this.moveDown
      attachmentElement.$data.referenceX = this.current_tab
      attachmentElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      attachmentElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(attachmentElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = attachmentElement.$data
    },

    moveUp(e, x, y) {
      let current = e.target.closest('.element')
      let prev = current.previousElementSibling

      let parent = document.querySelector('.e-content>.e-active')

      if (parent.firstChild.nextSibling !== current) {
        // swap html
        parent.insertBefore(current, prev);
        // swap data
        let tmp = this.template.pages[x].items[y]
        this.template.pages[x].items[y] = this.template.pages[x].items[y - 1]
        this.template.pages[x].items[y - 1] = tmp
        // reindexing
        this.template.pages[x].items[y - 1].referenceY = y - 1
        this.template.pages[x].items[y].referenceY = y
      }
    },
    moveDown(e, x, y) {
      let current = e.target.closest('.element')
      let next = current.nextElementSibling

      let parent = document.querySelector('.e-content>.e-active')

      if (parent.lastChild !== current) {
        // swap html
        parent.insertBefore(next, current);
        // swap data
        let tmp = this.template.pages[x].items[y]
        this.template.pages[x].items[y] = this.template.pages[x].items[y + 1]
        this.template.pages[x].items[y + 1] = tmp
        // reindexing
        this.template.pages[x].items[y + 1].referenceY = y + 1
        this.template.pages[x].items[y].referenceY = y
      }
    },

    update() {
      this.validationErrors = []

      // for (let i = 0; i < this.template.pages.length; i++) {
      //   for (let j = 0; j < this.template.pages[i].items.length; j++) {
      //     console.log(this.template.pages[i].items[j].removed)
      //     if (this.template.pages[i].items[j].removed) {
      //       this.template.pages[i].items.splice(j, 1)
      //     }
      //   }
      // }
      window.jQueryStartLoading();
      this.$axios.put('admin/templates/' + this.id, this.template).then(response => {
        window.jQueryToast(this.$t('Updated_Successfully'), 'success');
        window.jQueryEndLoading();
        if (typeof response.data.success !== 'undefined' && response.data.success === true) {
          return
        }
      }).catch(error => {
        if (error.response.data.message === 'Validation Error')
          this.validationErrors = error.response.data.errors
        window.jQueryToast(this.$t('Validation_Error'), 'danger');
        window.jQueryEndLoading();
      })
    },
  }
}
</script>

