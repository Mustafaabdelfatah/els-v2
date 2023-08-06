<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content" style="width: 650px">
      <div class="modal-header" style="background: #d4d4d4;">

        <h2 class="small-title" style="font-weight:bold;margin-bottom:-1px;font-size:1.5em">{{ $t("Timeline") }}</h2>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="width: 43px;" @click="cancel">
          <svg style="position: relative; left: 10px;" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z"
              fill="currentColor" />
          </svg>
        </button>

      </div>
      <div class="modal-body">
        <div class="container">
          <!--          <p><i :class="`text-center mb-2 d-inline-block text-primary icon-20 `"></i><strong class="text-center">{{ history.name +`  `}}</strong></p>-->


          <!-- Timeline Start -->

          <div class="card mb-5">
            <div class="card-body">
              <div class="row g-0">
                <div
                  class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4">
                  <div class="w-100 d-flex sh-1"></div>
                  <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                    <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>
                  </div>
                  <div class="w-100 d-flex h-100 justify-content-center position-relative">
                    <div class="line-w-1 bg-separator h-100 position-absolute"></div>
                  </div>
                </div>
                <div class="col mb-4">
                  <div class="h-100">
                    <request v-for="(history, historyIndex) in histories"
                      :key="historyIndex" :history="history"></request>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- file -->
          <!--          <div v-for="(file,fileIndex) in history.pages" :key="fileIndex">-->
          <!--            <div class="col-12 col-sm-12 mb-12" v-for="(item,itemIndex) in file.items" :key="itemIndex">-->
          <!--              <div v-if="item.type == 'file' && item.filling.value ">-->
          <!--                <strong class="cursor-pointer" @click="downloadFile(item.filling.value)"-->
          <!--                        style="margin: 50px; color: blue; font-size: larger; font-weight: bold;">-->
          <!--                  {{ $t('Download') }} <i @click="downloadFile(item.filling.value)"-->
          <!--                                          :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i></strong>-->
          <!--              </div>-->
          <!--            </div>-->
          <!--          </div>-->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="cancel">{{
          $t('Cancel')
        }}</button>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import request from "./toggleRequest"
export default {
  name: "RequestAssignedReviewDialog",
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      showDetails: false,
      showRequests: false,
      showClosed: false,
      showAssignDetails: false,
      showAmendment: false,
      selectedRequest: null,
      histories: [],
      lastStatus: ''
    };
  },
  watch: {
    "id": function () {
      this.getRequestById()
    }

  },
  created() {

    if (this.id > 0) {
      this.getRequestById();
    }
  },
  components: {
    request
  },
  computed: {
  },
  methods: {
    getSelectValue(value) {
      if (value.split(",").length > 1) {
        return this.$i18n.locale === 'en' ? value.split(",")[0] : value;
      } else {
        return value;
      }
    },
    getImgSrc(file) {
      // const baseUrl = window.location.origin;
      const baseUrl = 'http://127.0.0.1:8000';
      return `${baseUrl}/storage/${file}`;
    },
    getLabel(label) {
      if (label.split(",").length > 1) {
        return this.$i18n.locale === 'en' ? label.split(",")[0] : label.split(",")[1];
      } else {
        return label;
      }
    },
    toggleDetails() {
      this.showDetails = !this.showDetails;
    },
    toggleAssign() {
      this.showAssignDetails = !this.showAssignDetails;
    },
    toggleAmendment() {
      this.showAmendment = !this.showAmendment;
    },
    toggleClosed() {
      this.showClosed = !this.showClosed;
    },

    selectRequest(request) {
      console.log(request, 'asd');
      this.selectedRequest = request;
    },
    toggleRequests() {
      console.log(this.showRequests, 'test');
      this.showRequests = !this.showRequests;
    },
    addHoursToDate(date) {
      return moment(date).add(3, 'hours').format('YYYY-MM-DD HH:mm');
    },
    getRequestById() {
      window.jQueryStartLoading();
      return this.$axios.get('admin/forms/get/' + this.id)
        .then(response => {
          console.log(Object.assign(response.data), 's');
          this.histories = Object.assign(response.data).reverse()
          console.log(this.histories, 'tesss');
          this.lastStatus = response.data.status
          window.jQueryEndLoading();
        }).catch(error => {
          console.log(error)
          window.jQueryEndLoading();
        })
    },
    rejectModal() {
    },
    downloadFile(file) {

      window.open(`/storage/` + file, '_blank');
    },
    cancel() {
      this.form = {};
      this.$emit('resetID');
    },
  }
};
</script>
<style scoped>
.modal-right .modal-header {
  flex: none;
  height: 50px;
  border-radius: 0;
}

.rtl .modal-right .modal-content {
  border-top-right-radius: 20px !important;
  border-bottom-right-radius: 20px !important;
}

.activeBtn {
  background-color: #35415B !important;
  width: 340px !important;
}
</style>
