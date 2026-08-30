<script setup lang="js">
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import UserProfile from '@/components/UserProfile.vue'
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import ItemServices from '@/services/ItemServices'
import moneyFormater from '@/utils/moneyFormater'
import BaseButton from '@/components/BaseButton.vue'
import IconEdit from '@/components/icons/IconEdit.vue'
import IconDelete from '@/components/icons/IconDelete.vue'
import bTab from '@/components/tab/b-tab.vue'
import dateIsoFormater from '@/utils/dateIsoFormater'
import BaseModal from '@/components/BaseModal.vue'

const route = useRoute()
const router = useRouter()
const itemSvc = new ItemServices()
const auction = ref({})
const user = JSON.parse(localStorage.getItem('user'))

let isOpenMenu = ref(false)
let isLoading = ref(false)
function openedMenu() {
  isOpenMenu.value = !isOpenMenu.value
}

const getAuctionById = async (id) => {
  try {
    isLoading.value = true
    const response = await itemSvc.show(id)

    if (response.status >= 200) {
      auction.value = response.data.data
      console.log(auction.value)
    } else {
      throw response
    }
  } catch (error) {
    isLoading.value = false
    console.log(error)
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  getAuctionById(route.params.id)
  console.log(auction.value)
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
        <h3 class="title-page">Detail {{ auction.nama_barang }}</h3>
        <router-link class="nav-link" :to="{ name: 'assetAuctions' }">
          < Kembali ke Daftar Asset</router-link
        >
      </div>
      <div
        class="auction-wrapper"
        style="min-height: 50vh; display: flex; justify-content: center; align-items: center"
        v-if="isLoading"
      >
        <p>Sedang memuat data...</p>
      </div>
      <div class="auction-wrapper" v-else>
        <div class="c-img-container">
          <img :src="auction.thumbnail" :alt="auction.nama_barang" />
        </div>
        <div class="c-body-container">
          <h2 class="auction-title" v-text="auction.nama_barang"></h2>

          <b-tab :default-tab="'detail'" :tabs="[{ name: 'detail', label: 'Detail' }]">
            <template #detail>
              <ul class="auction-list">
                <li>
                  <span class="point">Tanggal:</span>
                  <span class="main" v-text="dateIsoFormater(auction.tgl)"></span>
                </li>
                <li>
                  <span class="point">Kategori lelang:</span>
                  <span class="main" v-text="auction.kategori_barang?.nama_kategori_barang"></span>
                </li>
                <li>
                  <span class="point">Harga awal:</span>
                  <span class="main" v-text="moneyFormater(auction.harga_awal)"></span>
                </li>
              </ul>
              <section style="margin-top: 0.5rem">
                <h4 class="point">Deskripsi:</h4>
                <p class="auction-description">{{ auction.deskripsi_barang }}</p>
              </section>
            </template>
          </b-tab>
          <hr class="line-divider" />
          <p>
            Ditambahkan oleh
            <span style="font-weight: bold" v-text="auction.petugas?.nama_petugas"></span>
          </p>

          <section class="auction-cta-container" v-show="auction.lelang?.status == 'dibuka'">
            <BaseButton v-if="user.role === 'petugas'" class="action-btn"
              ><template #btn-content><IconEdit></IconEdit></template
            ></BaseButton>
            <BaseButton v-if="user.role === 'petugas'" class="action-btn" :variant="'danger'"
              ><template #btn-content><IconDelete></IconDelete></template
            ></BaseButton>
          </section>
          <BaseButton
            @click="
              $router.push({ name: 'detailLiveAuction', params: { id: auction.lelang?.id_lelang } })
            "
            :variant="auction.lelang?.status == 'dibuka' ? 'primary' : 'disabled'"
            id="cta-btn-1"
            :disabled="auction.lelang?.status !== 'dibuka'"
            ><template #btn-content
              ><span
                v-text="
                  auction.lelang?.status == 'dibuka'
                    ? 'Lelang sedang berlangsung'
                    : 'Lelang belum berlangsung'
                "
              ></span></template
          ></BaseButton>
        </div>
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

p .nav-link-page {
  text-decoration: none;
  font-size: small;
  color: #383838;
}

.auction-wrapper {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  border-radius: 10px;
  width: 100%;
  background-color: #f8f8f8f8;
}

.auction-wrapper .c-img-container img {
  width: 100%;
  min-height: 500px;
  border-top-left-radius: 8px;
  border-bottom-left-radius: 8px;
}

.auction-wrapper .c-body-container {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: start;
  gap: 1rem;
}

.auction-title {
  color: #383838;
  font-size: larger;
}

.auction-description {
  font-size: medium;
  color: #525252;
}

.auction-list {
  list-style-type: none;
}

.auction-list li .point {
  color: #5a5a5a;
}

.auction-list li .main {
  margin-inline-start: 0.3rem;
}

.c-body-container section {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: start;
  gap: 0.4rem;
}

.c-body-container section h4,
p {
  color: #383838;
}

.c-body-container section p {
  color: #5a5a5a;
}

section.auction-cta-container {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
  width: 100%;
}

.action-btn {
  max-width: fit-content;
  max-height: fit-content;
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
