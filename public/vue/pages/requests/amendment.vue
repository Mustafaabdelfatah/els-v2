<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">
          Request
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <div class="container" v-if="history.form">
          <div class="card-body">
            <div class="card card-body no-shadow border mt-3">
              <div class="row">
                <div class="col-6 col-sm-6 mb-4 mb-2">
                  <strong>{{ $t('form') }} </strong>  <small>{{ history.form.name }}</small>
                </div>
                <div class="col-6 col-sm-6 mb-4 mb-2">
                  <strong>{{ $t('Priority') }} </strong>  <small>{{ history.Priority }}</small>
                </div>
                <div class="col-6 col-sm-6 mb-4 mb-2" v-if="history.file">
                  <a target="_blank" :href="history.file"><i :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i></a>
                </div>
                <div class="col-6 col-sm-6 mb-4 mb-2">
                  <strong>{{ $t('User') }} </strong>  <small>{{ history.user.name }}</small>
                </div>
                <div class="col-6 col-sm-6 mb-4 mb-2">
                  <strong> {{ (history.is_secret == 1) ? $t("Yes_secret_Data") : $t("Not_secret_Data") }}</strong>
                </div>
                <div class="col-6 col-sm-6 mb-4 mb-2">
                  <strong>{{ $t('Info') }} </strong>  <small>{{ history.informationText }}</small>
                </div>
                <div class="col-6 col-sm-6 mb-4 mb-2">
                  <strong>{{ $t('end_date') }} </strong>  <small>{{ history.end_date }}</small>
                </div>
<!--                <div class="col-12 col-sm-12 mb-4 mb-2">-->
<!--                  <lable>Comment : </lable>-->
<!--                  <textarea name="" id="" cols="30" rows="10" class="form-control" v-model="history.value"></textarea>-->
<!--                </div>-->
              </div>
<!--              <button type="button" class="btn btn-primary" @click="storeComment()" aria-label="Submit">{{ $t('Save') }}</button>-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props:{
    id: {
      type: Number,
      required: true
    }
  },
  created() {
    this.getAmendmentData()
  },
  watch:{
    "id": function (){
      this.getAmendmentData()
    }
  },
  data(){
    return {
      history:{}
    }
  },
  methods:{

    getAmendmentData()
    {
      window.jQueryStartLoading();
      //  get returned data by form id
      return this.$axios.get('admin/forms/history',{params:{'form_id':this.id}})
      .then(response => {
        this.history = Object.assign(response.data[0])
        console.log(response.data[0])
        window.jQueryEndLoading();
      }).catch(error => {
          console.log(error)
          window.jQueryEndLoading();
        })
    },
    storeComment()
    {
      window.jQueryStartLoading();
      const bodyFormData = new FormData
      for (let key in this.history) {
        let value = this.history[key]
        // bodyFormData.set(key,value)
        bodyFormData.append('form_id',this.id);
        bodyFormData.append('value',this.history.value)
      }
      return this.$axios({
        url:"admin/forms/value_request",
        method:"POST",
        data: bodyFormData,
        config: { headers: { 'Content-Type': 'multipart/form-data' } }
      }).then(response => {
        console.log(response)
        window.jQueryToast(this.$t("success_send_comment_to_user_will_be_response_as_soon_as"), "success");
        window.jQueryDatatableReload();
        window.jQueryEndLoading();
      }).catch(error => {
        console.log(error)
        window.jQueryEndLoading()
      })
    }
  }
}
</script>
