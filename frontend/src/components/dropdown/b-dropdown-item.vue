<script setup lang="js">
import { useRoute } from 'vue-router'

const route = useRoute()

const isActiveRoute = (routeName) => {
  return route.name == routeName
}

const props = defineProps({
  target: {
    type: String,
    default: 'adminDashboard',
  },

  value: {
    type: String,
    required: true,
  },

  dropdownLinkVariants: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary'].includes(value),
  },
})
</script>

<template>
  <router-link
    :class="[
      'dropdown-link',
      `dropdown-link-${props.dropdownLinkVariants}`,
      isActiveRoute(props.target) ? 'active' : '',
    ]"
    :to="{ name: props.target }"
  >
    {{ props.value }}
  </router-link>
</template>

<style lang="css" scoped>
.dropdown-link {
  width: 100%;
  text-decoration: none;
  color: white;
  padding: 0.6em;
  font-size: small;
}

.dropdown-link-primary:hover {
  background-color: #10ed10a7;
  border-radius: 8px;
}

.dropdown-link-primary.active {
  background-color: #10ed10a7;
  border-radius: 8px;
}
</style>
