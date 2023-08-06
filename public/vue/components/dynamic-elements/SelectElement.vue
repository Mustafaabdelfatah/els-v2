<template>
  <div v-if="!removed" :style="{'height': height}" class="element row"
       :class="{
        'col-3': width==='col-3','col-4': width==='col-4','col-6': width==='col-6','col-8': width==='col-8','col-12': width==='col-12',
    }" @mouseover="fillNotes=true" @mouseout="fillNotes=false">
    <input v-if="!reviewing&&!filling" type="text" v-model="label" class="col border-0">
    <label v-else class="col border-0 text-bold">
      <span v-html="$globals.linkParser(label)"></span>
      <span v-if="showRequiredNotify&&required&&!fill" class="text-danger"> *</span>
    </label>

    <span class="col-1 menu-icon material-icons cursor-pointer" @click="menuOpen = !menuOpen" v-if="!reviewing&&!filling">
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

    <div v-if="previewing">
      <span v-for="child in childList" v-if="fill === child.text">{{ child.text }}</span>
    </div>
    <div v-else-if="!reviewing&&!filling">
      <div v-for="(child, i) in childList" class="col-12 row mt-1 mb-1 p-2">
        <input type="text" v-model="child.text" class="col border-0">

        <span v-if="i" class="material-icons text-primary col-1 cursor-pointer" @click="childList.splice(childList.indexOf(child), 1)">
          remove circle
        </span>
        <span v-else class="material-icons text-success col-1 cursor-pointer" @click="addOption">
          add circle
        </span>
      </div>
    </div>
    <div v-else :style="{'height': height}">
      <select class="form-control" v-model="fill"
              :disabled="reviewing||!enabled" :required="required"
        :class="{'border-danger': showRequiredNotify&&required&&!fill}">
        <option v-for="child in childList" :value="child.text">{{ child.text }}</option>
      </select>
      <div class="note-content" :class="{'fadeIn': filling&&fillNotes&&notes!==null, 'fadeOut': !(filling&&fillNotes&&notes!==null)}"
           @mouseover="fillNotes=true" @mouseout="fillNotes=false">
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
        <span class="add-note" :title="$t('Add_Note')" @click="commenting=!commenting">
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
        <i class="material-icons">view module</i>
        <span>Excel Name</span>
        <input v-model="excel_name" class="width form-control-sm" />
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
        <span>nabled</span>
        <select v-model="enabled" class="width form-control-sm">
          <option value="true">Enabled</option>
          <option value="false">Disabled</option>
        </select>
      </li>
      <li class="context-menu-item">
        <i class="material-icons">
          keyboard
        </i>
        <span>Required</span>
        <select v-model="required" class="width form-control-sm">
          <option value="true">Yes</option>
          <option value="false">No</option>
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
  name: 'SelectElement',
  watch: {
    showRequiredNotify: function () {
      if(this.showRequiredNotify&&this.required&&!this.fill) {
        if (this.$el.closest('.e-item') != null) {
          let tabIndex = this.$el.closest('.e-item').id.replace('e-content-TabInstance_', '')
          document.querySelectorAll('.nav-index')[parseInt(tabIndex) - 1].classList.add('bg-danger')
        }
      }
    }
  },
  data() {
    return {
      showRequiredNotify: false,
      removed: false,
      type: 'select',
      excel_name: '',
      enabled: true,
      required: false,
      website_view: true,
      label: 'Title here ..',
      width: 'col-4',
      height: 'auto',
      notes: '',
      childList: [
        {
          text: 'Text here ..'
        }
      ],
      noting: false,
      menuOpen: false,

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
        self.commenting = false
      }
    })
  },
  methods: {
    moveUp(e, x, y) {

    },
    moveDown(e, x, y) {

    },
    addOption() {
      this.childList.push({text: 'Text here ..'})
    },
    confirmRemove() {
      this.removed = confirm('Are you sure ?')
    }
  }
}
</script>

<style scoped>

</style>
