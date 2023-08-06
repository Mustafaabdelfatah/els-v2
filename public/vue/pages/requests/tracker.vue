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
                  {{ $t("Tracks_Request") }}
                </h1>
                <nav
                  class="breadcrumb-container d-inline-block"
                  aria-label="breadcrumb"
                >
                  <ul class="breadcrumb pt-0">
                    <li class="breadcrumb-item">
                      <nuxt-link :to="{ name: 'home' }">{{
                          $t("Home")
                        }}</nuxt-link>
                    </li>
                  </ul>
                </nav>
              </div>
              <!-- Title End -->

              <!-- Top Buttons Start -->
              <!-- end top Buttons Start -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  name: "index",
  layout: "admin",
  middleware: ["auth", "admin"],
  head() {
    return {
      title: "Request Reviews"
    };
  },
}
</script>
