<script setup langs="js">
import IconClose from './icons/IconClose.vue'

const props = defineProps({
  type: {
    type: String,
    default: 'success',
  },
  message: {
    type: String,
    default: 'This is alert component',
  },

  isShow: {
    type: Boolean,
    default: true,
  },
})


const emit = defineEmits(['close'])
</script>
<template>
  <div :class="['alert-wrapper', `alert-${props.type}`]" v-if="props.isShow">
    <span class="alert-message">{{ props.message }}</span>
    <button class="alert-button" @click="emit('close')">
      <IconClose></IconClose>
    </button>
  </div>
</template>

<style lang="css" scoped>
.alert-wrapper {
  position: fixed;
  top: 15%;
  left: 0;
  right: 0;
  margin-inline: auto;
  width: fit-content;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  gap: 3rem;
  border-radius: 0.5rem;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
}

.alert-info {
  background-color: #d1ecf1;
  border: 1px solid #bee5eb;
}

.alert-button {
  background: none;
  border: none;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0.2rem;
}

.alert-message {
  font-size: 1rem;
  color: #383838;
  font-weight: bold;
}
</style>
