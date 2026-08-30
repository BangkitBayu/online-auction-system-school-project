<script setup lang="js">
import BaseLayoutDashboard from '../templates/BaseLayoutDashboard.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import UserProfile from '@/components/UserProfile.vue'
import BaseAside from '../administrator/components/BaseAside.vue'
import DashboardServices from '@/services/DashboardServices.js'

let isOpenMenu = ref(true)
const dashboardSvc = new DashboardServices()
function openedMenu() {
  isOpenMenu.value = !isOpenMenu.value
}

let dashboardData = ref({})
let timer = ref(null)

let nowTime = ref(new Date())

const estimateTime = (endTime) => {
  let result = new Date(endTime) - nowTime.value

  if (result <= 0) 'Berakhir'

  let hour = Math.floor(result / 3600000)
  let minute = Math.floor((result / 60000) % 60)

  return `Sisa ${hour} jam ${minute} menit lagi`
}

const fetchUserData = async () => {
  try {
    const response = await dashboardSvc.index()

    if (response.status === 200) {
      dashboardData.value = response.data?.data
    }
  } catch (error) {
    console.error(error)
  }
}

onMounted(async () => {
  fetchUserData()

  timer.value = setInterval(() => {
    nowTime.value = new Date()
    fetchUserData()
  }, 60000)
})

onUnmounted(() => {
  clearInterval(timer)
})

const user = JSON.parse(localStorage.getItem('user'))
</script>

<template>
  <BaseLayoutDashboard>
    <template #aside>
      <BaseAside :is-open="isOpenMenu"></BaseAside>
    </template>
    <template #header>
      <DashboardHeader>
        <HamburgerToggle @click="openedMenu"></HamburgerToggle>
        <UserProfile></UserProfile>
      </DashboardHeader>
    </template>
    <template #main>
      <h1 class="text__heading">Dashboard</h1>
      <section v-if="user.role === 'masyarakat'" class="user-dashboard-wrapper">
        <div class="row--3">
          <div class="dashboard-block-summary">
            <h3>Lelang diikuti</h3>
            <h1 v-text="dashboardData.total_lelang_diikuti"></h1>
          </div>
          <div class="dashboard-block-summary">
            <h3>Sedang unggul</h3>
            <h1 v-text="dashboardData.total_sedang_unggul"></h1>
          </div>
          <div class="dashboard-block-summary">
            <h3>Menang</h3>
            <h1 v-text="dashboardData.total_menang"></h1>
          </div>
        </div>
        <h2 class="text__heading">Segera Berakhir</h2>
        <div class="dashboard-items">
          <div class="empty-state" v-if="dashboardData.lelang_segera_berakhir?.length === 0">
            <p>Anda belum mengikuti lelang</p>
          </div>

          <router-link
            to=""
            v-else
            class="item--horizontal"
            v-for="item in dashboardData.lelang_segera_berakhir"
            :key="item.id_lelang"
          >
            <img
              class="item__img"
              :src="item.thumbnail_url"
              alt=""
              width="80"
              height="80"
              loading="lazy"
            />
            <div class="item--vertical">
              <span class="item__label" v-text="item.nama_lot"></span>
              <p class="item__value">{{ estimateTime(item.tgl_selesai) }}</p>
            </div>
          </router-link>
        </div>
      </section>
    </template>
  </BaseLayoutDashboard>
</template>

<style lang="css" scoped>
header .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2rem;
  background: white;
}

.text__heading {
  color: var(--text-heading-color);
}

.row--3 {
  display: grid;
  width: 100%;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-block: 1rem;
}

.user-dashboard-wrapper {
  display: flex;
  flex-direction: column;
  align-items: start;
  justify-content: space-evenly;
}

.dashboard-block-summary {
  border-radius: 1rem;
  background-color: #f8f8f8;
  width: 100%;
  min-height: 150px;
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  flex-direction: column;
  align-items: start;
  border: 1px solid #e0e0e0;
}

.dashboard-items {
  margin-block: 1rem;
  width: 100%;
  border-radius: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  flex-direction: column;
}

.empty-state {
  width: 100%;
  border-radius: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 20vh;
  border: 1px solid #e0e0e0;
  background-color: #f8f8f8;
}

.item--horizontal {
  display: flex;
  gap: 0.8rem;
  width: 100%;
  border-radius: 1rem;
  text-decoration: none;
  border: 1px solid #e0e0e0;
  background-color: #f8f8f8;
}

.item__img {
  border-top-left-radius: 1rem;
  border-bottom-left-radius: 1rem;
  object-fit: cover;
}

.item--vertical {
  padding: 0.8rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.item__label {
  font-weight: 600;
  color: black;
}

.item__value {
  color: var(--text-description-color);
  font-weight: 400;
}
</style>
