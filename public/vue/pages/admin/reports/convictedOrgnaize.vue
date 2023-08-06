<template>
  <div>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import Requestes from "../../includes/requestes";
import Employees from "../../includes/employees";
import LitigationReports from "../../includes/LitigationReports";
export default {
  props: {
    convicted: {
      type: Number,
      required: true,
    },
    total: {
      type: Number,
      required: true,
    },
  },
  components: {'apexchart':VueApexCharts},
  data() {
    return {
      ChartOption:{
        colors:['#35415b', '#23a5aa'],
        labels: ['closed','total']
      },
    }
  }
}
</script>
