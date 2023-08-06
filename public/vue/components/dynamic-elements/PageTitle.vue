<template>
  <div class="form-group pageTitle">
    <textarea v-if="editing" v-model="title" class="border-0 form-control"></textarea>
    <label v-else class="text-bold" v-html="title.replace(/\n/g, '<br>')"></label>
  </div>
</template>

<script>
export default {
  name: "PageTitle",
  data() {
    return {
      title: 'Page Title ..',
      editing: false
    }
  },
}
</script>

<style scoped>
.pageTitle {
  position: absolute;
  top: 30px;
  text-align: center;
  width: 120px;
  white-space: pre-wrap;
}

.pageTitle textarea, .pageTitle label {
  background: rgba(0, 0, 0, 0);
  text-align: center;
}
</style>
