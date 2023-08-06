<template>
  <main>
    <div class="container">
      <h2 class="small-title">{{ $t("translations") }}</h2>
      <div class="card mb-5">
        <div class="card-body">
          <div class="row g-0">
            <div class="col mb-4">
              <table class="table table-hover custom-table">
                <thead class="thead-custom">
                  <tr>
                    <th scope="col" class="text-uppercase">Key</th>
                    <th scope="col" class="text-uppercase" v-for="lang in languages">{{ lang }}</th>
                  </tr>
                </thead>
                <tr v-for="(value, key) in translations[languages[0]]">
                  <td class="col-3">
                    <p>{{ key }}</p>
                  </td>
                  <td v-for="lang in languages">
                    <textarea type="text" class="form-control" v-model="translations[lang][key]"></textarea>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <button type="button" class="btn btn-primary mt-4" @click="update">
            {{ $t("Update") }} ...
          </button>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  name: "Translations",
  layout: "admin",
  middleware: ["auth", "admin"],
  data() {
    return {
      translations: [],
      languages: [],
    };
  },
  created() {
    // load languages
    this.languages = [
      'en', 'ar',
    ];
    // load translations
    this.$axios.get('/translations')
      .then(response => {
        if (typeof response.data.translations != 'undefined')
          this.translations = response.data.translations;
      })
      .catch(() => {

      });
  },
  methods: {
    update() {
      this.validationErrors = [];
      window.jQueryStartLoading()
      this.$axios
        .put("/translations", {
          translations: this.translations
        })
        .then(response => {
          window.jQueryEndLoading();
          window.jQueryToast(this.$t("Updated_successfully"), "success");
        })
        .catch(error => {
          window.jQueryEndLoading();
          console.log(error)
          if (error.response.data.message === "Validation Error")
            this.validationErrors = error.response.data.errors;
        });
    }
  }
}
</script>

<style scoped></style>
