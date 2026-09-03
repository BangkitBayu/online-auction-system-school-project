<script setup>
import BaseButton from '@/components/BaseButton.vue'
import BaseLayout from '../templates/BaseLayout.vue'
import BaseCard from '@/components/BaseCard.vue'
import BSearchBar from '@/components/searchBar/b-search-bar.vue'
import HomeServices from '@/services/HomeServices.js'
import { computed, onMounted, onUnmounted, ref } from 'vue'

const user = JSON.parse(localStorage.getItem('user'))

const homeSvc = new HomeServices()
let auctions = ref([])
let isLoading = ref(false)
let auctionKeyword = ref('')

const auctionSearched = computed(() => {
  if (auctionKeyword.value)
    return auctions.value.filter((auction) => {
      return auction.barang?.nama.toLowerCase().includes(auctionKeyword.value.toLowerCase())
    })
  return auctions.value
})

let timer = ref(null)

const fetchAuctions = async () => {
  try {
    isLoading.value = true
    const response = await homeSvc.index()

    if (response?.status === 200) {
      auctions.value = response.data?.data
    } else {
      throw response
    }
  } catch (error) {
    isLoading.value = false
    console.error(error?.response)
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  fetchAuctions()

  timer.value = setInterval(() => {
    fetchAuctions()
  }, 30 * 1000)
})

onUnmounted(() => {
  if (timer.value) clearInterval(timer.value)
})
</script>

<template>
  <BaseLayout>
    <template #main>
      <section id="main-content-container">
        <div class="row">
          <BSearchBar v-model="auctionKeyword"></BSearchBar>
        </div>
        <h3>Lihat Asset yang Sedang di Lelang</h3>
        <div class="product-wrapper">
          <BaseCard v-for="item in auctionSearched" :key="item.id" :auction="item">
            <BaseButton
              @click="$router.push({ name: 'publicAuctionDetails', params: { id: item.id } })"
              v-if="!user || user?.role === 'masyarakat'"
              ><template #btn-content>Detail</template></BaseButton
            >
          </BaseCard>
        </div>
      </section>
    </template>
  </BaseLayout>
</template>

<style lang="css" scoped>
section {
  padding: 1rem;
  overflow: hidden;
}

#main-content-container {
  min-height: 100vh;
  display: flex;
  justify-content: flex-start;
  flex-direction: column;
  align-items: center;
  margin-top: 5rem;
}

.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  gap: 5px;
}

form input {
  width: 80%;
}

form button {
  width: 20% !important;
}

.product-wrapper {
  width: 100vw;
  padding-inline: 1.4rem;
  margin-block: 0.5rem;
  display: grid;
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  align-items: center;
  gap: 0.6rem;
}

h3 {
  font-weight: 600;
  color: #0bbd0b;
  align-self: self-start;
  font-size: clamp(26px, 1vw, 42px);
  margin-top: 2rem;
  margin-bottom: 0.5rem;
}

#nav-detail-btn {
  margin-block: 1rem;
  max-width: max-content !important;
  align-self: center;
}

.category-wrapper {
  width: 100%;
  max-width: 1000px;
  overflow-x: auto;
  display: flex;
  justify-content: flex-start;
  align-self: self-start;
  align-items: center;
  gap: 0.4rem;
}

.category-wrapper .nav-link {
  padding: 0.8rem;
  text-align: center;
  text-decoration: none;
  border: 1px solid #cacaca;
  border-radius: 5px;
  color: #525252;
  white-space: nowrap;
  font-weight: 600;
  margin-bottom: 0.4rem;
}

@media (max-width: 768px) {
  .product-wrapper {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
