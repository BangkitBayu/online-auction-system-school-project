<script setup lang="js">
const props = defineProps({
  row: {
    type: String,
    default: '30',
  },
  col: {
    type: String,
    default: '30',
  },
})

const model = defineModel()
</script>

<template>
  <textarea v-model="model" :cols="props.col" :rows="props.row"></textarea>
</template>

<style lang="css" scoped>
textarea {
  resize: none;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #cccccc;
  width: 100%;
}
</style>
