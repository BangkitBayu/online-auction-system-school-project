<script setup lang="js">
import BaseFooter from '@/components/BaseFooter.vue'
import BaseHeader from '@/components/BaseHeader.vue'
</script>

<template>
  <div>
    <BaseHeader></BaseHeader>
    <main>
      <slot name="main"></slot>
    </main>
    <BaseFooter></BaseFooter>
  </div>
</template>

<style scoped>
</style>
