<template>
  <main>
    <div class="container">
      <div class="row">
        <div class="col">
          <!-- Title and Top Buttons Start -->
          <div class="page-title-container">
            <div class="row">
              <!-- Title Start -->
              <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">
                  {{ $t("request_report") }}
                </h1>
                <nav
                  class="breadcrumb-container d-inline-block"
                  aria-label="breadcrumb"
                >
                  <ul class="breadcrumb pt-0">
                    <li class="breadcrumb-item">
                      <nuxt-link :to="{ name: 'home' }"
                      >{{ $t("Home") }}
                      </nuxt-link>
                    </li>
                  </ul>
                </nav>
              </div>
              <!-- Title End -->

            </div>
          </div>
          <!-- Title and Top Buttons End -->

          <!-- Content Start -->
          <div>
            <div class="row" style="margin-bottom: 50px" v-if="filterData">
              <div class="col-12">
                <form class="row g-3">
                  <div class="col-md-2 col-sm-6">
                    <label class="form-label">{{ $t("requests_from") }}</label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="from"
                      placeholder="Date"
                    />
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <label class="form-label">{{ $t("to") }}</label>
                      <input
                        type="date"
                        class="form-control"
                        v-model="to"
                        :min="from"
                        placeholder="Date"
                      />
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <label class="form-label">{{ $t("Organizations") }}</label>
                    <select class="form-select" v-model="organization_id" @change="getFilterData">
                      <option value="">{{ $t("All_organizations") }}</option>
                      <option
                        v-for="(organization, index) in organizations"
                        :key="index"
                        :value="organization.id"
                      >{{ organization.name }}
                      </option
                      >
                    </select>
                  </div>
                  <div class="col-md-2 col-sm-6" v-if="organization_id === 1">
                    <label class="form-label">{{ $t("Departments") }}</label>
                    <select class="form-select" v-model="department_id">
                      <option value="">{{ $t("All_departments") }}</option>
                      <option
                        v-for="(department, index) in departments"
                        :key="index"
                        :value="department.id"
                      >{{ department.name }}
                      </option
                      >
                    </select>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <label class="form-label">{{ $t("Templates") }}</label>
                    <select class="form-select" v-model="template_id">
                      <option value="">{{ $t("All_templates") }}</option>
                      <option
                        v-for="(template, index) in templates"
                        :key="index"
                        :value="template.id"
                      >{{ ($i18n.locale == 'en') ? template.name :
                        template.ar_name ? template.ar_name : template.name}}
                      </option
                      >
                    </select>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <label class="form-label">{{ $t("Employees") }}</label>
                    <select class="form-select" v-model="employee_id">
                      <option value="">{{ $t("All_Employees") }}</option>
                      <option
                        v-for="(employee, index) in employees"
                        :key="index"
                        :value="employee.id"
                      >{{ employee.name }}
                      </option
                      >
                    </select>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <div class="mt-4 pt-2">
                      <button type="button" class="btn btn-success btn-sm" @click="filter()">
                        {{ $t("search") }}
                      </button>
                    </div>
                  </div>
                </form>
              </div>

            </div>

            <div class="row">
              <!-- Pie Chart Start -->
<!--              <div class="col-12">-->
<!--                <div class="row">-->
<!--                  <div class="col-lg-4 col-md-6 col-sm-12" v-for="(temp,tempIndex) in templateClosed" :key="tempIndex">-->
<!--                    <section>-->
<!--                      <h2 class="small-title">{{temp.name[0] }}</h2>-->
<!--                      <div class="card mb-5">-->
<!--                        <div class="card-body">-->
<!--                          <div>-->
<!--                            &lt;!&ndash;                        <canvas id="pieChart"></canvas>&ndash;&gt;-->
<!--                            <apexchart type="donut" :options="closeTempOptions" :series="temp.total"></apexchart>-->
<!--                          </div>-->
<!--                        </div>-->
<!--                      </div>-->
<!--                    </section>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->

