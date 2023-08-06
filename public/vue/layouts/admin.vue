<template>
  <div id="root" :dir="dir" :class="dir">
    <x-header />
    <div class="clearfix"></div>
    <Nuxt/>
    <div id="toast" class="toast-container p-3">
      <div id="liveToast" class="toast hide align-items-center border-0 fade show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body message text-white">{{ $t("Hello_world_This_is_a_toast_message")}}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    <div class="clearfix"></div>
    <x-footer/>
<!--    <notifications group="general" position="top right" animation-type="velocity" />-->
  </div>
</template>
<script>

export default {
  data() {
    return {
      lang: localStorage.getItem('locale'),
      dir:''
    };
  },
  watch: {
    '$route'() {
      window.jQueryInit();
    },
  },
  created() {
    this.handleAfterLoad()
  },
  methods:{
    handleAfterLoad() {
      if (this.$auth.$state.user.email.split('@')[1] === 'ncms.sa'
        && this.$auth.$state.user.department_id === null) {
        this.$nextTick(function () {
          this.$modal.show("ChangePasswordDialog")
        })

      }
    },
    direction() {
      if(this.lang === 'en'){
        this.dir = 'ltr'
        return this.dir;
      }
      else{
        this.dir = 'rtl'
        return this.dir;
      }
    }
  },
  mounted() {
    this.direction();
    window.jQueryInit();
    // js spinner loader
    // document.body.classList.add('spinner');
  }

}

</script>
