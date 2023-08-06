<template>
  <div id="titlebar" class="gradient">
    <div class="container">
      <div class="row">
        <div class="col-md-12">

          <h2>{{ title }}</h2>

          <!-- Breadcrumbs -->
          <nav id="breadcrumbs" class="dark">
            <ul>
              <li v-for="(link, i) in links" class="text-capitalize">
                <nuxt-link v-if="i < links.length-1" :to="{name: link}">{{ link }}</nuxt-link>
                <a v-else>{{ link }}</a>
              </li>
            </ul>
          </nav>

        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ['title', 'links'],
}
</script>
