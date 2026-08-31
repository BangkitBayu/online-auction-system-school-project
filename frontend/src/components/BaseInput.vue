<script setup lang="js">
const props = defineProps({
  id: {
    required: true,
    type: String,
  },

  type: {
    required: true,
    type: String,
    validator: (value) =>
      [
        'hidden',
        'email',
        'phone',
        'text',
        'number',
        'date',
        'password',
        'hidden',
        'search',
        'tel',
        'datetime-local',
        'file',
      ].includes(value),
  },

  placeholder: {
    type: String,
  },

  isRequired: {
    type: Boolean,
    default: true,
  },
})

const model = defineModel()
</script>

<template>
  <input
    :id="props.id"
    :type="props.type"
    :placeholder="props.placeholder"
    :required="props.isRequired"
    v-model="model"
  />
</template>

<style scoped>
input {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #cccccc;
}
</style>
