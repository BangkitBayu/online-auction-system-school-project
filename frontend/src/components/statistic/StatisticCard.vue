<script setup lang="js">
const props = defineProps({
  label: {
    type: String,
    default: 'lorem ipsum',
  },

  value: {
    type: String,
    default: '0',
  },

  description: {
    type: String,
    default: 'lorem ipsum dolor ngetot',
  },

  variants: {
    type: String,
    default: 'blue',
    validator: (value) => ['blue', 'red', 'green', 'yellow'].includes(value),
  },
})
</script>

<template>
  <div :class="['wrapper', `theme-${props.variants}`]">
    <label v-text="props.label"></label>
    <h3 v-text="props.value"></h3>
    <p v-text="props.description"></p>
  </div>
</template>

<style lang="css" scoped>
.wrapper {
  border-radius: 10px;
  padding: 0.8rem;
  width: 100%;
  min-height: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  text-align: center;
  color: white;
}

.wrapper label {
  margin-bottom: 0.5rem;
  font-size: medium;
  font-weight: 500;
  font-size: clamp(1rem, 1vw, 2rem);
}

.wrapper h3 {
  font-weight: 700;
  font-size: clamp(2rem, 1vw + 2rem, 4rem);
}

.wrapper p {
  font-size: small;
  margin-top: 1rem;
  font-weight: 400;
  font-size: clamp(1rem, 1vw, 2rem);
}

.theme-blue {
  background-color: #1662e5;
}
.theme-yellow {
  background-color: #eccb0c;
}
.theme-red {
  background-color: #cc3309;
}
</style>
