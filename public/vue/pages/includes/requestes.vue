<template>
  <section class="scroll-section" id="stripedRows">
    <h1>{{ $t('Requests_of_templates')}}</h1>
    <div class="card mb-5">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
          <tr>
            <th scope="col" colspan="1">{{ $t('Template_Name')}}</th>
            <th scope="col">{{ $t('Average_to_close')}}</th>
            <th scope="col">{{ $t('Closed')}}</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="form in forms">
            <td colspan="1">{{ form.name }}</td>
            <td>{{ parseInt(form.days / form.closed) }} {{ $t('days')}}</td>
            <td>{{ form.closed }}</td>
          </tr>
          <!--              <tr>-->
          <!--                <td colspan="2">Larry the Bird</td>-->
          <!--                <td>@twitter</td>-->
          <!--              </tr>-->
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    forms: {
      type: Array,
      required: true,
    }
  },
  data() {
    return {

    }
  }
}
</script>
