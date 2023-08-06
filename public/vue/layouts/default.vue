<template>
  <nuxt class="h-100" />
</template>
<style>
body, #__nuxt, #__layout {height: 100%;}
</style>
<script>
export default {
  props: ['error'],
  name: 'Error',
  mounted() {
    window.jQueryInit();
  }
}
</script>
