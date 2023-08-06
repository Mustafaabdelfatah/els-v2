<template>
  <div>
    <!-- Background Start -->
    <div class="fixed-background"></div>
    <!-- Background End -->

    <div class="container-fluid p-0 h-100 position-relative">
      <div class="row g-0 h-100">
        <!-- Left Side Start -->
        <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100"></div>
        <!-- Left Side End -->

        <!-- Right Side Start -->
        <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
          <div class="sw-lg-60 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
            <div class="sw-lg-50 px-5">
              <div class="">
                <img src="/img/logo.png" :alt="$t('APP NAME')" width="300">
                <a href="#">
                  <div class=""></div>
                </a>
              </div>
              <div class="mb-5">
                <h2 class="cta-1 mb-0 text-primary">{{ $t('Error_404_h2') }}!</h2>
                <h2 class="display-2 text-primary">{{ $t('Error_404') }}</h2>
              </div>
              <div class="mb-5">
                <p class="h6">{{ $t('Error_404_h6') }}.</p>
                <p class="h6">
                  {{ $t('Error_404_h6_p') }}.
                </p>
              </div>
              <div>
                <nuxt-link class="btn btn-icon btn-icon-start btn-primary"
                  :to="{name: 'home'}">
                  <i data-cs-icon="arrow-left"></i>
                  <span>{{ $t('Back_to_Home') }}</span>
                </nuxt-link>
              </div>
            </div>
          </div>
        </div>
        <!-- Right Side End -->
      </div>
    </div>
  </div>
</template>

<script>
export default {

}
</script>
