<template>
  <div v-if="!removed&&renderComponent" ref="element" :style="{'height': height}" class="element row"
       :class="{
        'col-3': width==='col-3','col-4': width==='col-4','col-6': width==='col-6','col-8': width==='col-8','col-12': width==='col-12',
    }">

    <div class="tdMenu" tabindex="-1" ref="tdMenu" v-show="viewTdMenu && !reviewing&&!filling"
        :style="{top: tdMenuStyles.top, left: tdMenuStyles.left}">
      <ul class="context-menu-list context-menu-root">
        <li class="context-menu-item cursor-pointer" @click="addLabel">
          <i class="material-icons text-success">add circle</i>
          <span>Label box</span>
        </li>
        <li class="context-menu-item cursor-pointer" @click="addText">
          <i class="material-icons text-success">add circle</i>
          <span>Text box</span>
        </li>
        <li class="context-menu-item cursor-pointer" @click="addCheckbox">
          <i class="material-icons text-success">add circle</i>
          <span>Check box</span>
        </li>
        <li class="context-menu-item cursor-pointer" @click="addPercent">
          <i class="material-icons text-success">add circle</i>
          <span>Percent box</span>
        </li>
        <li class="context-menu-item cursor-pointer" @click="addSelect">
          <i class="material-icons text-success">add circle</i>
          <span>Select boxes</span>
        </li>
        <li class="context-menu-item cursor-pointer" @click="addSelectRelated">
          <i class="material-icons text-success">add circle</i>
          <span>Related select box</span>
        </li>
        <li class="context-menu-item cursor-pointer" @click="addRadio">
          <i class="material-icons text-success">add circle</i>
          <span>Radio buttons</span>
        </li>
      </ul>
    </div>

    <input v-if="!reviewing&&!filling" type="text" v-model="label" class="col border-0">
    <label v-else class="col border-0 text-bold">
      <span v-html="$globals.linkParser(label)"></span>
    </label>

    <span class="col-1 menu-icon material-icons cursor-pointer" @click="menuOpen = !menuOpen"
          v-if="!reviewing&&!filling">
      more vert
    </span>

    <div v-if="!reviewing&&!filling" class="col-1 moving-tool">
      <span class="material-icons cursor-pointer col-12 moving-up"
            @click="moveUp($event, referenceX, referenceY)">
        arrow drop up
      </span>
      <span class="material-icons cursor-pointer col-12 moving-down"
            @click="moveDown($event, referenceX, referenceY)">
        arrow drop down
      </span>
    </div>

    <div v-if="filling&&review.comment!==''&&review.comment!==null" class="alert alert-warning">
      <p class="mb-0" v-html="$globals.linkParser(review.comment)"></p>
    </div>

    <div v-if="!reviewing&&!filling">
      <div class="col text-center">
        <i class="material-icons text-success cursor-pointer" @click="addColumn">add circle</i>
        <i class="material-icons text-primary cursor-pointer" @click="removeColumn">remove circle</i>
      </div>
      <table ref="table" class="table table-bordered">
        <tr v-for="i in rows">
          <td v-for="j in columns" @contextmenu="tdMenu($event)" class="p-0" style="min-width: 20px;height: 20px;"
              :style="{width: childList[i-1][j-1].width+' !important'}">
            <div v-for="(item, index) in childList[i-1][j-1].items" class="p-0 row" style="position:relative;"
                 :class="{
                    'text-center': item.contentAlignment==='center',
                    'text-right': item.contentAlignment==='right',
                    'text-left': item.contentAlignment==='left',
                  }">
              <input v-if="item.type==='label'" type="text" v-model="item.value" class="col border-0" :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
              <input v-else-if="item.type==='checkbox'" v-model="item.value" type="checkbox" class="col mt-2" disabled
                     :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
              <input v-else-if="item.type==='radio'" v-model="item.value" type="radio" class="col mt-2" disabled
                     :name="item.name"
                     :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
              <input v-else-if="item.type==='text'" v-model="item.value" type="text" disabled class="form-control col"
                     :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
              <div v-else-if="item.type==='percent'" class="input-group col">
                <input type="text" class="form-control" disabled>
                <div class="input-group-append p-0">
                  <span class="input-group-text p-2">%</span>
                </div>
              </div>
              <div v-else-if="item.type==='select'" class="col">
                <div v-for="(option, index2) in item.options" class="row">
                  <input type="text" class="form-control col-11" v-model="option.value">

                  <img v-if="index2===0" src="/images/add-icon.svg" class="add-option p-0 pl-1 col-1"
                       style="position: unset;height: 15px;margin-top: 10px;"
                       @click="addItemSelectOption(i-1, j-1, index)">
                  <img v-else src="/images/remove.svg" class="remove-option p-0 pl-1 pl-1 col-1"
                       style="position: unset;height: 15px;margin-top: 10px;"
                       @click="removeItemSelectOption(i-1, j-1, index, index2)">
                </div>
              </div>
              <div v-else-if="item.type==='selectRelated'" class="col">
                <div v-for="(option, index2) in item.options" class="row">
                  <input type="text" class="form-control col-6" v-model="option.value">
                  <input type="text" class="form-control col-5" v-model="option.related">

                  <img v-if="index2===0" src="/images/add-icon.svg" class="add-option p-0 pl-1 col-1"
                       style="position: unset;height: 15px;margin-top: 10px;"
                       @click="addItemSelectOption(i-1, j-1, index)">
                  <img v-else src="/images/remove.svg" class="remove-option p-0 pl-1 pl-1 col-1"
                       style="position: unset;height: 15px;margin-top: 10px;"
                       @click="removeItemSelectOption(i-1, j-1, index, index2)">
                </div>
              </div>

              <span class="col-1 menu-icon material-icons cursor-pointer" @click="showTableItemMenu(i, j, index)">
                more vert
              </span>
              <div class="table-item-menu"
                   v-if="showItemMenu&&selectItemMenuX===i&&selectItemMenuY===j&&selectItemMenuZ===index">
                <ul class="context-menu-list context-menu-root">
                  <li class="context-menu-item cursor-pointer text-danger"
                      @click="childList[i-1][j-1].items.splice(index, 1);showItemMenu=true;showItemMenu=false">
                    <i class="material-icons">
                      delete forever
                    </i>
                    <span>Remove</span>
                  </li>
                  <li class="context-menu-item">
                    <i class="material-icons">
                      straighten
                    </i>
                    <span>Alignment</span>
                    <i class="cursor-pointer material-icons" @click="item.alignment='left';showItemMenu=false;showItemMenu=true">
                      format align left
                    </i>
                    <i class="cursor-pointer material-icons" @click="item.alignment='center';showItemMenu=false;showItemMenu=true">
                      format align center
                    </i>
                    <i class="cursor-pointer material-icons" @click="item.alignment='right';showItemMenu=false;showItemMenu=true">
                      format align right
                    </i>
                  </li>
                  <li v-if="item.type !== 'label'" class="context-menu-item cursor-pointer"
                      @click="childList[i-1][j-1].items[index].noting = !childList[i-1][j-1].items[index].noting;removed=true;removed=false">
                    <i class="material-icons">speaker notes</i>
                    <span>Notes</span>
                  </li>
                  <textarea v-if="item.type !== 'label'" type="text" v-model="item.notes" class="notes-text form-control-sm" :class="{'hidden': !childList[i-1][j-1].items[index].noting}"></textarea>
                  <li v-if="item.type !== 'label'" class="context-menu-item">
                    <i class="material-icons">
                      title
                    </i>
                    <span>Name</span>
                    <input v-model="item.name" class="width form-control-sm" />
                  </li>
                  <li v-if="item.type !== 'label'&&item.type !== 'percent'" class="context-menu-item">
                    <i class="material-icons">
                      keyboard
                    </i>
                    <span>Required</span>
                    <select v-model="item.required" class="width form-control-sm">
                      <option value="true">Yes</option>
                      <option value="false">No</option>
                    </select>
                  </li>
                </ul>
              </div>
            </div>
          </td>
        </tr>
      </table>
      <div class="col text-center">
        <i class="material-icons text-success cursor-pointer" @click="addRow">add circle</i>
        <i class="material-icons text-primary cursor-pointer" @click="removeRow">remove circle</i>
      </div>
    </div>
    <div v-else>
      <table ref="table" class="table table-bordered" style="height: 58%">
        <tr v-for="i in rows">
          <td v-for="j in columns" @contextmenu="tdMenu($event)" class="p-0"
              style="position: relative"
              :style="{width: childList[i-1][j-1].width+' !important'}">
            <div v-for="(item, index) in childList[i-1][j-1].items" class="p-0 row"
                 @mouseover="childList[i-1][j-1].items[index].fillNotes=true;removed=true;removed=false"
                 @mouseout="childList[i-1][j-1].items[index].fillNotes=false;removed=true;removed=false">
              <span
                v-if="showRequiredNotify&&item.required&&!item.value&&item.type!=='label'&&item.type!=='radio'&&item.type!=='checkbox'"
                class="text-danger"> *Missing Required Field</span>
              <label v-if="item.type==='label'" class="col-12 border-0" :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">{{ item.value }}</label>
              <div v-else-if="item.type==='checkbox'" class="col-12">
                <input v-if="!previewing" v-model="item.value"
                       type="checkbox" class="col-12" :required="item.required"
                       :disabled="reviewing||!enabled" :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
                <div v-else-if="item.value"><span>&#10003;</span></div>
              </div>
              <div v-else-if="item.type==='radio'" class="col-12">
                <input v-if="!previewing" v-model="item.value"
                       :value="item.name+'-'+i+'-'+j+'-'+index" :name="item.name"
                       type="radio" class="col-12" :required="item.required"
                       @change="checkRadio(item.name)" :ref="item.name"
                       :data-i="i" :data-j="j" :data-index="index"
                       :disabled="reviewing||!enabled" :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
                <div v-else-if="item.value"><span>&#10003;</span></div>
              </div>
              <div v-else-if="item.type==='text'" class="col-12">
                <input v-if="!previewing" v-model="item.value" type="text"
                       :id="item.name" :data-i="i" :data-j="j" :data-index="index"
                       class="col-12 form-control"
                       :disabled="reviewing||!enabled||(typeof item.name != 'undefined' && item.name!==null)"
                       :required="item.required" :class="{
                     'border-danger': showRequiredNotify&&item.required&&!item.value,
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
                <div v-else-if="item.value">{{ item.value }}</div>
              </div>
              <div v-else-if="item.type==='select'" class="col-12">
                <select v-if="!previewing" v-model="item.value"
                        :required="item.required" class="form-control col-12"
                        :disabled="reviewing||!enabled" :class="{
                      'border-danger': showRequiredNotify&&item.required&&!item.value,
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
                  <option v-for="option in item.options" :value="option.value">{{ option.value }}</option>
                </select>
                <div v-else-if="item.value">{{ item.value }}</div>
              </div>
              <div v-else-if="item.type==='selectRelated'" class="col-12">
                <select v-if="!previewing" v-model="item.value"
                        @change="relatedChange($event, item.name)"
                        :required="item.required" class="form-control col-12"
                        :disabled="reviewing||!enabled" :class="{
                      'border-danger': showRequiredNotify&&item.required&&!item.value,
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
                  <option v-for="option in item.options" :value="option.value" :rel="option.related">{{
                      option.value
                    }}
                  </option>
                </select>
                <div v-else-if="item.value"><span>&#10003;</span></div>
              </div>
              <div v-else-if="item.type==='percent'" class="col-12">
                <div v-if="!previewing" class="col">
                  <div v-if="i < rows" class="input-group col">
                    <select :ref="'percent'" @change="sumPercent()" v-model="item.value" class="form-control col-12"
                            :disabled="reviewing||!enabled" :required="required"
                            :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
                      <option v-for="x in [0,5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100]" :value="x">{{
                          x
                        }}
                      </option>
                    </select>
                    <div class="input-group-append p-0">
                      <span class="input-group-text p-2">%</span>
                    </div>
                  </div>
                  <div v-else class="col">
                    <p class="text-danger col-12">* Total percent must be 100%</p>
                    <div class="input-group col">
                      <input type="text" ref="totalPercent" v-model="item.value" class="form-control col-12" disabled
                             :class="{
                    'text-center': item.alignment==='center',
                    'text-right': item.alignment==='right',
                    'text-left': item.alignment==='left',
                  }">
                      <div class="input-group-append p-0">
                        <span class="input-group-text p-2">%</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else-if="item.value">{{ item.value }}</div>
              </div>

              <div class="note-content"
                   :class="{'fadeIn': filling&&item.fillNotes&&(typeof item.notes != 'undefined' && item.notes !== '' && item.notes !== null), 'fadeOut': !(filling&&item.fillNotes&&(typeof item.notes != 'undefined' && item.notes !== '' && item.notes !== null))}"
                   style="top: -100px"
                   @mouseover="childList[i-1][j-1].items[index].fillNotes=true;removed=true;removed=false"
                   @mouseout="childList[i-1][j-1].items[index].fillNotes=false;removed=true;removed=false">
                <p v-html="$globals.linkParser(item.notes)"></p>
              </div>
            </div>
          </td>
        </tr>
      </table>
      <div class="note-content" v-if="0&&fillNotes&&notes!==null">
        <p v-html="$globals.linkParser(notes)"></p>
      </div>
    </div>

    <div v-if="reviewing&&!previewing" class="reviewing-box">
      <div class="mt-1 text-left">
          <span class="mr-1 check-input">
            <label class="checkbox-container">
              <input type="checkbox" v-model="review.review">
              <span class="checkmark"></span>
            </label>
          </span>
        <span class="add-note" title="Add Note" @click="commenting=!commenting">
            <img src="/images/add-icon.svg">
           </span>
        <div class="note-input" v-if="commenting">
          <textarea class="form-control" v-model="review.comment"
                    style="min-height:60px;max-height: 60px;height: 60px; background: 0 !important;border: 0;"></textarea>
        </div>
      </div>
    </div>

    <ul class="context-menu-list context-menu-root" v-if="menuOpen" v-on:clickout="menuOpen=false">
      <li class="context-menu-item cursor-pointer text-danger" @click="confirmRemove">
        <i class="material-icons">
          delete forever
        </i>
        <span>Remove</span>
      </li>
      <li class="context-menu-item">
        <i class="material-icons">
          straighten
        </i>
        <span>Width</span>
        <select v-model="width" class="width form-control-sm">
          <option value="col-3">25%</option>
          <option value="col-4">33.3%</option>
          <option value="col-6">50%</option>
          <option value="col-8">66.6%</option>
          <option value="col-12">100%</option>
        </select>
      </li>
      <li class="context-menu-item">
        <i class="material-icons fa-flip-vertical">
          straighten
        </i>
        <span>Columns Widths</span>

        <div class="row" style="width: 230px;margin-left: 5px;">
          <div class="col-6 p-2" v-for="(col, index) in childList[0]">
            <select v-model="col.width" :disabled="index===childList[0].length-1"
                    class="form-control-sm">
              <option value="auto">Auto</option>
              <option value="25%">12.5%</option>
              <option value="25%">25%</option>
              <option value="33.3%">33.3%</option>
              <option value="50%">50%</option>
              <option value="66.6%">66.6%</option>
              <option value="100%">100%</option>
            </select>
          </div>
        </div>
      </li>

      <li class="context-menu-item">
        <i class="material-icons">view module</i>
        <span>Excel Name</span>
        <input v-model="excel_name" class="width form-control-sm"/>
      </li>
      <li class="context-menu-item cursor-pointer" @click="noting = !noting">
        <i class="material-icons">speaker notes</i>
        <span>Notes</span>
      </li>
      <textarea type="text" v-model="notes" class="notes-text form-control-sm" :class="{'hidden': !noting}"></textarea>
      <li class="context-menu-item">
        <i class="material-icons">
          mode edit
        </i>
        <span>Enabled</span>
        <select v-model="enabled" class="width form-control-sm">
          <option value="true">Enabled</option>
          <option value="false">Disabled</option>
        </select>
      </li>
      <li class="context-menu-item">
        <i class="material-icons">
          web
        </i>
        <span>Website View</span>
        <select v-model="website_view" class="height form-control-sm">
          <option value="true">Yes</option>
          <option value="false">No</option>
        </select>
      </li>
    </ul>

  </div>
