<script setup lang="js">
import { ref } from 'vue'

const props = defineProps({
  tabs: {
    required: true,
    type: Array,
  },

  defaultTab: {
    required: true,
    type: String,
  },
})

let activeTab = ref(props.defaultTab || props.tabs[0]?.name)

const clickedTab = (tabName) => {
  activeTab.value = tabName
}
</script>

<template>
  <div class="tab-wrapper">
    <div class="tab-header-container">
      <button type="button"
        v-for="tab in props.tabs"
        :key="tab.name"
        :value="tab.label"
        :class="['default-tab-btn', { active: activeTab === tab.name }]"
        @click="clickedTab(tab.name)"
      >
        {{ tab.label }}
      </button>
    </div>
    <div class="tab-content-container">
      <div v-for="tab in tabs" :key="tab.name" v-show="activeTab === tab.name">
        <slot :name="tab.name"></slot>
      </div>
    </div>
  </div>
</template>

<style lang="css" scoped>
.default-tab-btn {
  background-color: transparent;
  border: none;
  outline: none;
  padding: 0.8rem;
  font-weight: 600;
  font-size: medium;
}

.active {
  color: var(--primary-color);
  border-block-end: 2px solid var(--primary-color);
  border-radius: 2px;
}

.tab-wrapper {
  width: 100%;
}

.tab-header-container {
  display: flex;
  justify-content: start;
  align-items: center;
  border-block: 1px solid #e5e5e5;
  width: 100%;
}

.tab-content-container {
  margin-block: 1rem;
}
</style>