<!--              <div class="col-md-6 col-lg-4 col-sm-12">-->
<!--                <section>-->
<!--                  <h2 class="small-title">{{ $t("closed_request") }}</h2>-->
<!--                  <div class="card mb-5">-->
<!--                    <div class="card-body">-->
<!--                      <div class="sh-35">-->
<!--                        &lt;!&ndash;                        <canvas id="pieChart"></canvas>&ndash;&gt;-->
<!--                        <apexchart width="400" type="pie" :options="closedRequestOptions" :series="employeeReport.closed"></apexchart>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </section>-->
<!--              </div>-->

<!--              <div class="col-md-6 col-lg-4 col-sm-12">-->
<!--                <section>-->
<!--                  <h2 class="small-title">{{ $t("approved_request") }}</h2>-->
<!--                  <div class="card mb-5">-->
<!--                    <div class="card-body">-->
<!--                      <div class="sh-35">-->
<!--                        &lt;!&ndash;                        <canvas id="pieChart"></canvas>&ndash;&gt;-->
<!--                        <apexchart width="400" type="pie" :options="approvedRequestOptions" :series="employeeReport.approved"></apexchart>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </section>-->
<!--              </div>-->

<!--              <div class="col-md-6 col-lg-4 col-sm-12">-->
<!--                <section>-->
<!--                  <h2 class="small-title">{{ $t("processing_request") }}</h2>-->
<!--                  <div class="card mb-5">-->
<!--                    <div class="card-body">-->
<!--                      <div class="sh-35">-->
<!--                        &lt;!&ndash;                        <canvas id="pieChart"></canvas>&ndash;&gt;-->
<!--                        <apexchart width="400" type="pie" :options="processingRequestOptions" :series="employeeReport.processing"></apexchart>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </section>-->
<!--              </div>-->

<!--              <div class="col-md-6 col-lg-6 col-sm-12">-->
<!--                <section>-->
<!--                  <h2 class="small-title">{{ $t("rejected_request") }}</h2>-->
<!--                  <div class="card mb-5">-->
<!--                    <div class="card-body">-->
<!--                      <div class="sh-35">-->
<!--                        &lt;!&ndash;                        <canvas id="pieChart"></canvas>&ndash;&gt;-->
<!--                        <apexchart width="400" type="pie" :options="rejectedRequestOptions" :series="employeeReport.rejected"></apexchart>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </section>-->
<!--              </div>-->

