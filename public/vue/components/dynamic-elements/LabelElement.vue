<template>
  <div v-if="!removed" :style="{'height': height}" class="element row"
       :class="{
        'col-3': width==='col-3','col-4': width==='col-4','col-6': width==='col-6','col-8': width==='col-8','col-12': width==='col-12'||reviewing,
    }">
    <textarea v-if="!reviewing&&!filling" type="text" v-model="label" class="col border-0 background-0"
              style="font-size: 14px;min-height: 20px;max-height: 60px"
              :class="{
        'text-center': notes==='center',
        'text-right': notes==='right',
        'text-left': notes==='left',
      }"></textarea>
    <label v-else class="col border-0 text-bold" style="width:98%; font-size: 14px;" :class="{
        'text-center': notes==='center',
        'text-right': notes==='right',
        'text-left': notes==='left',
      }" v-html="$globals.linkParser(label)"></label>

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
        <i class="material-icons">
          straighten
        </i>
        <span>Height</span>
        <select v-model="height" class="height form-control-sm">
          <option value="auto">Fit</option>
          <option value="65px">Small</option>
          <option value="90px">Medium</option>
          <option value="115px">Large</option>
        </select>
      </li>
      <li class="context-menu-item">
        <i class="material-icons">
          straighten
        </i>
        <span>Alignment</span>
        <i class="cursor-pointer material-icons" @click="notes='left'">
          format align left
        </i>
        <i class="cursor-pointer material-icons" @click="notes='center'">
          format align center
        </i>
        <i class="cursor-pointer material-icons" @click="notes='right'">
          format align right
        </i>
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
  name: 'LabelElement',
  data() {
    return {
      removed: false,
      type: 'label',
      enabled: true,
      required: false,
      website_view: true,
      label: 'Title here ..',
      width: 'col-4',
      height: 'auto',
      notes: '',
      childList: [],
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

      id: 0,

      referenceX: 0,
      referenceY: 0,
    }
  },
  created: function () {
    window.addEventListener('click', (e) => {
      // close dropdown when clicked outside
      if (!this.$el.contains(e.target)) {
        this.noting = false
        this.menuOpen = false
        this.commenting = false
      }
    })
  },
  methods: {
    moveUp(e, x, y) {

    },
    moveDown(e, x, y) {

    },
    confirmRemove() {
      this.removed = confirm('Are you sure ?')
    },
  }
}
</script>

<style scoped>

</style>
