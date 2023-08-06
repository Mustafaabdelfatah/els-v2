<template>
  <section class="scroll-section" id="hoverableRows">
    <h1>{{ $t('Employees')}}</h1>
    <div class="card mb-5">
      <div class="card-body">
        <input id="myInput" type="text" :placeholder="$t('Search_employee_name')">
        <table class="table table-hover">
          <thead>
          <tr>
            <th scope="col">{{ $t('User_Name')}}</th>
            <th scope="col">{{ $t('Average_to_close')}}</th>
            <th scope="col">{{ $t('Closed_Requests')}}</th>
            <th scope="col">{{ $t('Processing_Requests')}}</th>
            <th scope="col">{{ $t('rejected_request')}}</th>
          </tr>
          </thead>
          <tbody id="myBody">
          <tr v-for="user in users">
            <td>{{ user.name }}</td>
            <td>{{ parseInt(user.days / user.closed) }} {{ $t('days')}}</td>
            <td>{{ user.closed }}</td>
            <td>{{ user.processing }}</td>
            <td>{{ user.rejected }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    users:{
      type: Array,
      required: true,
    }
  },
  data() {
    return {

    }
  },
  mounted() {
    this.functionSearch();
  },
  methods:{
    functionSearch(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myBody tr").filter(function() {
          $(this).toggle($(this).text()
            .toLowerCase().indexOf(value) > -1)
        });
      });
    },
  }
}
</script>
