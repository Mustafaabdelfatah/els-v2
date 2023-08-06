<template>
  <modal
    name="CreateTemplateModal"
    :width="'60%'"
    :height="'auto'"
    :scrollable="true"
    :focusTrap="true"
    @before-open="reset"
    :clickToClose="false"
    :draggable="true"
    :adaptive="true">
    <form @submit.stop.prevent="create">
      <div class="modal-body row m-2">
        <div class="form-group col-6">
          <label for="name">Name <span class="position-absolute l-10 text-danger">{{
              validationErrors.name ? validationErrors.name[0] : ''
            }}</span></label>
          <input id="name" type="text" class="form-control" v-model="name"
                 :class="{'is-invalid': !!validationErrors.name }">
        </div>
        <div class="form-group col-6">
          <label for="template_id">Clone Template <span class="position-absolute l-10 text-danger">{{
              validationErrors.template_id ? validationErrors.template_id[0] : ''
            }}</span></label>
          <select id="template_id" class="form-control" v-model="template_id"
                  :class="{'is-invalid': !!validationErrors.template_id }">
            <option value=""></option>
            <option v-for="template in templates" :value="template.id">{{ template.name }}</option>
          </select>
        </div>

      </div>
      <div class="modal-footer border-top-0 pt-0">
        <div class="m-auto">
          <button class="cursor-pointer d-inline-block mt-2 px-5 btn btn-secondary rounded-0 text-white"
                  type="button" @click="$modal.hide('CreateTemplateModal')">
            {{ $t("Cancel")}}
          </button>
          <button class="cursor-pointer d-inline-block mt-2 px-5 btn button-colored rounded-0 text-white"
                  type="submit" :disabled="loading">
            <span v-show="loading" class="spinner-border spinner-border-sm login-spinner"></span>
            {{ $t("Create")}} ..
          </button>
        </div>
      </div>
    </form>
  </modal>
</template>

<script>

export default {
  name: 'Create',
  props: ['type'],
  watch: {
    loading(newValue) {
      this.$store.commit("setLoading", newValue)
    }
  },
  data() {
    return {
      loading: false,
      name: '',
      template_id: '',
      templates: [],
      validationErrors: [],
    }
  },
  methods: {
    reset() {
      this.loading = true
      this.name = ''
      this.template_id = ''
      this.validationErrors = []
      // load all templates
      this.loading = true
      this.$toasted.info('Loading Templates ..', {
        icon: 'import_export',
        duration: 1000000
      })
      this.$axios.get('templates/get/all', {
        params: {
          type: this.type
        }
      }).then(response => {
        this.$toasted.clear()
        if (typeof response.data.success !== 'undefined' && response.data.success === true) {
          this.$toasted.success("Success ..")
          this.templates = response.data.data.templates
          this.loading = false
          return
        }
        this.$toasted.general_error()
        this.loading = false
      }).catch(error => {
        this.$toasted.clear()
        this.$toast.error(error.response.data.code + ': ' + error.response.data.message, {
          duration: 2000
        })
        this.loading = false
      })
    },
    create() {
      this.loading = true
      this.$toasted.info('Storing ..', {
        icon: 'save',
        duration: 1000000
      })
      this.validationErrors = []
      if (this.name.trim() === '') {
        this.validationErrors.name = []
        this.validationErrors.name[0] = 'Name field is required'
      }

      if (Object.keys(this.validationErrors).length) {
        this.loading = false
        this.$toasted.clear()
        this.$toasted.error("Validation Error ..")
        return
      }

      this.$axios.post('templates', {
        name: this.name,
        type: this.type,
        template_id: this.template_id,
      }).then(response => {
        this.$toasted.clear()
        this.loading = false
        if (typeof response.data.success !== 'undefined' && response.data.success === true) {
          this.$toasted.success("Success ..")
          this.$modal.hide('CreateTemplateModal')
          this.$emit('callback', -2)
          return
        }
        this.$toasted.general_error()
      }).catch(error => {
        if (error.response.data.message === 'Validation Error')
          this.validationErrors = error.response.data.errors

        this.$toasted.clear()
        this.$toast.error(error.response.data.code + ': ' + error.response.data.message, {
          duration: 2000
        })
        this.loading = false
      })

    },
  }
}
</script>

<style scoped>

</style>
