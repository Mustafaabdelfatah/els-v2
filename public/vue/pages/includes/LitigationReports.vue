<template>
  <div>
    <div class="col-md-6 col-lg-6 col-sm-12">
      <section>
        <h2 class="small-title">{{ $t("convicted_request") }}</h2>
        <div class="card mb-5">
          <div class="card-body">
            <div class="sh-35">
              <apexchart width="400" type="pie" :options="convictedOptions" :series="convicted"></apexchart>
            </div>
          </div>
        </div>
      </section>
    </div>
<!--    <div class="col-md-6 col-lg-6 col-sm-12">-->
<!--      <section>-->
<!--        <h2 class="small-title">{{ $t("condemned_request") }}</h2>-->
<!--        <div class="card mb-5">-->
<!--          <div class="card-body">-->
<!--            <div class="sh-35">-->
<!--              <apexchart width="400" type="pie" :options="condemnedOptions" :series="condemned"></apexchart>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </section>-->
<!--    </div>-->
  </div>
</template>

<script>
export default {
  name: "LitigationReports",
  props: {
    convicted:{
      type: Array,
      required: true,
    },
    condemned:{
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      convictedOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: ['total','requests']
      },
      condemnedOptions:{
        colors:['#35415b', '#23a5aa'],
        labels: ['total','requests']
      },
    }
  }
}
</script>

<style scoped>

</style>
