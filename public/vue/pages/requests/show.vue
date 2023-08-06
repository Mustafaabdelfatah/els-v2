<template>
  <main>
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
                <div class="d-flex flex-column justify-content-start" v-for="(history,historyIndex) in histories"
                     :key="historyIndex">
                  <div class="d-flex flex-column">
                    <!--                        <a href="#" class="heading stretched-link">{{ history.name }}</a>-->
                    <div class="mb-1">
                      <span class="badge bg-primary text-uppercase">{{ lastStatus }}</span>
                    </div>
                    <div class="text-alternate mb-1">{{ addHoursToDate(history.created_at) }}</div>
                    <div class="card mb-5">
                      <div class="card-body">
                        <div class="row g-0">
                          <div
                            class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                            style="margin-left: 10px">
                            <div class="w-100 d-flex sh-1"></div>
                            <div
                              class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                              <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>
                            </div>
                            <div class="w-100 d-flex h-100 justify-content-center position-relative">
                              <div class="line-w-1 bg-separator h-100 position-absolute"></div>
                            </div>
                          </div>
                          <div class="col mb-4">
                            <div class="h-100">
                              <div class="d-flex flex-column justify-content-start">
                                <div class="d-flex flex-column">
                                  <span class="badge bg-primary text-uppercase">{{$t('Details')}}</span>
                                  <div class="row">
                                    <div class="col-12 col-sm-12 mb-12 mt-2" v-for="(page,pageIndex) in history.pages" :key="pageIndex">
                                      {{(page.title.split(",").length > 1)
                                      ? ($i18n.locale == 'en') ? page.title.split(",")[0]
                                        : page.title.split(",")[1]
                                      : page.title}}
                                      <div class="row">
                                        <div class="col-12 col-sm-12 mb-12" v-for="(item,itemIndex) in page.items" :key="itemIndex">
                                          <strong>{{ (item.label.split(",").length > 1) ?
                                            (item.label.split(",")[0]) ? ($i18n.locale == 'en') ? item.label.split(",")[0] : item.label.split(",")[1]
                                              : item.label
                                            : item.label + ` :  `}}</strong>
                                          <small v-if="item.type == 'select'">{{ ` : `+ (item.filling.value.split(",").length > 1) ?
                                            (item.filling.value.split(",")[0]) ? ($i18n.locale == 'en') ? item.filling.value.split(",")[0] : item.filling.value
                                              : item.filling.value
                                            : item.filling.value }}</small>
                                          <small v-else-if="item.type == 'file'">{{ item.filling.file_name }}</small>
                                          <small v-else>{{  item.filling.value }}</small>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row g-0" v-if="historyIndex ==0 && history.assigned_requests.length > 0">
                          <div
                            class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                            style="margin-left: 10px">
                            <div class="w-100 d-flex sh-1"></div>
                            <div
                              class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                              <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>
                            </div>
                            <div class="w-100 d-flex h-100 justify-content-center position-relative">
                              <div class="line-w-1 bg-separator h-100 position-absolute"></div>
                            </div>
                          </div>
                          <div class="col mb-4">
                            <div class="h-100">
                              <div class="d-flex flex-column justify-content-start">
                                <div class="d-flex flex-column">
                                  <div class="text-alternate mb-1">{{ addHoursToDate(history.assigned_requests[0].created_at) }}</div>
                                  <span class="badge bg-primary text-uppercase">{{$t('Assigned')}}</span>
                                  <div class="row">
                                    <div class="col-12 col-sm-12 mb-12 mt-2"  v-for="(assign,assignIndex) in history.assigned_requests" :key="assignIndex">
                                      <div class="mb-2">
                                        <strong>{{ $t('Assigned_from')+ ` : `}}</strong> <small>{{ assign.assigner.name }}</small>
                                      </div>                                          <div class="mb-2">
                                      <strong>{{ $t('Assigned_to')+ ` : `}}</strong> <small>{{ assign.user.name }}</small>
                                    </div>
                                      <div class="mb-2">
                                        <strong>{{ $t('expiry_date') + ` : ` }}</strong> <small>{{
                                          assign.date
                                        }}</small>
                                      </div>
                                      <div class="mb-2">
                                        <strong>{{ $t('Assigned_in')+ ` : `}}</strong> <small>{{ addHoursToDate(assign.created_at) }}</small>
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
                              <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>
                            </div>
                            <div class="w-100 d-flex h-100 justify-content-center position-relative">
                              <div class="line-w-1 bg-separator h-100 position-absolute"></div>
                            </div>
                          </div>
                          <div class="col mb-4">
                            <div class="h-100">
                              <div class="d-flex flex-column justify-content-start">
                                <div class="d-flex flex-column">
                                  <div class="text-alternate mb-1">{{ history.amendment_request[0].created }}</div>
                                  <span class="badge bg-primary text-uppercase">{{$t('Amendment_Request')}}</span>
                                  <div class="row">
                                    <div class="col-12 col-sm-12 mb-12 mt-2" v-for="(amendment,assignIndex) in history.amendment_request" :key="assignIndex">
                                      <div class="mb-2">
                                        <strong>{{ $t('Amendment_From')+ ` : `}}</strong> <small>{{ amendment.user.name }}</small>
                                      </div>
                                      <div class="mb-2">
                                        <strong>{{ $t('Priority')+ ` : `}}</strong> <small>{{ amendment.Priority }}</small>
                                      </div>
                                      <div class="mb-2">
                                        <strong>{{ $t('Amendment_Message')+ ` : `}}</strong> <small>{{ amendment.informationText }}</small>
                                      </div>
                                      `                                          <div class="mb-2" v-if="amendment.file">
                                      <a target="_blank" :href="'/storage/'+ amendment.file"><i :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i> {{ amendment.file.split("/")[2] }} </a>
                                    </div>
                                      `                                          <div class="mb-2">
                                      <strong>{{ $t('end_date')+ ` : `}}</strong> <small>{{ amendment.end_date }}</small>
                                    </div>
                                      `                                        </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!--                            {{ history.parent_form }}-->
                        <!--                            <div class="row g-0" >-->
                        <!--                              <div class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4">-->
                        <!--                                <div class="w-100 d-flex sh-1"></div>-->
                        <!--                                <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">-->
                        <!--                                  <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>-->
                        <!--                                </div>-->
                        <!--                                <div class="w-100 d-flex h-100 justify-content-center position-relative">-->
                        <!--                                  <div class="line-w-1 bg-separator h-100 position-absolute"></div>-->
                        <!--                                </div>-->
                        <!--                              </div>-->
                        <!--                              <div class="col mb-4">-->
                        <!--                                <div class="h-100">-->
                        <!--                                  <div class="d-flex flex-column justify-content-start">-->
                        <!--                                    <div class="d-flex flex-column">-->
                        <!--&lt;!&ndash;                                      <span class="badge bg-primary text-uppercase">{{$t('user response')}}</span>&ndash;&gt;-->
                        <!--                                      <div class="row g-0 mt-2" >-->
                        <!--                                        <div class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4">-->
                        <!--                                          <div class="w-100 d-flex sh-1"></div>-->
                        <!--                                          <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">-->
                        <!--                                            <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>-->
                        <!--                                          </div>-->
                        <!--                                          <div class="w-100 d-flex h-100 justify-content-center position-relative">-->
                        <!--                                            <div class="line-w-1 bg-separator h-100 position-absolute"></div>-->
                        <!--                                          </div>-->
                        <!--                                        </div>-->
                        <!--                                        <div class="col mb-4">-->
                        <!--                                          <div class="h-100">-->
                        <!--                                            <div class="d-flex flex-column justify-content-start">-->
                        <!--                                              <div class="d-flex flex-column">-->
                        <!--                                                <span class="badge bg-primary text-uppercase">{{history.parent_form.name}}</span>-->
                        <!--                                                  <div class="row">-->
                        <!--                                                    <div class="col-12 col-sm-12 mb-12 mt-2" v-for="(parentPage,parentPageIndex) in history.parent_form.pages" :key="parentPageIndex">-->
                        <!--                                                      {{parentPage.title}}-->
                        <!--                                                      {{(parentPage.title.split(",").length > 1)-->
                        <!--                                                      ? ($i18n.locale == 'en') ? parentPage.title.split(",")[0]-->
                        <!--                                                        : parentPage.title.split(",")[1]-->
                        <!--                                                      : parentPage.title}}-->
                        <!--                                                      <div class="row">-->
                        <!--                                                        <div class="col-12 col-sm-12 mb-12" v-for="(parentPageitem,parentPageitemIndex) in parentPage.items" :key="parentPageitemIndex">-->
                        <!--                                                          <strong>{{ (parentPageitem.label.split(",").length > 1) ?-->
                        <!--                                                            (parentPageitem.label.split(",")[0]) ? ($i18n.locale == 'en') ? parentPageitem.label.split(",")[0] : parentPageitem.label.split(",")[1]-->
                        <!--                                                              : parentPageitem.label-->
                        <!--                                                            : parentPageitem.label+ ` : `}}</strong>-->
                        <!--                                                          <small v-if="parentPageitem.type == 'select'">{{ (parentPageitem.filling.value.split(",").length > 1) ?-->
                        <!--                                                            (parentPageitem.filling.value.split(",")[0]) ? ($i18n.locale == 'en') ? parentPageitem.filling.value.split(",")[0] : parentPageitem.filling.value.split(",")[1]-->
                        <!--                                                              : parentPageitem.filling.value-->
                        <!--                                                            : parentPageitem.filling.value }}</small>-->
                        <!--                                                          <small v-else> {{ parentPageitem.filling.value }} </small>-->
                        <!--                                                        </div>-->
                        <!--                                                      </div>-->
                        <!--                                                    </div>-->
                        <!--                                                  </div>-->
                        <!--                                              </div>-->
                        <!--                                            </div>-->
                        <!--                                          </div>-->
                        <!--                                        </div>-->
                        <!--                                        <div class="row g-0" v-if="history.parent_form.assigned_requests.length > 0">-->
                        <!--                                          <div class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4">-->
                        <!--                                            <div class="w-100 d-flex sh-1"></div>-->
                        <!--                                            <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">-->
                        <!--                                              <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>-->
                        <!--                                            </div>-->
                        <!--                                            <div class="w-100 d-flex h-100 justify-content-center position-relative">-->
                        <!--                                              <div class="line-w-1 bg-separator h-100 position-absolute"></div>-->
                        <!--                                            </div>-->
                        <!--                                          </div>-->
                        <!--                                          <div class="col mb-4">-->
                        <!--                                            <div class="h-100">-->
                        <!--                                              <div class="d-flex flex-column justify-content-start">-->
                        <!--                                                <div class="d-flex flex-column">-->
                        <!--                                                  <span class="badge bg-primary text-uppercase">{{$t('Assigned')}}</span>-->
                        <!--                                                  <div class="row">-->
                        <!--                                                    <div class="col-12 col-sm-12 mb-12 mt-2" v-for="(parentAssign,parentAssignIndex) in parent.assigned_requests" :key="parentAssignIndex">-->
                        <!--                                                      <div class="mb-2">-->
                        <!--                                                        <strong>{{ $t('Assigned_from')+ ` : `}}</strong> <small>{{ parentAssign.assigner.name }}</small>-->
                        <!--                                                      </div>                                          <div class="mb-2">-->
                        <!--                                                      <strong>{{ $t('Assigned_to')+ ` : `}}</strong> <small>{{ parentAssign.user.name }}</small>-->
                        <!--                                                    </div>-->
                        <!--                                                      <div class="mb-2">-->
                        <!--                                                        <strong>{{ $t('expiry_date') + ` : ` }}</strong>-->
                        <!--                                                        <small>{{ parentAssign.date }}</small>-->
                        <!--                                                      </div>-->
                        <!--                                                      <div class="mb-2">-->
                        <!--                                                        <strong>{{ $t('Assigned_in')+ ` : `}}</strong> <small>{{ parentAssign.created_at }}</small>-->
                        <!--                                                      </div>-->
                        <!--                                                      <div class="row">-->
                        <!--                                                      </div>-->
                        <!--                                                    </div>-->
                        <!--                                                  </div>-->
                        <!--                                                </div>-->
                        <!--                                              </div>-->
                        <!--                                            </div>-->
                        <!--                                          </div>-->
                        <!--                                        </div>-->
                        <!--                                        <div class="row g-0" v-if="history.parent_form.amendment_request.length > 0">-->
                        <!--                                          <div class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4">-->
                        <!--                                            <div class="w-100 d-flex sh-1"></div>-->
                        <!--                                            <div class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">-->
                        <!--                                              <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>-->
                        <!--                                            </div>-->
                        <!--                                            <div class="w-100 d-flex h-100 justify-content-center position-relative">-->
                        <!--                                              <div class="line-w-1 bg-separator h-100 position-absolute"></div>-->
                        <!--                                            </div>-->
                        <!--                                          </div>-->
                        <!--                                          <div class="col mb-4">-->
                        <!--                                            <div class="h-100">-->
                        <!--                                              <div class="d-flex flex-column justify-content-start">-->
                        <!--                                                <div class="d-flex flex-column">-->
                        <!--                                                  <span class="badge bg-primary text-uppercase">{{$t('Amendment_Request')}}</span>-->
                        <!--                                                  <div class="row">-->
                        <!--                                                    <div class="col-12 col-sm-12 mb-12 mt-2" v-for="(parentAmendment,parentAmendmentIndex) in history.parent_form.amendment_request" :key="parentAmendmentIndex">-->
                        <!--                                                      <div class="mb-2">-->
                        <!--                                                        <strong>{{ $t('Amendment_From')+ ` : `}}</strong> <small>{{ parentAmendment.user.name }}</small>-->
                        <!--                                                      </div>-->
                        <!--                                                      <div class="mb-2">-->
                        <!--                                                        <strong>{{ $t('Priority')+ ` : `}}</strong> <small>{{ parentAmendment.Priority }}</small>-->
                        <!--                                                      </div>-->
                        <!--                                                      <div class="mb-2">-->
                        <!--                                                        <strong>{{ $t('Amendment_Message')+ ` : `}}</strong> <small>{{ parentAmendment.informationText }}</small>-->
                        <!--                                                      </div>-->
                        <!--                                                      <div class="mb-2" v-if="parentAmendment.file">-->
                        <!--                                                      <a target="_blank" :href="parentAmendment.file"><i :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i></a>-->
                        <!--                                                    </div>-->
                        <!--                                                      <div class="mb-2">-->
                        <!--                                                      <strong>{{ $t('End_date')+ ` : `}}</strong> <small>{{ parentAmendment.end_date }}</small>-->
                        <!--                                                    </div>-->
                        <!--                                                    </div>-->
                        <!--                                                  </div>-->
                        <!--                                                </div>-->
                        <!--                                              </div>-->
                        <!--                                            </div>-->
                        <!--                                          </div>-->
                        <!--                                        </div>-->
                        <!--                                      </div>-->
                        <!--                                    </div>-->
                        <!--                                  </div>-->
                        <!--                                </div>-->
                        <!--                              </div>-->
                        <!--                            </div>-->
                        <div class="row g-0" v-if="history.closed">
                          <div
                            class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4"
                            style="margin-left: 10px">
                            <div class="w-100 d-flex sh-1"></div>
                            <div
                              class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                              <div class="bg-gradient-primary sw-1 sh-1 rounded-xl position-relative"></div>
                            </div>
                            <div class="w-100 d-flex h-100 justify-content-center position-relative">
                              <div class="line-w-1 bg-separator h-100 position-absolute"></div>
                            </div>
                          </div>
                          <div class="col mb-4">
                            <div class="h-100">
                              <div class="d-flex flex-column justify-content-start">
                                <div class="d-flex flex-column">
                                  <div class="text-alternate mb-1">{{ addHoursToDate(history.closed.created_at)}}</div>
                                  <span class="badge bg-success text-uppercase">{{$t('Closed_Request')}}</span>
                                  <div class="row">
                                    <div class="col-12 col-sm-12 mb-12 mt-2">
                                      <div class="mb-2">
                                        <strong>{{ $t('Amendment_From')+ ` : `}}</strong> <small>{{ history.closed.comment }}</small>
                                      </div>
                                      <div class="mb-2">
                                        <strong>{{ $t('closed_in')+ ` : `}}</strong> <small>{{ addHoursToDate(history.closed.created_at ) }}</small>
                                      </div>
                                      <div class="mb-2" v-if="history.closed.company_judgment">
                                        <strong>{{ $t('company_judgment')+ ` : `}}</strong> <small>{{ history.closed.company_judgment }}</small>
                                      </div>
                                      <div class="mb-2" v-if="history.closed.case_date">
                                        <strong>{{ $t('expected_result')+ ` : `}}</strong> <small>{{ history.closed.case_date }}</small>
                                      </div>
                                      <div class="mb-2" v-if="history.closed.case_number">
                                        <strong>{{ $t('expected_result')+ ` : `}}</strong> <small>{{ history.closed.case_number }}</small>
                                      </div>
                                      <div class="mb-2" v-if="history.closed.type_of_judge">
                                        <strong>{{ $t('type_of_judge')+ ` : `}}</strong> <small>{{ history.closed.type_of_judge }}</small>
                                      </div>
                                      <div class="mb-2" v-if="history.closed.expected_result">
                                        <strong>{{ $t('expected_result')+ ` : `}}</strong> <small>{{ history.closed.expected_result }}</small>
                                      </div>
                                      <div class="mb-2">
                                        <div class="row">
                                          <div class="col-12" v-for="(closeFile,closeFileIndex) in history.closed.files" :key="closeFileIndex">
                                            <strong class="cursor-pointer" @click="downloadFile(closeFile.file)"
                                                    style="margin: 50px; color: blue; font-size: larger; font-weight: bold;">{{ closeFile.fileName }}
                                              <i @click="downloadFile(closeFile.file)" :class="`text-center mb-2 d-inline-block text-primary icon-20 cs-save`"></i>
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
                    <!-- file -->
                    <div v-for="(file,fileIndex) in history.pages" :key="fileIndex">
                      <div class="col-12 col-sm-12 mb-12" v-for="(item,itemIndex) in file.items" :key="itemIndex">
                        <div v-if="item.type == 'file' && item.filling.value ">
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
  </main>
</template>

<script>
import moment from "moment";

export default {
  layout: "admin",
  middleware: ["auth", "admin"],
  data(){
    return {
      id: 0,
      histories: [],
      lastStatus: ''
    }
  },
  mounted() {
    this.getRequestById(this.$route.hash.substr(1).split('&').map(x => x.split('=').length && x.split('=')[0] === 'id' ? x.split('=')[1] : ''));
  },
  methods:{
    addHoursToDate(date) {
      return moment(date).add(3, 'hours').format('YYYY-MM-DD HH:mm');
    },
    getRequestById(requestId)
    {
      window.jQueryStartLoading();
      return this.$axios.get('admin/forms/get/'+requestId)
        .then(response => {
          this.histories = Object.assign(response.data).reverse()
          this.lastStatus = response.data.status
          window.jQueryEndLoading();
        }).catch(error => {
          console.log(error)
          window.jQueryEndLoading();
        })
    },
    downloadFile(file){
      console.log(file)
      window.open(`/storage/` + file, '_blank');
    },
  }
}
</script>
