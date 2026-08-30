<script setup lang="js">
const model = defineModel()
</script>
<template>
  <select v-model="model">
    <slot name="options"></slot>
  </select>
</template>

<style lang="css" scoped>
select {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #cccccc;
}
</style>
