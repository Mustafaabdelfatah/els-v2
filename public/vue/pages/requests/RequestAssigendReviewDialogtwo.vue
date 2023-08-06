<template>
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">
      <div class="modal-body">
        <div class="container">
          <!--          <p><i :class="`text-center mb-2 d-inline-block text-primary icon-20 `"></i><strong class="text-center">{{ history.name +`  `}}</strong></p>-->
          <hr>

          <!-- Timeline Start -->
          <h2 class="small-title">{{ $t("Timeline") }}</h2>
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
                    <div class="d-flex flex-column justify-content-start" v-for="(history, historyIndex) in histories"
                      :key="historyIndex">
                      <div class="d-flex flex-column">
                        <!--                        <a href="#" class="heading stretched-link">{{ history.name }}</a>-->
                        <div class="mb-1">
                          <span class="badge bg-primary text-uppercase">{{ lastStatus }}</span>
                        </div>
                        <button @click="toggleRequests" class="btn btn-link">{{
                          addHoursToDate(history.created_at)
                        }}</button>

                        <!-- <div class="text-alternate mb-1">{{ addHoursToDate(history.created_at) }}</div> -->
                        <div v-if="showRequests">
                          <div class="card mb-5">
                            <div class="card-body">
                              <div class="row g-0">
                                <div
                                  class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                                  style="margin-left: 10px">
                                  <div class="w-100 d-flex sh-1"></div>
                                  <div
                                    class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                                    <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative">
                                    </div>
                                  </div>
                                  <div class="w-100 d-flex h-100 justify-content-center position-relative">
                                    <div class="line-w-1 bg-separator h-100 position-absolute">
                                    </div>
                                  </div>
                                </div>
                                <div class="col mb-4">
                                  <div class="h-100">
                                    <div class="d-flex flex-column justify-content-start">
                                      <div class="d-flex flex-column">
                                        <span class="badge bg-primary text-uppercase" @click="toggleDetails">{{
                                          $t('Details') }}</span>

                                        <div class="row" v-if="showDetails">
                                          <div class="col-12 col-sm-12 mb-12 mt-2"
                                            v-for="(page, pageIndex) in history.pages" :key="pageIndex"
                                            @click="selectRequest(page)">
                                            {{ (page.title.split(",").length >
                                              1)
                                              ? ($i18n.locale == 'en') ?
                                                page.title.split(",")[0]
                                                : page.title.split(",")[1]
                                              : page.title }}
                                            <div class="row">
                                              <div class="col-12 col-sm-12 mb-12" v-for="(item, itemIndex) in page.items"
                                                :key="itemIndex">
                                                <strong>{{
                                                  (item.label.split(",").length
                                                    > 1) ?
                                                  (item.label.split(",")[0])
                                                    ? ($i18n.locale == 'en')
                                                      ?
                                                      item.label.split(",")[0]
                                                      :
                                                      item.label.split(",")[1]
                                                    : item.label
                                                  : item.label + ` : `
                                                }}</strong>
                                                <!--                                              {{ item.filling.value }}-->
                                                <small v-if="item.type == 'select'">{{
                                                  ` : ` +
                                                  (item.filling.value.split(",").length
                                                    > 1) ?
                                                  (item.filling.value.split(",")[0])
                                                    ?
                                                    ($i18n.locale == 'en') ?
                                                      item.filling.value.split(",")[0]
                                                      : item.filling.value
                                                    : item.filling.value
                                                  : item.filling.value
                                                }}</small>
                                                <small v-else-if="item.type == 'file'">{{
                                                  item.filling.file_name
                                                }}</small>
                                                <small v-else>{{
                                                  item.filling.value
                                                }}</small>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row g-0" v-if="historyIndex == 0 && history.assigned_requests.length > 0">
                                <div
                                  class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                                  style="margin-left: 10px">
                                  <div class="w-100 d-flex sh-1"></div>
                                  <div
                                    class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                                    <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative">
                                    </div>
                                  </div>
                                  <div class="w-100 d-flex h-100 justify-content-center position-relative">
                                    <div class="line-w-1 bg-separator h-100 position-absolute">
                                    </div>
                                  </div>
                                </div>
                                <div class="col mb-4">
                                  <div class="h-100">
                                    <div class="d-flex flex-column justify-content-start">
                                      <div class="d-flex flex-column">
                                        <div class="text-alternate mb-1">{{
                                          addHoursToDate(history.assigned_requests[0].created_at)
                                        }}</div>
                                        <span class="badge bg-primary text-uppercase">{{
                                          $t('Assigned') }}</span>
                                        <div class="row">
                                          <div class="col-12 col-sm-12 mb-12 mt-2"
                                            v-for="(assign, assignIndex) in history.assigned_requests" :key="assignIndex">
                                            <div class="mb-2">
                                              <strong>{{ $t('Assigned_from') +
                                                ` : ` }}</strong> <small>{{
    assign.assigner.name
  }}</small>
                                            </div>
                                            <div class="mb-2">
                                              <strong>{{ $t('Assigned_to') + `
                                                : ` }}</strong> <small>{{
                                                  assign.user.name
                                                }}</small>
                                            </div>
                                            <div class="mb-2">
                                              <strong>{{ $t('expiry_date') + `
                                                : ` }}</strong> <small>{{
                                                  assign.date
                                                }}</small>
                                            </div>
                                            <div class="mb-2">
                                              <strong>{{ $t('Assigned_in') + `
                                                : ` }}</strong> <small>{{
                                                  addHoursToDate(assign.created_at)
                                                }}</small>
                                            </div>
                                            <div class="row">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row g-0" v-if="history.amendment_request.length > 0">
                                <div
                                  class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                                  style="margin-left: 10px">
                                  <div class="w-100 d-flex sh-1"></div>
                                  <div
                                    class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                                    <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative">
                                    </div>
                                  </div>
                                  <div class="w-100 d-flex h-100 justify-content-center position-relative">
                                    <div class="line-w-1 bg-separator h-100 position-absolute">
                                    </div>
                                  </div>
                                </div>
                                <div class="col mb-4">
                                  <div class="h-100">
                                    <div class="d-flex flex-column justify-content-start">
                                      <div class="d-flex flex-column">
                                        <div class="text-alternate mb-1">{{
                                          history.amendment_request[0].created }}
                                        </div>
                                        <span class="badge bg-primary text-uppercase">{{
                                          $t('Amendment_Request') }}</span>
                                        <div class="row">
                                          <div class="col-12 col-sm-12 mb-12 mt-2"
                                            v-for="(amendment, assignIndex) in history.amendment_request"
                                            :key="assignIndex">
                                            <div class="mb-2">
                                              <strong>{{ $t('Amendment_From')
                                                + ` : ` }}</strong>
                                              <small>{{
                                                amendment.user.name
                                              }}</small>
                                            </div>
                                            <div class="mb-2">
                                              <strong>{{ $t('Priority') + ` :
                                                ` }}</strong> <small>{{
                                                  amendment.Priority
                                                }}</small>
                                            </div>
                                            <div class="mb-2">
                                              <strong>{{
                                                $t('Amendment_Message') + `
                                                : ` }}</strong> <small>{{
                                                  amendment.informationText
                                                }}</small>
                                            </div>
                                            ` <div class="mb-2" v-if="amendment.file">
                                              <a target="_blank" :href="'/storage/' + amendment.file"><i
                                                  :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i>
                                                {{
                                                  amendment.file.split("/")[2]
                                                }} </a>
                                            </div>
                                            ` <div class="mb-2">
                                              <strong>{{ $t('end_date') + ` :
                                                ` }}</strong> <small>{{
                                                  amendment.end_date
                                                }}</small>
                                            </div>
                                            `
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row g-0" v-if="history.closed">
                                <div
                                  class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                                  style="margin-left: 10px">
                                  <div class="w-100 d-flex sh-1"></div>
                                  <div
                                    class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                                    <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative">
                                    </div>
                                  </div>
                                  <div class="w-100 d-flex h-100 justify-content-center position-relative">
                                    <div class="line-w-1 bg-separator h-100 position-absolute">
                                    </div>
                                  </div>
                                </div>
                                <div class="col mb-4">
                                  <div class="h-100">
                                    <div class="d-flex flex-column justify-content-start">
                                      <div class="d-flex flex-column">
                                        <div class="text-alternate mb-1">{{
                                          addHoursToDate(history.closed.created_at)
                                        }}
                                        </div>
                                        <span class="badge bg-danger text-uppercase"
                                          v-if="history.closed.comment === 'rejected'">{{
                                            $t('Closed_Request') }}</span>
                                        <span class="badge bg-success text-uppercase" v-else>{{ $t('Closed_Request')
                                        }}</span>
                                        <div class="row">
                                          <div class="col-12 col-sm-12 mb-12 mt-2">
                                            <div class="mb-2">
                                              <strong>{{ $t('comment') + ` : `
                                              }}</strong> <small>{{
  history.closed.comment
}}</small>
                                            </div>
                                            <div class="mb-2" v-if="history.comment">
                                              <strong>{{ $t('reason') + ` : `
                                              }}</strong> <small>{{
  history.comment
}}</small>
                                            </div>
                                            <div class="mb-2" v-if="history.request_notes">
                                              <strong>{{ $t('reason') + ` : `
                                              }}</strong> <small>{{
  history.request_notes.notes
}}</small>
                                            </div>
                                            <div class="mb-2">
                                              <strong>{{ $t('closed_in') + ` :
                                                ` }}</strong> <small>{{
                                                  addHoursToDate(history.closed.created_at)
                                                }}</small>
                                            </div>
                                            <div class="mb-2" v-if="history.closed.company_judgment">
                                              <strong>{{
                                                $t('company_judgment') + ` :
                                                ` }}</strong> <small>{{
                                                  history.closed.company_judgment
                                                }}</small>
                                            </div>
                                            <div class="mb-2" v-if="history.closed.case_date">
                                              <strong>{{ $t('expected_result')
                                                + ` : ` }}</strong>
                                              <small>{{
                                                history.closed.case_date
                                              }}</small>
                                            </div>
                                            <div class="mb-2" v-if="history.closed.case_number">
                                              <strong>{{ $t('expected_result')
                                                + ` : ` }}</strong>
                                              <small>{{
                                                history.closed.case_number
                                              }}</small>
                                            </div>
                                            <div class="mb-2" v-if="history.closed.type_of_judge">
                                              <strong>{{ $t('type_of_judge') +
                                                ` : ` }}</strong> <small>{{
    history.closed.type_of_judge
  }}</small>
                                            </div>
                                            <div class="mb-2" v-if="history.closed.expected_result">
                                              <strong>{{ $t('expected_result')
                                                + ` : ` }}</strong>
                                              <small>{{
                                                history.closed.expected_result
                                              }}</small>
                                            </div>
                                            <div class="mb-2">
                                              <div class="row">
                                                <div class="col-12"
                                                  v-for="(closeFile, closeFileIndex) in history.closed.files"
                                                  :key="closeFileIndex">
                                                  <strong class="cursor-pointer" @click="downloadFile(closeFile.file)"
                                                    style="margin: 50px; color: blue; font-size: larger; font-weight: bold;">{{
                                                      closeFile.fileName
                                                    }}
                                                    <i @click="downloadFile(closeFile.file)"
                                                      :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i>
                                                  </strong>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- file -->
                        <div v-for="(file, fileIndex) in history.pages" :key="fileIndex">
                          <div class="col-12 col-sm-12 mb-12" v-for="(item, itemIndex) in file.items" :key="itemIndex">
                            <div v-if="item.type == 'file' && item.filling.value">
                              <strong class="cursor-pointer" @click="downloadFile(item.filling.value)"
                                style="margin: 50px; color: blue; font-size: larger; font-weight: bold;">
                                {{ item.filling.file_name }} <i @click="downloadFile(item.filling.value)"
                                  :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i></strong>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
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
  methods: {
    toggleDetails() {
      this.showDetails = !this.showDetails;
    },
    selectRequest(request) {
      console.log(request);
      this.selectedRequest = request;
    },
    toggleRequests() {
      this.showRequests = !this.showRequests;
    },
    addHoursToDate(date) {
      return moment(date).add(3, 'hours').format('YYYY-MM-DD HH:mm');
    },
    getRequestById() {
      window.jQueryStartLoading();
      return this.$axios.get('admin/forms/get/' + this.id)
        .then(response => {
          this.histories = Object.assign(response.data).reverse()
          // console.log(this.histories.reverse());
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
      console.log(file)
      window.open(`/storage/` + file, '_blank');
    },
    cancel() {
      this.form = {};
      this.$emit('resetID');
    },
  }
};
</script>