<!--              <div class="col-md-6 col-lg-4 col-sm-12">-->
<!--                <section>-->
<!--                  <h2 class="small-title">{{ $t("requests_chart") }}</h2>-->
<!--                  <div class="card mb-5">-->
<!--                    <div class="card-body">-->
<!--                      <div class="sh-35">-->
<!--&lt;!&ndash;                        <canvas id="pieChart"></canvas>&ndash;&gt;-->
<!--                        <apexchart width="400" type="pie" :options="chartOptions" :series="series"></apexchart>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </section>-->
<!--              </div>-->
              <!-- Pie Chart End -->

              <!-- donut Chart Start -->
              <div class="col-md-6 col-lg-6 col-sm-12" v-if="!series.every( (val, i, arr) => val === arr[0] )">
                <section>
                  <h2 class="small-title">{{ $t("requests_chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <!--                        <canvas id="pieChart"></canvas>-->
                        <apexchart v-if="chartData" width="400" type="donut" :options="chartOptions" :series="series"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
              <!-- donut Chart End -->

              <!-- bar Chart Start -->
              <div class="col-md-6 col-lg-6 col-sm-12" v-if="!series.every( (val, i, arr) => val === arr[0] )">
                <section>
                  <h2 class="small-title">{{ $t("requests_chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <!--                        <apexchart width="100%" height="250" type="bar" :options="barOptions" :series="data"></apexchart>-->
                        <apexchart v-if="chartData" width="450" type="bar" :options="barOptions" :series="data"></apexchart>

                      </div>
                    </div>
                  </div>
                </section>
              </div>
              <!-- bar Chart End -->
              <!-- area Chart Start -->
              <div class="col-md-6 col-lg-6 col-sm-12" v-if="!series.every( (val, i, arr) => val === arr[0] )">
                <section>
                  <h2 class="small-title">{{ $t("requests_chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
<!--                        <apexchart width="100%" height="250" type="bar" :options="barOptions" :series="data"></apexchart>-->
                        <apexchart v-if="chartData" width="450" type="area" :options="barOptions" :series="data"></apexchart>

                      </div>
                    </div>
                  </div>
                </section>
              </div>
              <!-- bar Chart End -->

<!--              <div class="col-6">-->
<!--                <section>-->
<!--                  <h2 class="small-title">Average to close template Chart</h2>-->
<!--                  <div class="card mb-5">-->
<!--                    <div class="card-body">-->
<!--                      <div class="sh-35">-->
<!--                        <apexchart width="450" type="area" :options="templateChartOptions" :series="forms"></apexchart>-->

<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </section>-->
<!--              </div>-->
              <div class="col-md-6 col-lg-6 col-sm-12" v-if="templatesData.length > 0">
                <section>
                  <h2 class="small-title">{{ $t("average_days_to_close_template_pie_chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart v-if="getTemplates" ref="demoChart" width="400" type="pie" :options="templateLables" :series="templatesData"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <!-- donut Chart Start -->
              <div class="col-md-6 col-lg-6 col-sm-12" v-if="templatesClosedData.length > 0">
                <section>
                  <h2 class="small-title">{{ $t("closed_templates") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart v-if="getTemplates" ref="closeChart" width="400" type="donut" :options="closeTemplateLables" :series="templatesClosedData"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
              <!-- donut Chart End -->

                <!-- bar Chart Start -->
                <div class="col-md-6 col-lg-6 col-sm-12" v-if="employeeData[0].data.length > 0">
                  <section>
                    <h2 class="small-title">{{ $t("Average time of employee processing closed requests") }}</h2>
                    <div class="card mb-5">
                      <div class="card-body">
                        <div class="sh-35">
                          <!--                        <apexchart width="100%" height="250" type="bar" :options="barOptions" :series="data"></apexchart>-->
                          <apexchart v-if="chartData" width="450" type="area" :options="employeeBar" :series="employeeData"></apexchart>

                        </div>
                      </div>
                    </div>
                  </section>
                </div>
                <!-- bar Chart End -->

                <!-- bar Chart Start -->
                <div class="col-6" v-if="adminData[0].data.length > 0">
                  <section>
                    <h2 class="small-title">{{ $t("Average time of admins assigning request") }}</h2>
                    <div class="card mb-5">
                      <div class="card-body">
                        <div class="sh-35">
                          <apexchart v-if="chartData" width="450" type="bar" :options="adminBar" :series="adminData"></apexchart>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
                <!-- bar Chart End -->
              <!-- bar Chart Start -->
              <div class="col-md-6 col-lg-6 col-sm-12" v-if="!litigationCaseTypesData[0].data.every( (val, i, arr) => val === arr[0] )">
                <section>
                  <h2 class="small-title">{{ $t("litigation case types chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart v-if="litigationChartData" width="450" type="bar" :options="litigationCaseTypes" :series="litigationCaseTypesData"></apexchart>

                      </div>
                    </div>
                  </div>
                </section>
              </div>
              <!-- bar Chart End -->

              <!-- bar Chart Start -->
              <div class="col-md-6 col-lg-6 col-sm-12" v-if="!litigationProcedureTypesData[0].data.every( (val, i, arr) => val === arr[0] )">
                <section>
                  <h2 class="small-title">{{ $t("litigation procedure types chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart v-if="litigationChartData" width="450" type="bar" :options="litigationProcedureTypes" :series="litigationProcedureTypesData"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
              <!-- bar Chart End -->

              <div class="col-md-6 col-lg-6 col-sm-12" v-if="convictedData[1] > 0">
                <section>
                  <h2 class="small-title">{{ $t("Convicted Requests Chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart width="400" type="pie" :options="convictedOptions" :series="convictedData"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <div class="col-md-6 col-lg-6 col-sm-12" v-if="condemnedData[1] > 0">
                <section>
                  <h2 class="small-title">{{ $t("Condemned Requests Chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart width="400" type="pie" :options="condemnedOptions" :series="condemnedData"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <div class="col-md-6 col-lg-6 col-sm-12" v-if="convicted[1] > 0 ">
                <section>
                  <h2 class="small-title">{{ $t("Actual cost for convicted chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart v-if="litigationCosts" width="400" type="pie" :options="convictedCostOptions" :series="convicted"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <div class="col-md-6 col-lg-6 col-sm-12" v-if="condemned[1] > 0">
                <section>
                  <h2 class="small-title">{{ $t("Actual cost for condemned chart") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart v-if="litigationCosts" width="400" type="pie" :options="condemnedCostOptions" :series="condemned"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <div class="col-md-6 col-lg-6 col-sm-12" v-if="prepareRequest[1] > 0">
                <section>
                  <h2 class="small-title">{{ $t("Prepare Request") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart width="400" type="pie" :options="prepareOptions" :series="prepareRequest"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <div class="col-md-6 col-lg-6 col-sm-12" v-if="reviewRequest[1] > 0">
                <section>
                  <h2 class="small-title">{{ $t("Review Request") }}</h2>
                  <div class="card mb-5">
                    <div class="card-body">
                      <div class="sh-35">
                        <apexchart width="400" type="pie" :options="reviewOptions" :series="reviewRequest"></apexchart>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

            </div>

            <div class="row">
              <!-- employee table -->
              <div class="col-md-6 col-lg-6 col-sm-12">
                <employees :users="users"></employees>
              </div>
              <!-- request table -->
              <div class="col-md-6 col-lg-6 col-sm-12">
                <requestes :forms="forms"></requestes>
              </div>
            </div>

          </div>


        </div>
      </div>
    </div>
  </main>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import Employees from "../../includes/employees";
import Requestes from "../../includes/requestes";
// Vue.use(VueApexCharts)
// Vue.component('apexchart', VueApexCharts)

export default {
  components: {Requestes, Employees, 'apexchart':VueApexCharts},
  name: "RequestReport",
  layout: "admin",
  middleware: ["auth", "admin"],
  head() {
    return {
      title: "RequestReport"
    };
  },
  data() {
    return {
      loading: false,
      series: [],
      from: '',
      to: new Date().toISOString().slice(0, 10),
      organization_id: '',
      department_id: '',
      template_id: '',
      employee_id: '',
      users: [],
      organizations: [],
      departments: [],
      templates: [],
      employees: [],
      chartData: false,
      getTemplates : false,
      litigationChartData: false,
      litigationCosts: false,
      filterData: false,
      templateClosed: [],
      chartOptions: {
        labels: [this.$t('pending'), this.$t('approved'), this.$t('Processing'), this.$t('Rejected'),this.$t('returned'),this.$t('Closed')]
      },
      closeTempOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('closed'),this.$t('total_employees')]
      },
      prepareOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('Prepare'),this.$t('All_Contract')]
      },
      reviewOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('Review Request'),this.$t('All_Contract')]
      },
      closedRequestOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('closed'),this.$t('total_requests')]
      },
      approvedRequestOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('approved'),this.$t('total_requests')]
      },
      processingRequestOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('process'),this.$t('total_requests')]
      },
      rejectedRequestOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('reject'),this.$t('total_requests')]
      },
      employeeReport: {},
      templateChartOptions: {
        xaxis: {
          categories: [this.$t('Legal'), this.$t('Litigation'), this.$t('Contract')]
        }
      },
      forms : [],
      // forms: [{
      //     name: 'days',
      //     data: []
      // }],
      templatesData:[],
      templatesClosedData:[],
      templateLables: {
        labels: [],
        dataLabels: {
          enabled: false,
        },
        noData: {
          text: 'No Data',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: 'red',
            fontSize: '20px',
          }
        }
      },
      closeTemplateLables: {
        labels: [],
        dataLabels: {
          enabled: false,
        },
        noData: {
          text: 'No Data',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: 'red',
            fontSize: '20px',
          }
        }
      },


      barOptions: {
        xaxis: {
          // categories: [this.$t('Pending'),this.$t('Approved'), this.$t('Processing'), this.$t('Rejected'),this.$t('Returned'),this.$t('Closed')]
          categories: [this.$t('pending'), this.$t('approved'), this.$t('Processing'), this.$t('Rejected'),this.$t('returned'),this.$t('closed')]

        }
      },
      data: [{
        name: this.$t('requests'),
        data: []
      }],

      employeeBar: {
        xaxis: {
          type: this.$t('category')
        },
        noData: {
          text: 'No Data',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: 'red',
            fontSize: '20px',
          }
        }
      },
      employeeData: [{
        name: this.$t('days'),
        data: [],
      }],

      adminBar: {
        xaxis: {
          type: this.$t('category')
        },
        noData: {
          text: 'No Data',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: 'red',
            fontSize: '20px',
          }
        }
      },
      adminData: [{
        name: this.$t('days'),
        data: [],
      }],

      litigationCaseTypes: {
        xaxis: {
          categories: [this.$t('workers'), this.$t('commercial'), this.$t('general'), this.$t('administrative'),this.$t('executive')]
        },
        noData: {
          text: 'No Data',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: 'red',
            fontSize: '20px',
          }
        }
      },
      litigationCaseTypesData: [{
        name: 'requests',
        data: [],
      }],

      litigationProcedureTypes: {
        xaxis: {
          categories: [this.$t('litigate'), this.$t('civil_defense'), this.$t('reconciliation')]
        },
        noData: {
          text: 'No Data',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: 'red',
            fontSize: '20px',
          }
        }
      },
      litigationProcedureTypesData: [{
        name: 'requests',
        data: []
      }],

      convictedData: [],
      condemnedData: [],

      convicted:[],
      condemned:[],

      convictedOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('total_requests'),this.$t('convicted_requests')],
      },
      condemnedOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('total_requests'),this.$t('condemned_requests')]
      },

      convictedCostOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('total_cost'),this.$t('convicted_cost')]
      },
      condemnedCostOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: [this.$t('total_cost'),this.$t('condemned_cost')]
      },

      prepareRequest: [],
      reviewRequest: [],

      // barOptions: {
      //   xaxis: {
      //     categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
      //   },
      // },
      // data: [{
      //   name: 'requests',
      //   data: [30, 40, 45, 50, 49, 60, 70, 81]
      // }]

    };
  },
  created() {
    this.getFilterData()
    this.requestChart()
    this.getForms()
    this.getEmployees()
    this.getEmployeesReport()
    this.getTemplateClosed()
    this.getLitigationRequests()
    this.getLitigationCost()
    this.getContractRequests()
    this.getLitigationTypes()
  },
  methods: {
    filter (){
      this.requestChart()
      this.getForms()
      this.getLitigationRequests()
      this.getLitigationTypes()
      this.getLitigationCost()
      this.getContractRequests()
    },
    updateCloseTemplateChart: function(labels) {
      this.$refs.demoChart.updateOptions({ labels: labels })
      this.$refs.closeChart.updateOptions({ labels: labels })
      // this.$refs.demoChart.updateSeries(series)
    },
    getForms(){
      this.getTemplates = false
      window.jQueryStartLoading();
      if((this.from && this.to) || this.organization_id || (this.organization_id && this.department_id) || this.template_id) {
        this.$axios
          .get("admin/reports/getForms", {
            params: {
              from: this.from,
              to: this.to,
              organization_id: this.organization_id,
              template_id: this.template_id,
              department_id: this.department_id,
            }
          })
          .then(response => {
            this.forms = response.data.data
            this.templateLables.labels = response.data.labels
            this.closeTemplateLables.labels = response.data.labels
            this.templatesData = response.data.templatesData
            this.templatesClosedData = response.data.closedTemplatesData
            setTimeout(() => {
              this.getTemplates = true
            }, 100);
            this.updateCloseTemplateChart(response.data.labels)
            window.jQueryEndLoading();
          })
          .catch(() => {
            window.jQueryEndLoading();
          });
      }else{
        this.$axios
          .get("admin/reports/getForms")
          .then(response => {
            this.forms = response.data.data
            this.templateLables.labels = response.data.labels
            this.closeTemplateLables.labels = response.data.labels
            this.templatesData = response.data.templatesData
            this.templatesClosedData = response.data.closedTemplatesData
            setTimeout(() => {
              this.getTemplates = true
            }, 100);
            this.updateCloseTemplateChart(response.data.labels)
            window.jQueryEndLoading();
          })
          .catch(() => {
            window.jQueryEndLoading();
          });
      }
    },
    getEmployees(){
      window.jQueryStartLoading();
      this.$axios
        .get("admin/reports/getEmployees")
        .then(response => {
          this.users = response.data;
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryEndLoading();
        });
    },
    getFilterData(){
      this.filterData = false;
      window.jQueryStartLoading();
      this.$axios
        .get("admin/reports/getFilterData", {
          params: {
            organization_id: this.organization_id,
          }
        })
        .then(response => {
          this.organizations = response.data.organizations;
          this.departments = response.data.departments;
          this.templates = response.data.templates;
          this.employees = response.data.employees;
          setTimeout(() => {
            this.filterData = true
          }, 100);
          window.jQueryEndLoading();
        })
        .catch(() => {
          window.jQueryEndLoading();
        });
    },
    requestChart() {
      this.chartData = false
      window.jQueryStartLoading();
      if((this.from && this.to) || this.organization_id || (this.organization_id && this.department_id) || this.template_id || this.employee_id)
      {
        this.$axios
          .get("admin/reports/requestChart", {
            params: {
              from: this.from,
              to: this.to,
              organization_id: this.organization_id,
              template_id: this.template_id,
              department_id: this.department_id,
              employee_id: this.employee_id,
            }
          })
          .then(response => {
            this.series = response.data.data;
            this.data[0].data = response.data.data;
            this.employeeData[0].data = response.data.employeeData;
            this.adminData[0].data = response.data.adminData;
            setTimeout(() => {
              this.chartData = true
            }, 100);
            window.jQueryEndLoading();
          })
          .catch(() => {
            window.jQueryEndLoading();
          });
      }else{
        this.$axios
          .get("admin/reports/requestChart")
          .then(response => {
            this.series = response.data.data;
            this.data[0].data = response.data.data;
            this.employeeData[0].data = response.data.employeeData;
            this.adminData[0].data = response.data.adminData;
            setTimeout(() => {
              this.chartData = true
            }, 100);
            window.jQueryEndLoading();
          })
          .catch(() => {
            window.jQueryEndLoading();
          });
      }
    },
    getEmployeesReport() {
      this.$axios({
        method: "GET",
        url: "admin/reports/employeeReport"
      }).then(response =>{
        this.employeeReport = Object.assign(response.data)
      }).catch(error => {
        console.log(error)
      })
    },
    getTemplateClosed()
    {
      this.$axios({
        method: "GET",
        url: "admin/reports/serviceClosed"
      }).then(response =>{
        console.log(response)
        this.templateClosed = response.data
        // console.log(response)
      }).catch(error => {
        // console.log(error)
      })
    },
    getLitigationRequests()
    {
      if((this.from && this.to) || this.organization_id || (this.organization_id && this.department_id)) {
        this.$axios({
          method: "GET",
          url: "admin/reports/litigationRequests",
          params: {
            from: this.from,
            to: this.to,
            organization_id: this.organization_id,
            department_id: this.department_id,
          }
        }).then(response => {
          this.convictedData = response.data.convictedData
          this.condemnedData = response.data.condemnedData
        }).catch(error => {
          console.log(error)
        })
      }else{
        this.$axios({
          method: "GET",
          url: "admin/reports/litigationRequests",
        }).then(response => {
          this.convictedData = response.data.convictedData
          this.condemnedData = response.data.condemnedData
        }).catch(error => {
          console.log(error)
        })
      }
    },
    getLitigationTypes()
    {
      window.jQueryStartLoading();
      this.litigationChartData = false
      if((this.from && this.to) || this.organization_id || (this.organization_id && this.department_id)) {
        this.$axios({
          method: "GET",
          url: "admin/reports/litigationTypes",
          params: {
            from: this.from,
            to: this.to,
            organization_id: this.organization_id,
            department_id: this.department_id,
          }
        }).then(response =>{
          this.litigationCaseTypesData[0].data = response.data.data;
          this.litigationProcedureTypesData[0].data = response.data.procedureTypes;
          setTimeout(() => {
            this.litigationChartData = true
          }, 100);
          window.jQueryEndLoading();
        }).catch(error => {
          console.log(error)
          window.jQueryEndLoading();
        })
      }else{
        this.$axios({
          method: "GET",
          url: "admin/reports/litigationTypes"
        }).then(response =>{
          this.litigationCaseTypesData[0].data = response.data.data;
          this.litigationProcedureTypesData[0].data = response.data.procedureTypes;
          setTimeout(() => {
            this.litigationChartData = true
          }, 100);
          window.jQueryEndLoading();
        }).catch(error => {
          console.log(error)
          window.jQueryEndLoading();
        })
      }
    },
    getContractRequests()
    {
      if((this.from && this.to) || this.organization_id || (this.organization_id && this.department_id)) {
        this.$axios({
          method: "GET",
          url: "admin/reports/contractRequests",
          params: {
            from: this.from,
            to: this.to,
            organization_id: this.organization_id,
            department_id: this.department_id,
          }
        }).then(response => {
          this.prepareRequest = response.data.prepare
          this.reviewRequest = response.data.review
        }).catch(error => {
          console.log(error)
        })
      }else{
        this.$axios({
          method: "GET",
          url: "admin/reports/contractRequests"
        }).then(response => {
          this.prepareRequest = response.data.prepare
          this.reviewRequest = response.data.review
        }).catch(error => {
          console.log(error)
        })
      }
    },
    getLitigationCost()
    {
      this.litigationCosts = false
      if((this.from && this.to) || this.organization_id || (this.organization_id && this.department_id)) {
        this.$axios({
          method: "GET",
          url: "admin/reports/litigationCost",
          params: {
            from: this.from,
            to: this.to,
            organization_id: this.organization_id,
            department_id: this.department_id,
          }
        }).then(response => {
          console.log(response.data)
          this.convicted = response.data.convicted
          this.condemned = response.data.condemned
          setTimeout(() => {
            this.litigationCosts = true
          }, 100);
        }).catch(error => {
          console.log(error)
        })
      }else{
        this.$axios({
          method: "GET",
          url: "admin/reports/litigationCost"
        }).then(response => {
          console.log(response.data)
          this.convicted = response.data.convicted
          this.condemned = response.data.condemned
          setTimeout(() => {
            this.litigationCosts = true
          }, 100);
        }).catch(error => {
          console.log(error)
        })
      }
    },
  }
};
</script>
