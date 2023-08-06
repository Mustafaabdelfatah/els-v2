  <template>
    <div class="modal-dialog" style="max-width: 50%">
      <div class="modal-content">
        <div class="modal-body" v-if="form.id > 0">
          <div class="container">
            <p class=""><i :class="`text-center mb-2 d-inline-block text-primary icon-20 `+ form.template.icon"></i><strong class="">{{ `  `+form.name }}</strong></p>
            <hr>

  <!--tabs-->
            <ul class="nav nav-tabs nav-tabs-title card-header-tabs responsive-tabs" id="titleTabsContainer" role="tablist">
              <li class="nav-item" role="presentation" v-for="(page,pageIndex) in form.pages" :key="pageIndex">
                <a class="nav-link" :class="{ 'show active': tabOben == pageIndex }" data-bs-toggle="tab" :href="`#Tab-`+pageIndex" role="tab" aria-selected="true">{{ page.title }}</a>
              </li>
              <!-- An empty list to put overflowed links -->
            </ul>
  <!--body-->
            <div class="card mb-5">
              <div class="card-body">
                <div class="tab-content">
                  <div v-for="(page,pageIndex) in form.pages" :key="pageIndex" class="tab-pane fade" :class="{ 'show active': tabOben == pageIndex }" :id="`Tab-`+pageIndex" role="tabpanel">
                    <div>
                      <div class="row">
                        <div class="col-6 col-sm-6 mb-4" v-for="(item,itemIndex) in page.items" :key="itemIndex">
                          <strong> {{ item.label }} : </strong><span v-if="item.filling">{{ item.filling.value }}</span>
                        </div>
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
            @click="cancel"
          >
            {{ $t("Cancel") }}
          </button>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    name: "RequestReviewDialog",
    props: {
      id: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        form: {},
        tabOben:0,
      };
    },
    watch:{
      "id": function (){
        this.getRequestById()
      }
    },
    created() {
      if(this.id > 0){
        this.getRequestById();
      }
    },
    computed: {
    },
    methods: {
      getRequestById()
      {
        return this.$axios.get('admin/forms/get/'+this.id)
        .then(response => {
          this.form = Object.assign(response.data)
          this.tabOben = Object.keys(this.form.pages)[0]
        }).catch(error => {
          console.log(error)
        })
      },
      cancel() {
        this.form = {};
        this.$emit('resetID');
      },
    }
  };
  </script>
