<template>
  <div v-if="!removed"
       class="element row"
       :style="{'height': height}"
       :class="{'col-3': width==='col-3','col-4': width==='col-4','col-6': width==='col-6','col-8': width==='col-8','col-12': width==='col-12'}"
       @focusin="fillNotes=true" @focusout="fillNotes=false">

    <input v-if="!filling&&!reviewing" type="text" v-model="label" class="col border-0">
    <label v-else class="col border-0 text-bold">
      <span v-html="$globals.linkParser(label)"></span>
      <span v-if="showRequiredNotify&&required&&!childList.text" class="text-danger"> *</span>
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
        arrown drop down
      </span>
    </div>

    <div class="note-content"
         :class="{'fadeIn': filling&&fillNotes&&notes!==null, 'fadeOut': !(filling&&fillNotes&&notes!==null)}"
         @mouseover="fillNotes=true" @mouseout="fillNotes=false">
      <p v-html="$globals.linkParser(notes)"></p>
    </div>

    <div v-if="filling&&review.comment!==''&&review.comment!==null" class="alert alert-warning">
      <p class="mb-0" v-html="$globals.linkParser(review.comment)"></p>
    </div>

    <div v-if="reviewing" class="reviewing-box">
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

    <figure>
      <figcaption></figcaption>
      <ul class="tree" style="margin: 0 auto;">
        <li>
          <code>
            <input type="text" v-model="childList.text" class="form-control" style="width: 200px" :disabled="!enabled">

            <i class="material-icons ml-2 text-success cursor-pointer" v-if="!reviewing&&enabled"
               @click="childList.nodes.push({text: '', nodes: []})">add circle</i>
          </code>
          <ul v-if="childList.nodes.length">
            <li v-for="(node, i1) in childList.nodes">
              <code>
                <input type="text" v-model="node.text" class="form-control" style="width: 200px" :disabled="!enabled">

                <i class="material-icons ml-2 text-success cursor-pointer" v-if="!reviewing&&enabled"
                   @click="childList.nodes[i1].nodes.push({text: '', nodes: []})">add_circle</i>
                <i class="material-icons ml-2 text-primary cursor-pointer" v-if="!reviewing&&enabled"
                   @click="childList.nodes.splice(i1, 1)">remove_circle</i>
              </code>
              <ul v-if="typeof node.nodes !== 'undefined' && node.nodes.length">
                <li v-for="(node2, i2) in node.nodes">
                  <code>
                    <input type="text" v-model="node2.text" class="form-control" style="width: 200px"
                           :disabled="!enabled">

                    <i class="material-icons ml-2 text-success cursor-pointer" v-if="!reviewing&&enabled"
                       @click="childList.nodes[i1].nodes[i2].nodes.push({text: '', nodes: []})">add_circle</i>
                    <i class="material-icons ml-2 text-primary cursor-pointer" v-if="!reviewing&&enabled"
                       @click="childList.nodes[i1].nodes.splice(i2, 1)">remove_circle</i>
                  </code>
                  <ul v-if="typeof node2.nodes !== 'undefined' && node2.nodes.length">
                    <li v-for="(node3, i3) in node2.nodes">
                      <code>
                        <input type="text" v-model="node3.text" class="form-control" style="width: 200px"
                               :disabled="!enabled">

                        <i class="material-icons ml-2 text-success cursor-pointer" v-if="!reviewing&&enabled"
                           @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes.push({text: '', nodes: []})">add circle</i>
                        <i class="material-icons ml-2 text-primary cursor-pointer" v-if="!reviewing&&enabled"
                           @click="childList.nodes[i1].nodes[i2].nodes.splice(i3, 1)">remove circle</i>
                      </code>
                      <ul v-if="typeof node3.nodes !== 'undefined' && node3.nodes.length">
                        <li v-for="(node4, i4) in node3.nodes">
                          <code>
                            <input type="text" v-model="node4.text" class="form-control" style="width: 200px"
                                   :disabled="!enabled">

                            <i class="material-icons ml-2 text-success cursor-pointer" v-if="!reviewing&&enabled"
                               @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes.push({text: '', nodes: []})">add circle</i>
                            <i class="material-icons ml-2 text-primary cursor-pointer" v-if="!reviewing&&enabled"
                               @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes.splice(i4, 1)">remove circle</i>
                          </code>
                          <ul v-if="typeof node4.nodes !== 'undefined' && node4.nodes.length">
                            <li v-for="(node5, i5) in node4.nodes">
                              <code>
                                <input type="text" v-model="node5.text" class="form-control" style="width: 200px"
                                       :disabled="!enabled">

                                <i class="material-icons ml-2 text-success cursor-pointer" v-if="!reviewing&&enabled"
                                   @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes[i5].nodes.push({text: '', nodes: []})">add circle</i>
                                <i class="material-icons ml-2 text-primary cursor-pointer" v-if="!reviewing&&enabled"
                                   @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes.splice(i5, 1)">remove circle</i>
                              </code>
                              <ul v-if="typeof node5.nodes !== 'undefined' && node5.nodes.length">
                                <li v-for="(node6, i6) in node5.nodes">
                                  <code>
                                    <input type="text" v-model="node6.text" class="form-control" style="width: 200px"
                                           :disabled="!enabled">

                                    <i v-if="0" class="material-icons ml-2 text-success cursor-pointer"
                                       @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes[i5].nodes[i6].nodes.push({text: '', nodes: []})">add circle</i>
                                    <i class="material-icons ml-2 text-primary cursor-pointer" v-if="!reviewing&&enabled"
                                       @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes[i5].nodes.splice(i6, 1)">remove circle</i>
                                  </code>
                                  <ul v-if="typeof node6.nodes !== 'undefined' && node6.nodes.length">

                                  </ul>
                                </li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
    </figure>

    <div class="tree-menu" v-if="0">
      <div class="row">
        <input type="text" v-model="childList.text" class="form-control" style="width: 200px" :disabled="!enabled">

        <img src="/images/add-icon.svg" alt="Add" title="Add" class="ml-2 cursor-pointer" v-if="!reviewing&&enabled"
             @click="childList.nodes.push({text: '', nodes: []})">
      </div>
      <div class="tree-menu" v-if="childList.nodes.length" v-for="(node, i1) in childList.nodes">
        <div class="row">
          <input type="text" v-model="node.text" class="form-control" style="width: 200px" :disabled="!enabled">

          <img src="/images/add-icon.svg" alt="Add" title="Add" class="ml-2 cursor-pointer" v-if="!reviewing&&enabled"
               @click="childList.nodes[i1].nodes.push({text: '', nodes: []})">
          <img src="/images/remove.svg" alt="Remove" title="Remove" width="13" class="ml-2 cursor-pointer"
               v-if="!reviewing&&enabled"
               @click="childList.nodes.splice(i1, 1)">
        </div>
        <div class="tree-menu" v-if="typeof node.nodes !== 'undefined' && node.nodes.length"
             v-for="(node2, i2) in node.nodes">
          <div class="row">
            <input type="text" v-model="node2.text" class="form-control" style="width: 200px" :disabled="!enabled">

            <img src="/images/add-icon.svg" alt="Add" title="Add" class="ml-2 cursor-pointer" v-if="!reviewing&&enabled"
                 @click="childList.nodes[i1].nodes[i2].nodes.push({text: '', nodes: []})">
            <img src="/images/remove.svg" alt="Remove" title="Remove" width="13" class="ml-2 cursor-pointer"
                 v-if="!reviewing&&enabled"
                 @click="childList.nodes[i1].nodes.splice(i2, 1)">
          </div>
          <div class="tree-menu" v-if="typeof node2.nodes !== 'undefined' && node2.nodes.length"
               v-for="(node3, i3) in node2.nodes">
            <div class="row">
              <input type="text" v-model="node3.text" class="form-control" style="width: 200px" :disabled="!enabled">

              <img src="/images/add-icon.svg" alt="Add" title="Add" class="ml-2 cursor-pointer"
                   v-if="!reviewing&&enabled"
                   @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes.push({text: '', nodes: []})">
              <img src="/images/remove.svg" alt="Remove" title="Remove" width="13" class="ml-2 cursor-pointer"
                   v-if="!reviewing&&enabled"
                   @click="childList.nodes[i1].nodes[i2].nodes.splice(i3, 1)">
            </div>
            <div class="tree-menu" v-if="typeof node3.nodes !== 'undefined' && node3.nodes.length"
                 v-for="(node4, i4) in node3.nodes">
              <div class="row">
                <input type="text" v-model="node4.text" class="form-control" style="width: 200px" :disabled="!enabled">

                <img src="/images/add-icon.svg" alt="Add" title="Add" class="ml-2 cursor-pointer"
                     v-if="!reviewing&&enabled"
                     @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes.push({text: '', nodes: []})">
                <img src="/images/remove.svg" alt="Remove" title="Remove" width="13" class="ml-2 cursor-pointer"
                     v-if="!reviewing&&enabled"
                     @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes.splice(i4, 1)">
              </div>
              <div class="tree-menu" v-if="typeof node4.nodes !== 'undefined' && node4.nodes.length"
                   v-for="(node5, i5) in node4.nodes">
                <div class="row">
                  <input type="text" v-model="node5.text" class="form-control" style="width: 200px"
                         :disabled="!enabled">

                  <img src="/images/add-icon.svg" alt="Add" title="Add" class="ml-2 cursor-pointer"
                       v-if="!reviewing&&enabled"
                       @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes[i5].nodes.push({text: '', nodes: []})">
                  <img src="/images/remove.svg" alt="Remove" title="Remove" width="13" class="ml-2 cursor-pointer"
                       v-if="!reviewing&&enabled"
                       @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes.splice(i5, 1)">
                </div>
                <div class="tree-menu" v-if="typeof node5.nodes !== 'undefined' && node5.nodes.length"
                     v-for="(node6, i6) in node5.nodes">
                  <div class="row">
                    <input type="text" v-model="node6.text" class="form-control" style="width: 200px"
                           :disabled="!enabled">

                    <img v-if="0" src="/images/add-icon.svg" alt="Add" title="Add" class="ml-2 cursor-pointer"
                         @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes[i5].nodes[i6].nodes.push({text: '', nodes: []})">
                    <img src="/images/remove.svg" alt="Remove" title="Remove" width="13" class="ml-2 cursor-pointer"
                         v-if="!reviewing&&enabled"
                         @click="childList.nodes[i1].nodes[i2].nodes[i3].nodes[i4].nodes[i5].nodes.splice(i6, 1)">
                  </div>
                </div>
              </div>
            </div>
          </div>
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
  name: 'TreeElement',
  watch: {
    showRequiredNotify: function () {
      if (this.showRequiredNotify && this.required && !this.childList.text) {
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
      type: 'tree',
      enabled: true,
      required: false,
      website_view: true,
      label: 'Title here ..',
      width: 'col-4',
      height: 'auto',
      notes: '',
      childList: {
        text: '',
        nodes: [],
      },
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
/* It's supposed to look like a tree diagram */
.tree input {
  width: 132px !important;
  height: 101px !important;
  text-align: center;
  border-radius: 32px;
  border-color: #ddd !important;
  margin-bottom: 1px;
}

.tree .add {
  position: absolute;
  top: 0;
  right: -15px;
}

.tree .remove {
  position: absolute;
  top: 25px;
  right: -22px;
}

.tree,
.tree ul,
.tree li {
  list-style: none;
  margin: 0;
  padding: 0;
  position: relative;
}

.tree {
  margin: 0 0 1em;
  text-align: center;
}

.tree,
.tree ul {
  display: table;
}

.tree ul {
  width: 100%;
}

.tree li {
  display: table-cell;
  padding: 1.5em 0;
  vertical-align: top;
  margin-top: 26px;
}

/* _________ */
.tree li:before {
  outline: solid 1px #ddd;
  content: "";
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
}

.tree li:first-child:before {
  left: 50%;
}

.tree li:last-child:before {
  right: 50%;
}

.tree code,
.tree span {
  /* border: solid .1em #666; */
  /* border-radius: .2em; */
  display: inline-block;
  margin: 0 1em 0.5em;
  /* padding: .2em .5em; */
  position: relative;
}

/* If the tree represents DOM structure */
.tree code {
  font-family: monaco, Consolas, "Lucida Console", monospace;
  margin-bottom: 21px;
}

/* | */
.tree ul:before,
.tree code:before,
.tree span:before {
  outline: solid 1px #ddd;
  content: "";
  height: 1.5em;
  left: 50%;
  position: absolute;
}

.tree ul:before {
  top: -1.5em;
}

.tree code:before,
.tree span:before {
  top: -1.55em;
}

/* The root node doesn't connect upwards */
.tree > li {
  margin-top: 0;
}

.tree > li:before,
.tree > li:after,
.tree > li > code:before,
.tree > li > span:before {
  outline: none;
}
</style>
