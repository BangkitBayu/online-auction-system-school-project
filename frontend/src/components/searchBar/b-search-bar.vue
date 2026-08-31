<script setup lang="js">
const props = defineProps({
  searchPlaceholder: {
    type: String,
    default: 'Search here',
  },
})

const emit = defineEmits(['handle-search'])

const model = defineModel()
</script>
<template>
  <div class="wrapper">
    <form>
      <div class="form-group">
        <svg
          class="search-icon"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
        >
          <path
            d="M9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l5.6 5.6q.275.275.275.7t-.275.7t-.7.275t-.7-.275l-5.6-5.6q-.75.6-1.725.95T9.5 16m0-2q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14"
          />
        </svg>
        <input type="search" :placeholder="props.searchPlaceholder" v-model="model" required />
      </div>

      <button type="button" @click="$emit('handle-search')" class="search-btn">Cari</button>
    </form>
  </div>
</template>

<style lang="css" scoped>
.wrapper {
  width: 100%;
}

form {
  display: flex;
  justify-content: start;
  align-items: center;
  widows: 100%;
  flex-wrap: nowrap;
  gap: 0.4rem;
}

.form-group {
  display: flex;
  justify-content: center;
  align-items: center;
  padding-inline: 0.8rem;
  border: 1px solid #e6e6e6;
  border-radius: 5px;
  width: 100%;
  background-color: white;
}

input {
  border: none;
  background-color: transparent;
  padding: 0.8rem 0.4rem;
  outline: none;
  width: 100%;
}

.search-icon {
  width: 16px;
  height: 16px;
  margin-inline-end: 0.2rem;
  fill: #2d2d2d;
}

.search-btn {
  padding: 0.8rem;
  font-size: small;
  border: none;
  background-color: var(--primary-color);
  border-radius: 5px;
  color: white;
}

.search-btn:hover {
  opacity: 80%;
}
</style>
