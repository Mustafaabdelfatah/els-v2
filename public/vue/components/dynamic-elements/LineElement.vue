<template>
  <div v-if="!removed" :style="{'height': height}" class="element row"
       :class="{
        'col-3': width==='col-3','col-4': width==='col-4','col-6': width==='col-6','col-8': width==='col-8','col-12': width==='col-12',
    }">
    <div v-if="!reviewing&&!filling" class="col"></div>

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
        <i class="material-icons fa-flip-vertical">
          straighten
        </i>
        <span>Height</span>
        <select v-model="height" class="width form-control-sm">
          <option value="auto">Fit</option>
          <option value="45px">Small}</option>
          <option value="90px">Medium</option>
          <option value="145px">Large</option>
        </select>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'LineElement',
  data() {
    return {
      removed: false,
      type: 'line',
      enabled: true,
      required: false,
      website_view: true,
      label: 'Title here ..',
      width: 'col-12',
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
    confirmRemove() {
      this.removed = confirm('Are you sure ?')
    }
  }
}
</script>

<style scoped>

</style>
