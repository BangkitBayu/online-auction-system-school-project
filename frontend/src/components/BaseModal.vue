<script setup lang="js">
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
})
</script>
<template>
  <div class="modal-overlay" v-show="props.isOpen">
    <div class="modal-wrapper">
      <section class="modal-header">
        <slot name="modalHeader"></slot>
      </section>
      <section class="modal-body">
        <slot name="modalBody"></slot>
      </section>
      <section class="modal-footer">
        <slot name="modalFooter"></slot>
      </section>
    </div>
  </div>
</template>

<style lang="css" scoped>
.modal-overlay {
  position: fixed;
  background-color: #000000b7;
  z-index: 10;
  height: 100vh;
  width: 100vw;
  top: 0;
  left: 0;

  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-wrapper {
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: white;
  border-radius: 0.5rem;
  width: 100%;
  max-width: 400px;
}

.modal-header {
  padding: 1rem;
  border-bottom: 1px solid #c7c7c7;
  width: 100%;
}

.modal-body {
  width: 100%;
  padding: 2rem;
}

.modal-footer {
  padding: 1rem;
  border-top: 1px solid #c7c7c7;
  width: 100%;
}
</style>
