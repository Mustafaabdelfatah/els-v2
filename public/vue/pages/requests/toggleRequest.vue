<template>
  <div class="d-flex flex-column justify-content-start">
    <div class="d-flex flex-column">
      <div class="mb-1">
        <span class="badge bg-primary text-uppercase">{{ lastStatus }}</span>
      </div>
      <button @click="toggleRequests(history)" class="btn btn-link" style="text-align: right;">
        {{ addHoursToDate(history.created_at) }}
      </button>
      <!-- <div class="text-alternate mb-1">{{ addHoursToDate(history.created_at) }}</div> -->
      <div v-if="showRequests">
        <div class="card mb-5">
          <div class="card-body">
            <div class="row g-0">
              <div
                class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                style="margin-left: 10px">
                <div class="w-100 d-flex sh-1"></div>
                <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
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
                      <span style="cursor: pointer; background: #6A81B1 ;  width: 150px; padding: 10px;"
                        class="badge   text-uppercase" @click="toggleDetails" :class="{ activeBtn: showDetails }">{{
                          $t('Details') }}</span>
                      <div class="row" v-if="showDetails">
                        <div class="col-12 col-sm-12 mb-12 mt-2" v-for="(page, pageIndex) in history.pages"
                          :key="pageIndex" @click="selectRequest(page)">
                          {{ (page.title.split(",").length >
                            1)
                            ? ($i18n.locale == 'en') ?
                              page.title.split(",")[0]
                              : page.title.split(",")[1]
                            : page.title }}
                          <div class="row">
                            <div class="col-12 col-sm-12 mb-12" v-for="(item, itemIndex) in page.items" :key="itemIndex">
                              <div class="form-group">
                                <label>{{ getLabel(item.label) }}</label>
                                <div class="input-group">
                                  <a v-if="item.type === 'file'" :href="'/storage/' + item.filling.value" target="_blank"
                                    style="display: flex; justify-content: space-between;">
                                    <input type="text" :value="item.filling.file_name" class="form-control" readonly>
                                    <i class="text-center mb-2 d-inline-block text-primary icon-20 cs-save"></i>
                                  </a>
                                  <input v-else-if="item.type === 'select'" type="text"
                                    :value="getSelectValue(item.filling.value)" disabled class="form-control">
                                  <input v-else type="text" :value="item.filling.value" disabled class="form-control">
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
            <!-- historyIndex == 0 &&  -->
            <div class="row g-0" v-if="history.assigned_requests.length > 0">
              <div
                class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                style="margin-left: 10px">
                <div class="w-100 d-flex sh-1"></div>
                <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
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
                      <span style="cursor: pointer; background: #6A81B1;  width: 150px;  padding: 10px;"
                        :class="{ activeBtn: showAssignDetails }" @click="toggleAssign" class="badge  text-uppercase">{{
                          $t('Assigned') }}</span>
                      <div class="row" v-if="showAssignDetails">
                        <div class="text-alternate mb-1">{{
                          addHoursToDate(history.assigned_requests[0].created_at)
                        }}</div>
                        <div class="col-12 col-sm-12 mb-12 mt-2"
                          v-for="(assign, assignIndex) in history.assigned_requests" :key="assignIndex">
                          <!-- <div class="mb-2">
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
                                            </div> -->
                          <div class="mb-2">
                            <label for="assignedFrom">{{ $t('Assigned_from') + ` : ` }}</label>
                            <input type="text" :value="assign.assigner.name" disabled class="form-control"
                              id="assignedFrom">
                          </div>
                          <div class="mb-2">
                            <label for="assignedTo">{{ $t('Assigned_to') + ` : ` }}</label>
                            <input type="text" :value="assign.user.name" disabled class="form-control" id="assignedTo">
                          </div>
                          <div class="mb-2">
                            <label for="expiryDate">{{ $t('expiry_date') + ` : ` }}</label>
                            <input type="text" :value="assign.date" disabled class="form-control" id="expiryDate">
                          </div>
                          <div class="mb-2">
                            <label for="assignedIn">{{ $t('Assigned_in') + ` : ` }}</label>
                            <input type="text" :value="addHoursToDate(assign.created_at)" disabled class="form-control"
                              id="assignedIn">
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
                <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
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
                      <span style="cursor: pointer; background: #6A81B1; width: 150px;  padding: 10px;"
                        :class="{ activeBtn: showAmendment }" @click="toggleAmendment" class="badge  text-uppercase">
                        {{ history.amendment_request[0].type === 'reject' ? $t('reject_Request') : $t('Amendment_Request')
                        }}</span>
                      <div class="row" v-if="showAmendment">
                        <div class="text-alternate mb-1">{{
                          history.amendment_request[0].created }}
                        </div>
                        <div class="col-12 col-sm-12 mb-12 mt-2"
                          v-for="(amendment, assignIndex) in history.amendment_request" :key="assignIndex">
                          <div class="mb-2">
                            <label for="amendmentFrom">{{ history.amendment_request[0].type === 'reject' ? $t('reject_by')
                              : $t('Amendment_From') + ` : ` }}</label>
                            <input type="text" :value="amendment.user.name" disabled class="form-control"
                              id="amendmentFrom">
                          </div>
                          <div class="mb-2">
                            <label for="priority">{{ $t('Priority') + ` : ` }}</label>
                            <input type="text" :value="amendment.Priority" disabled class="form-control" id="priority">
                          </div>
                          <div class="mb-2">
                            <label for="amendmentMessage">{{ history.amendment_request[0].type === 'reject' ?
                              $t('reject_note') : $t('Amendment_Message') + ` : ` }}</label>
                            <input type="text" :value="amendment.informationText" disabled class="form-control"
                              id="amendmentMessage">
                          </div>
                          <div class="mb-2">
                            <div class="row">
                              <div class="col-12" v-if="history.amendment_request">
                                <div v-for="amendment_request in history.amendment_request" :key=amendment_request.id>
                                  <div v-if="amendment_request.type === 'reject'">
                                    <div v-for="(amendmentFile, fileIndex) in amendment_request.rejected_files"
                                      :key="fileIndex" style="display: flex; justify-content: space-between;">
                                      <input type="text" :value="amendmentFile.name" class="form-control" readonly>
                                      <i @click="downloadFile(amendmentFile.file)" style="cursor: pointer;"
                                        class="text-center mb-2 d-inline-block text-primary icon-20 cs-save"></i>
                                    </div>
                                  </div>

                                  <div v-else>
                                    <div v-for="(amendmentFile, fileIndex) in amendment_request.uploaded_files"
                                      :key="fileIndex" style="display: flex; justify-content: space-between;">
                                      <input type="text" :value="amendmentFile.name" class="form-control" readonly>
                                      <i @click="downloadFile(amendmentFile.file)" style="cursor: pointer;"
                                        class="text-center mb-2 d-inline-block text-primary icon-20 cs-save"></i>
                                    </div>
                                  </div>

                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="mb-2">
                            <label for="endDate">{{ $t('end_date') + ` : ` }}</label>
                            <input type="text" :value="amendment.end_date" disabled class="form-control" id="endDate">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row g-0" v-if="history.approve && history.approve.length > 0">
              <div
                class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                style="margin-left: 10px">
                <div class="w-100 d-flex sh-1"></div>
                <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
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
                      <span style="cursor: pointer; background: #6A81B1; width: 150px;  padding: 10px;"
                        :class="{ activeBtn: showApprove }" @click="toggleApprove" class="badge  text-uppercase">{{
                          $t('Approve_Request') }}</span>
                      <div class="row" v-if="showApprove">
                        <div class="text-alternate mb-1">{{
                          history.approve[0].created }}
                        </div>
                        <div class="col-12 col-sm-12 mb-12 mt-2" v-for="(approve, assignIndex) in history.approve"
                          :key="assignIndex">
                          <div class="mb-2">
                            <label for="ApproveForm">{{ $t('Approve_From') + ` : ` }}</label>
                            <input type="text" :value="approve.user.name" disabled class="form-control" id="ApproveForm">
                          </div>
                          <div class="mb-2">
                            <label for="priority">{{ $t('Priority') + ` : ` }}</label>
                            <input type="text" :value="approve.Priority" disabled class="form-control" id="priority">
                          </div>
                          <div class="mb-2">
                            <label for="approveMessage">{{ $t('Approve_Message') + ` : ` }}</label>
                            <input type="text" :value="approve.informationText" disabled class="form-control"
                              id="approveMessage">
                          </div>
                          <div class="mb-2">
                            <div class="row">
                              <div class="col-12" v-if="history.approve">
                                <div v-for="approve in history.approve" :key=approve.id>
                                  <label for="">Files</label>
                                  <div v-for="(approveFile, fileIndex) in approve.uploaded_files" :key="fileIndex"
                                    style="display: flex; justify-content: space-between;">
                                    <input type="text" :value="approveFile.name" class="form-control" readonly>
                                    <i @click="downloadFile(approveFile.file)" style="cursor: pointer;"
                                      class="text-center mb-2 d-inline-block text-primary icon-20 cs-save"></i>
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


            <div class="row g-0" v-if="history.closed">
              <div
                class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                style="margin-left: 10px">
                <div class="w-100 d-flex sh-1"></div>
                <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
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
                      <span class="badge   text-uppercase" v-if="history.closed.comment === 'rejected'"
                        style="cursor: pointer; background: #6A81B1; width: 150px;  padding: 10px;"
                        :class="{ activeBtn: showClosed }" @click="toggleClosed">{{
                          $t('Closed_Request') }}</span>
                      <span class="badge   text-uppercase"
                        style="cursor: pointer; background: #6A81B1; width: 150px;  padding: 10px;"
                        :class="{ activeBtn: showClosed }" @click="toggleClosed" v-else>{{
                          $t('Closed_Request')
                        }}</span>
                      <div class="row" v-if="showClosed">
                        <div class="text-alternate mb-1">{{
                          addHoursToDate(history.closed.created_at)
                        }}
                        </div>
                        <div class="col-12 col-sm-12 mb-12 mt-2">
                          <div class="mb-2">
                            <label for="comment">{{ $t('comment') + ` : ` }}</label>
                            <input type="text" :value="history.closed.comment" disabled class="form-control" id="comment">
                          </div>
                          <div class="mb-2" v-if="history.comment">
                            <label for="reason">{{ $t('reason') + ` : ` }}</label>
                            <input type="text" :value="history.comment" disabled class="form-control" id="reason">
                          </div>
                          <div class="mb-2" v-if="history.request_notes">
                            <label for="reason">{{ $t('reason') + ` : ` }}</label>
                            <input type="text" :value="history.request_notes.notes" disabled class="form-control"
                              id="reason">
                          </div>
                          <div class="mb-2">
                            <label for="closedIn">{{ $t('closed_in') + ` : ` }}</label>
                            <input type="text" :value="addHoursToDate(history.closed.created_at)" disabled
                              class="form-control" id="closedIn">
                          </div>
                          <div class="mb-2" v-if="history.closed.company_judgment">
                            <label for="companyJudgment">{{ $t('company_judgment') + ` : ` }}</label>
                            <input type="text" :value="history.closed.company_judgment" disabled class="form-control"
                              id="companyJudgment">
                          </div>
                          <div class="mb-2" v-if="history.closed.case_date">
                            <label for="expectedResult">{{ $t('expected_result') + ` : ` }}</label>
                            <input type="text" :value="history.closed.case_date" disabled class="form-control"
                              id="expectedResult">
                          </div>
                          <div class="mb-2" v-if="history.closed.case_number">
                            <label for="expectedResult">{{ $t('expected_result') + ` : ` }}</label>
                            <input type="text" :value="history.closed.case_number" disabled class="form-control"
                              id="expectedResult">
                          </div>
                          <div class="mb-2" v-if="history.closed.type_of_judge">
                            <label for="typeOfJudge">{{ $t('type_of_judge') + ` : ` }}</label>
                            <input type="text" :value="history.closed.type_of_judge" disabled class="form-control"
                              id="typeOfJudge">
                          </div>
                          <div class="mb-2" v-if="history.closed.expected_result">
                            <label for="expectedResult">{{ $t('expected_result') + ` : ` }}</label>
                            <input type="text" :value="history.closed.expected_result" disabled class="form-control"
                              id="expectedResult">
                          </div>
                          <div class="mb-2">
                            <div class="row">
                              <div class="col-12" v-for="(closeFile, closeFileIndex) in history.closed.files"
                                :key="closeFileIndex">
                                <label class="cursor-pointer" @click="downloadFile(closeFile.file)"
                                  style="margin: 10px 0; color: blue; font-size: larger; font-weight: bold;">
                                  <a :href="getImgSrc(closeFile.file)" target="_blank"
                                    style="display: flex; justify-content: space-between;">
                                    <input type="text" class="form-control" style="width: 350px;"
                                      :value="closeFile.fileName" readonly>
                                    <i class="text-center mb-2 d-inline-block text-primary icon-20 cs-save"></i>
                                  </a>
                                  <!-- <img @click="downloadFile(closeFile.file)"
                                                      :src="getImgSrc(closeFile.file)" :title="closeFile.fileName"
                                                      class="rounded-circle image-icon" style="width: 70px; height:70px"> -->
                                  <!-- <i @click="downloadFile(closeFile.file)"
                                                      :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i> -->
                                </label>
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
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  props: ["history"],

  data() {
    return {
      showDetails: false,
      showRequests: false,
      showClosed: false,
      showAssignDetails: false,
      showAmendment: false,
      showApprove: false,
      selectedRequest: null,
      histories: [],
      lastStatus: '',
    }
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
    toggleApprove() {
      this.showApprove = !this.showApprove;
    },
    toggleClosed() {
      this.showClosed = !this.showClosed;
    },

    selectRequest(request) {
      console.log(request, 'asd');
      this.selectedRequest = request;
    },
    toggleRequests(history) {
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
          console.log(this.histories);
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
}
</script>
