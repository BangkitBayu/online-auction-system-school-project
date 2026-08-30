<script setup lang="js">
const props = defineProps({
  variant: {
    default: 'primary',
    validator: (value) => ['primary', 'primary-bordered', 'danger', 'info' , 'warn'].includes(value),
  },
})
</script>

<template>
  <button :class="['btn', `btn-${props.variant}`]">
    <slot name="btn-content"></slot>
  </button>
</template>

<style scoped>
.btn {
  font-weight: 600;
  width: 100%;
  padding: 0.8rem;
  border-radius: 5px;
  border: none;
}

.btn p {
  font-weight: 400;
}

.btn-primary {
  background-color: #0bbd0b;
  color: white;
  transition: all ease-in-out 0.3s;
}

.btn-primary:hover {
  opacity: 80%;
}

.btn-primary-bordered {
  border: 1px solid #0bbd0b;
  color: #0bbd0b;
  background: transparent;
  transition: all ease-in-out 0.3s;
}

.btn-primary-bordered:hover {
  background-color: #0bbd0b;
  color: white;
}

.btn-danger {
  background-color: #e60909;
  color: white;
}

.btn-danger:hover {
  opacity: 80%;
}
.btn-info {
  background-color: #095ee6;
  color: white;
}

.btn-info:hover {
  opacity: 80%;
}
.btn-warn {
  background-color: #e6d409;
  color: white;
}

.btn-warn:hover {
  opacity: 80%;
}

.btn-disabled {
  background-color: gray;
  color: white;
}
</style>
