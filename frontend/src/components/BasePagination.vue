<script setup lang="js">
import { computed } from 'vue'

const props = defineProps({
  pagination: {
    type: Object,
    required: true,
  },

  offset: {
    type: Number,
    default: 2, // Jumlah angka yang tampil di kiri/kanan halaman aktif
  },
})

const emit = defineEmits(['page-changed'])

// Kalkulasi range nomor halaman yang akan ditampilkan
const pagesNumber = computed(() => {
  if (!props.pagination.to) {
    return []
  }

  let from = props.pagination.current_page - props.offset
  if (from < 1) {
    from = 1
  }

  let to = from + props.offset * 2
  if (to >= props.pagination.last_page) {
    to = props.pagination.last_page
  }

  const pagesArray = []
  for (let page = from; page <= to; page++) {
    pagesArray.push(page)
  }

  return pagesArray
})

function changePage(page) {
  if (page !== props.pagination.current_page && page >= 1 && page <= props.pagination.last_page) {
    emit('page-changed', page)
  }
}
</script>

<template>
  <nav v-if="props.pagination.last_page > 1" class="pagination-container">
    <button
      :disabled="pagination.current_page === 1"
      @click="changePage(pagination.current_page - 1)"
      class="btn-nav"
    >
      &laquo; Prev
    </button>

    <button
      v-for="page in pagesNumber"
      :key="page"
      @click="changePage(page)"
      :class="['btn-page', { active: page === pagination.current_page }]"
    >
      {{ page }}
    </button>

    <button
      :disabled="pagination.current_page === pagination.last_page"
      @click="changePage(pagination.current_page + 1)"
      class="btn-nav"
    >
      Next &raquo;
    </button>
  </nav>
</template>
<style lang="css" scoped>
.pagination-container {
  display: flex;
  gap: 6px;
  align-items: center;
  justify-content: center;
  margin-block: 1rem;
  align-self: auto;
}

button {
  padding: 6px 12px;
  border: 1px solid #ccc;
  background-color: #fff;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.2s;
}

button:hover:not(:disabled) {
  background-color: #f0f0f0;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-page.active {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}
</style>