</template>

<script>
export default {
  name: 'TableElement',
  watch: {
    showRequiredNotify: function () {
      let caught = false
      if (this.showRequiredNotify)
        for (let i = 0; i < this.rows; i++) {
          for (let j = 0; j < this.columns; j++) {
            for (let n = 0; n < this.childList[i][j].items.length; n++) {
              if (this.childList[i][j].items[n].required &&
                !this.childList[i][j].items[n].value &&
                this.childList[i][j].items[n].type !== 'label' &&
                this.childList[i][j].items[n].type !== 'checkbox' &&
                this.childList[i][j].items[n].type !== 'radio'
              ) {
                if (this.$el.closest('.e-item') != null) {
                  let tabIndex = this.$el.closest('.e-item').id.replace('e-content-TabInstance_', '')
                  document.querySelectorAll('.nav-index')[parseInt(tabIndex) - 1].classList.add('bg-danger')
                }
                caught = true
                break
              }
            }
            if (caught) break;
          }
          if (caught) break;
        }
    },
  },
  data() {
    return {
      showRequiredNotify: false,
      renderComponent: true,
      removed: false,
      type: 'table',
      excel_name: '',
      enabled: true,
      required: false,
      website_view: true,
      label: 'Title here ..',
      width: 'col-4',
      height: 'auto',
      notes: '',
      childList: [
        [
          {
            width: 'auto',
            items: [
              {
                type: 'label',
                excel_name: '',
                value: 'Title here ..',
                alignment: 'left',
                contentAlignment: 'left',
                required: false,
                noting: false,
                fillNotes: false,
                notes: '',
              }
            ]
          }
        ],
      ],
      noting: false,
      menuOpen: false,
      columns: 1,
      rows: 1,

      viewTdMenu: false,
      tdMenuStyles: {
        top: 0,
        left: 0
      },
      selectedColumn: 0,
      selectedRow: 0,

      showItemMenu: false,
      selectItemMenuX: 0,
      selectItemMenuY: 0,
      selectItemMenuZ: 0,

      validatePercent: true,

      reviewing: false,
      commenting: false,
      review: {
        review: false,
        comment: '',
      },
      filling: false,
      fill: '',
      fillNotes: false,

      previewing: false,

      id: 0,

      referenceX: 0,
      referenceY: 0,
    }
  },
  created: function () {
    let self = this;
    window.addEventListener('click', function (e) {
      // close dropdown when clicked outside
      if (!self.$el.contains(e.target)) {
        self.noting = false
        self.menuOpen = false
        self.viewTdMenu = false
        self.commenting = false
        self.showItemMenu = false
      }
    });
    window.addEventListener('keyup', function (e) {
      if (e.keyCode === 27 || e.key === "Escape")
        self.viewTdMenu = false;
    })
  },
  methods: {
    moveUp(e, x, y) {

    },
    moveDown(e, x, y) {

    },
    addColumn() {
      this.columns++
      for (let i = 0; i < this.rows; i++) {
        this.childList[i][this.columns - 1] = {
          width: 'auto',
          items: []
        }
      }
    },
    addRow() {
      this.rows++
      this.childList[this.rows - 1] = []
      for (let i = 0; i < this.columns; i++) {
        this.childList[this.rows - 1][i] = {
          width: 'auto',
          items: []
        }
      }
    },
    removeColumn() {
      if (this.columns > 1) {
        this.columns--
        for (let i = 0; i < this.rows; i++) {
          this.childList[i][this.columns] = {}
          this.childList[i].splice(this.columns, 1)
        }
      }
    },
    removeRow() {
      if (this.rows > 1) {
        this.rows--
        this.childList.splice(this.rows, 1)
      }
    },
    addLabel() {
      this.viewTdMenu = false
      this.childList[this.selectedRow][this.selectedColumn].items.push({
        type: 'label',
        excel_name: '',
        value: 'Title here ..',
        alignment: 'left',
        contentAlignment: 'left',
      })
    },
    addText() {
      this.viewTdMenu = false
      this.childList[this.selectedRow][this.selectedColumn].items.push({
        type: 'text',
        excel_name: '',
        name: '',
        value: '',
        alignment: 'left',
        contentAlignment: 'left',
        required: false,
        noting: false,
        fillNotes: false,
        notes: '',
      })
    },
    addCheckbox() {
      this.viewTdMenu = false
      this.childList[this.selectedRow][this.selectedColumn].items.push({
        type: 'checkbox',
        excel_name: '',
        value: '',
        alignment: 'left',
        contentAlignment: 'left',
        width: 'col',
        required: false,
        noting: false,
        fillNotes: false,
        notes: '',
      })
    },
    addRadio() {
      this.viewTdMenu = false
      this.childList[this.selectedRow][this.selectedColumn].items.push({
        type: 'radio',
        excel_name: '',
        value: false,
        alignment: 'left',
        contentAlignment: 'left',
        width: 'col',
        required: false,
        noting: false,
        fillNotes: false,
        notes: '',
        name: ''
      })
    },
    addPercent() {
      this.viewTdMenu = false
      this.childList[this.selectedRow][this.selectedColumn].items.push({
        type: 'percent',
        excel_name: '',
        value: '',
        alignment: 'left',
        contentAlignment: 'left',
        width: 'col',
        required: false,
        noting: false,
        fillNotes: false,
        notes: '',
      })
    },
    addSelect() {
      this.viewTdMenu = false
      this.childList[this.selectedRow][this.selectedColumn].items.push({
        type: 'select',
        excel_name: '',
        value: '',
        alignment: 'left',
        contentAlignment: 'left',
        width: 'col',
        options: [
          {value: 'Option here ..'}
        ],
        required: false,
        noting: false,
        fillNotes: false,
        notes: '',
      })
    },
    addSelectRelated() {
      this.viewTdMenu = false
      this.childList[this.selectedRow][this.selectedColumn].items.push({
        type: 'selectRelated',
        excel_name: '',
        name: '',
        value: '',
        alignment: 'left',
        contentAlignment: 'left',
        width: 'col',
        options: [
          {value: 'Option here ..', related: 'Related Value ..'}
        ],
        required: false,
        noting: false,
        fillNotes: false,
        notes: '',
      })
    },
    tdMenu(e) {
      //e.preventDefault()
      this.viewTdMenu = true
      this.$refs.tdMenu.focus();
      let top = e.y -25,
        left = e.x - this.$refs.element.getBoundingClientRect().left - 25,
        largestHeight = window.innerHeight - this.$refs.tdMenu.offsetHeight - 25,
        largestWidth = window.innerWidth - this.$refs.tdMenu.offsetWidth - 25
      this.tdMenuStyles.top = (top > largestHeight ? largestHeight : top) + 'px';
      this.tdMenuStyles.left = (left > largestWidth ? largestHeight : left) + 'px';

      this.selectedColumn = e.currentTarget.cellIndex
      this.selectedRow = e.currentTarget.parentNode.rowIndex
    },
    showTableItemMenu(x, y, z) {
      this.selectItemMenuX = x
      this.selectItemMenuY = y
      this.selectItemMenuZ = z
      this.showItemMenu = true
    },
    addItemSelectOption(i, j, n) {
      this.childList[i][j].items[n].options.push({
        value: 'Option here ..',
        related: 'Related Value ..'
      })

      this.renderComponent = false;
      this.$nextTick(() => {
        this.renderComponent = true;
      });
    },
    removeItemSelectOption(i, j, n, m) {
      this.childList[i][j].items[n].options.splice(m, 1)

      this.renderComponent = false;
      this.$nextTick(() => {
        this.renderComponent = true;
      });
    },
    sumPercent() {
      this.validatePercent = true
      this.$refs.totalPercent[0].classList.remove('border-danger')
      let total = 0
      for (let t = 0; t < this.$refs.percent.length; t++) {
        if (!isNaN(parseInt(this.$refs.percent[t].value)))
          total += parseInt(this.$refs.percent[t].value)
      }

      let i = this.rows - 1
      for (let j = 0; j < this.columns; j++) {
        for (let n = 0; n < this.childList[i][j].items.length; n++) {
          // console.log(this.childList[i][j].items[n].type)
          if (this.childList[i][j].items[n].type === 'percent') {
            //console.log(i, j, n)
            this.childList[i][j].items[n].value = total
          }
        }
      }

      if (total !== 100) {
        this.validatePercent = false
        this.$refs.totalPercent[0].classList.add('border-danger')
      }
    },
    confirmRemove() {
      this.removed = confirm('Are you sure ?')
    },
    checkRadio(name) {
      for (let r = 0; r < this.$refs[name].length; r++) {
        if (!this.$refs[name][r].checked) {
          // console.log(this.$refs[name][r].getAttribute('data-i'))
          // console.log(this.$refs[name][r].getAttribute('data-j'))
          // console.log(this.$refs[name][r].getAttribute('data-index'))
          this.childList[this.$refs[name][r].getAttribute('data-i') - 1]
            [this.$refs[name][r].getAttribute('data-j') - 1].items
            [this.$refs[name][r].getAttribute('data-index')].value = ''
        }
      }
    },
    relatedChange(e, id) {
      let input = document.getElementById(id)
      if (typeof input != 'undefined' && input != null) {
        this.childList[input.getAttribute('data-i') - 1][input.getAttribute('data-j') - 1]
          .items[input.getAttribute('data-index')]
          .value = e.target.options[e.target.selectedIndex].getAttribute('rel')
      }
    }
  }
}
</script>

<style scoped>
.reviewing-box {
  top: -70px !important;
}

.table-bordered td {
  border: 1px solid #ddd;
  padding: 0 15px !important;
}

.table-item-menu {
  position: absolute;
}

.tdMenu {
  z-index: 3;
  position: fixed;
  width: max-content;
  list-style-type: none;
}

</style>
