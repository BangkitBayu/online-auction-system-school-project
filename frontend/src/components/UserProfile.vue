<script setup lang="js">
const user = JSON.parse(localStorage.getItem('user'))

const props = defineProps({
  variants: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'primary-bordered'].includes(value),
  },
})
</script>
<template>
  <router-link :class="[`user-profile-${props.variants}`]" :to="{ name: 'dashboardView' }">
    <p class="username" v-text="user.username"></p>
  </router-link>
</template>

<style lang="css" scoped>
a {
  text-decoration: none;
  padding: 0.6rem;
  border: 1px solid #e5e5e5;
  border-radius: 15px;
}

.username {
  font-size: small;
  font-weight: 500;
}

.user-profile-primary {
  background-color: var(--primary-color);
  color: white;
  transition: transform ease-in-out 0.3s;
}

.user-profile-primary:hover {
  transform: translateY(-2px);
}
</style>
