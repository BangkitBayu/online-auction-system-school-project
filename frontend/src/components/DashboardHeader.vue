<script setup lang="js"></script>

<template>
  <div class="wrapper">
    <slot></slot>
  </div>
</template>

<style scoped>
.wrapper {
  display: flex;
  width: auto;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  box-sizing: border-box;
  gap: 1rem;
}

@media (max-width: 768px) {
  .wrapper {
    padding-inline: 2rem;
  }
}
</style>
