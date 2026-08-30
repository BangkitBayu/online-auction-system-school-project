<script setup lang="js">
import { ref } from 'vue'

const props = defineProps({
  dropdownValue: {
    type: String,
    required: true,
  },

  dropdownVariants: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary'].includes(value),
  },
})

let isExpanded = ref(false)

const expandDropdown = () => {
  isExpanded.value = !isExpanded.value
}
</script>
<template>
  <div :class="['wrapper', `dropdown-${props.dropdownVariants}`]">
    <div class="container">
      <p v-text="props.dropdownValue" :class="[`dropdown-${props.dropdownVariants}`]"></p>
      <button type="button" :class="[`dropdown-${props.dropdownVariants}`]" @click="expandDropdown">
        <svg
          :class="[`dropdown-${props.dropdownVariants}`, isExpanded ? 'active' : '']"
          class="arrow"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 12 24"
        >
          <path d="M0 0h12v24H0z" fill="none" />
          <path
            fill-rule="evenodd"
            d="M10.157 12.711L4.5 18.368l-1.414-1.414l4.95-4.95l-4.95-4.95L4.5 5.64l5.657 5.657a1 1 0 0 1 0 1.414"
          />
        </svg>
      </button>
    </div>

    <div
      :class="[
        'dropdown-items-container',
        `dropdown-${props.dropdownVariants}`,
        isExpanded ? 'expanded' : 'collapsed',
      ]"
    >
      <slot name="dropdown-items"></slot>
    </div>
  </div>
</template>

<style lang="css" scoped>
.wrapper {
  display: flex;
  justify-content: start;
  align-items: start;
  flex-direction: column;
  width: 100%;
  transition: transform ease-in-out 3s;
  margin-block: 0.5rem;
}
.container {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.arrow {
  width: 20px;
  height: 20px;
}

button {
  display: flex;
  justify-content: center;
  align-items: center;
}

.dropdown-primary {
  background-color: transparent;
}

button {
  background-color: transparent;
  border: none;
}

.arrow {
  transform: rotate(90deg);
}
.arrow.active {
  transform: rotate(-90deg);
}

.dropdown-primary .arrow {
  fill: white;
}

.dropdown-primary p {
  color: white;
  font-weight: 600;
}

.dropdown-items-container {
  display: flex;
  justify-content: start;
  align-items: start;
  flex-direction: column;
  margin-top: 0.6rem;
  gap: 0.4rem;
  width: 100%;
}

.expanded {
  display: flex;
}

.collapsed {
  display: none;
}

.dropdown-secondary p {
  color: var(--text-heading-color);
}
</style>
