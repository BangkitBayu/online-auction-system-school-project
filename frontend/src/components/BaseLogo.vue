<script setup lang="js">
const props = defineProps({
  variants: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary'].includes(value),
  },
})
</script>

<template>
  <router-link :class="['logo', `logo-${props.variants}`]" :to="{ name: 'home' }">
    <h3>LelangMudah</h3>
  </router-link>
</template>
<style lang="css" scoped>
.logo {
  text-decoration: none;
  font-size: clamp(26px, 2vw, 42px);
}

.logo-primary h3 {
  color: var(--primary-color);
}
.logo-secondary h3 {
  color: white;
}
</style>
