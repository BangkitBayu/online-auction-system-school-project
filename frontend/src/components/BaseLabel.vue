<script setup lang="js">
const props = defineProps({
  for: {
    required: true,
    type: String,
  },
  value: {
    required: true,
    type: String,
  },
})
</script>

<template>
  <label :for="props.for" v-text="props.value"></label>
</template>

<style scoped>
label {
  font-weight: 600;
  color: #1e1e1e;
  font-size: medium;
}

label::after {
  content: '*';
  color: red;
}
</style>
