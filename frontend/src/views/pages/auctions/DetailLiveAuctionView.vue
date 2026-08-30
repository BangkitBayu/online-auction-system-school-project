<script setup lang="js">
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import UserProfile from '@/components/UserProfile.vue'
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import moneyFormater from '@/utils/moneyFormater'
import BaseButton from '@/components/BaseButton.vue'
import AuctionServices from '@/services/AuctionServices'
import bTable from '@/components/table/b-table.vue'
import bTableHead from '@/components/table/b-table-head.vue'
import bTableData from '@/components/table/b-table-data.vue'
import dateIsoFormater from '@/utils/dateIsoFormater'

const route = useRoute()
const router = useRouter()
const auctionSvc = new AuctionServices()
const auction = ref({})
const user = JSON.parse(localStorage.getItem('user'))

let isOpenMenu = ref(false)
let isLoading = ref(false)
function openedMenu() {
  isOpenMenu.value = !isOpenMenu.value
}

const fetchAuctionById = async (id) => {
  try {
    isLoading.value = true
    const response = await auctionSvc.show(id)

    if (response.status >= 200) {
      auction.value = response.data.data
      console.log(auction.value)
    } else {
      throw response
    }
  } catch (error) {
    isLoading.value = false

    if (error.response?.status === 404) {
      alert(error.response?.message)
      router.push({ name: 'liveAuctions' })
    }
    console.log(error)
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  fetchAuctionById(route.params.id)
})
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
      <div class="page-info-container">
        <h1 class="title-page">Detail {{ auction.asset?.nama }}</h1>
        <router-link class="nav-link" :to="{ name: 'liveAuctions' }">
          < Kembali ke Daftar Lelang Berlangsung</router-link
        >
      </div>
      <div
        class="auction-wrapper"
        style="min-height: 50vh; display: flex; justify-content: center; align-items: center"
        v-if="isLoading"
      >
        <p>Sedang memuat data...</p>
      </div>
      <div class="auction-summary" v-else>
        <section class="auction-summary__section--vertical">
          <h2 class="auction-summary__heading">RINGKASAN ASSET</h2>
          <div class="auction-summary__card auction-summary__card--vertical">
            <img
              class="auction-summary__image"
              :src="auction.asset?.thumbnail_url"
              :alt="[`img-${auction.asset?.nama_barang}`]"
            />
            <div class="auction-summary__details">
              <h3 class="auction-summary__subheading">{{ auction.asset?.nama }}</h3>
              <ul class="auction-summary__lists">
                <li class="auction-summary__info">
                  ID barang: <strong>{{ auction.asset?.id }}</strong>
                </li>
                <li class="auction-summary__info">
                  Harga awal: <strong>{{ moneyFormater(auction.asset?.harga_awal) }}</strong>
                </li>
              </ul>
              <BaseButton
                @click="$router.push({ name: 'detailAuction', params: { id: auction.asset?.id } })"
              >
                <template #btn-content>Lihat detail</template></BaseButton
              >
              <hr class="line-divider" />
              <h3 class="auction-summary__subheading">Penawar Tertinggi</h3>
              <ul class="auction-summary__lists">
                <li class="auction-summary__info">
                  Nama lengkap: <strong>{{ auction.detail_highest_bidder?.nama_lengkap }}</strong>
                </li>
                <li class="auction-summary__info">
                  Kontak: <strong>{{ auction.detail_highest_bidder?.telp }}</strong>
                </li>
              </ul>
            </div>
          </div>
        </section>
        <section class="auction-summary__section--vertical">
          <h2 class="auction-summary__heading">Panel Monitoring Lelang</h2>
          <div class="auction-summary__card auction-summary__card--vertical">
            <p class="auction-summary__info">Harga Penawaran Tertinggi Saat Ini</p>
            <h3 class="auction-summary__subheading--highlighted">
              {{ moneyFormater(auction.current_bidder?.higher_price) }}
            </h3>
            <ul class="auction-summary__lists">
              <li class="auction-summary__info">
                Oleh: <strong>{{ auction.current_bidder?.by }}</strong>
              </li>
              <li class="auction-summary__info">
                Total peserta: <strong>{{ auction.current_bidder?.count_bidder }}</strong>
              </li>
            </ul>
          </div>

          <h3 class="auction-summary__subheading">Log Bids</h3>
          <div class="auction-summary__wrapper">
            <b-table>
              <template #tableHead>
                <tr>
                  <b-table-head>ID User</b-table-head>
                  <b-table-head>Username</b-table-head>
                  <b-table-head>Nominal Penawaran</b-table-head>
                  <b-table-head>Timestamp</b-table-head>
                </tr>
              </template>
              <template #tableBody>
                <tr v-if="auction.log_bids?.length === 0">
                  <td class="auction-summary__td--info" colspan="4">Data penawar kosong</td>
                </tr>
                <tr v-else v-for="item in auction.log_bids" :key="item.id_history">
                  <b-table-data>{{ item.id_user }}</b-table-data>
                  <b-table-data>{{ item.username }}</b-table-data>
                  <b-table-data>{{ moneyFormater(item.penawaran_harga) }}</b-table-data>
                  <b-table-data>{{ dateIsoFormater(item.created_at) }}</b-table-data>
                </tr>
              </template>
            </b-table>
          </div>
        </section>
      </div>
    </template>
  </BaseLayoutDashboard>
</template>

<style lang="css" scoped>
.page-info-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.title-page {
  font-size: clamp(1.5rem, 1vw, 2rem);
  margin-bottom: 1rem;
  font-weight: 600;
  color: #383838;
}

ul {
  list-style-type: none;
}

p .nav-link-page {
  text-decoration: none;
  font-size: small;
  color: #383838;
}

.title-subpoint {
  color: #383838;
}

.auction-summary {
  display: grid;
  grid-template-columns: auto 4fr;
  gap: 2rem;
  border-radius: 10px;
  width: 100%;
  place-items: start;
  padding: 2rem;
  height: auto;
}

.auction-summary__wrapper {
  width: 100%;
  overflow-x: auto;
  max-width: 1000px;
}

.auction-summary__section--vertical {
  display: flex;
  justify-content: center;
  align-items: start;
  flex-direction: column;
  background-color: #f8f8f8f8;
  padding: 2rem;
  border-radius: 1rem;
  width: 100%;
  gap: 1rem;
}

.auction-summary__section {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  place-items: center;
}

.auction-summary__heading {
  text-transform: uppercase;
  font-weight: 600;
}

.auction-summary__subheading {
  font-weight: bold;
}

.auction-summary__subheading--highlighted {
  color: var(--primary-color);
  font-size: x-large;
}

.auction-summary__card {
  width: 100%;
}

.auction-summary__card--horizontal {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1.6rem;
}

.auction-summary__card--vertical {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: start;
  gap: 0.8rem;
}

.auction-summary__image {
  width: 100%;
  max-width: 400px;
  max-height: 400px;
  height: 100%;
  border-radius: 1rem;
}

.auction-summary__details {
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: start;
  width: 100%;
}

.auction-summary__info {
  color: #5a5959;
  margin-block: 0.5rem;
}

.auction-summary__lists {
  list-style-type: none;
}

.auction-summary__td--info {
  padding: 2rem;
  text-align: center;
}

@media (max-width: 768px) {
  .auction-wrapper {
    display: flex;
    justify-content: flex-start;
    align-items: start;
    flex-direction: column;
    gap: 0.8rem;
  }
}
</style>
