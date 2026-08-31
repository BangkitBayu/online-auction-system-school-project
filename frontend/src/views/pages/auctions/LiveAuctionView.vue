<script setup lang="js">
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import UserProfile from '@/components/UserProfile.vue'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import { onMounted, ref } from 'vue'
import bTable from '@/components/table/b-table.vue'
import bTableHead from '@/components/table/b-table-head.vue'
import bTableData from '@/components/table/b-table-data.vue'
import AuctionServices from '@/services/AuctionServices'
import BaseButton from '@/components/BaseButton.vue'
import moneyFormater from '@/utils/moneyFormater'
import IconEyeOn from '@/components/icons/IconEyeOn.vue'

const auctionSvc = new AuctionServices()

let isOpenMenu = ref(true)
let isLoading = ref(false)
let auctions = ref([])

function openedMenu() {
  isOpenMenu.value = !isOpenMenu.value
}

const fetchAllAuction = async () => {
  isLoading.value = true
  try {
    const response = await auctionSvc.index()
    if (response.status == 200) {
      auctions.value = response.data?.data
    } else {
      throw response
    }
  } catch (error) {
    isLoading.value = false
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  fetchAllAuction()
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
        <h3 class="title-page">Lelang Berlangsung</h3>
      </div>
      <div class="content-wrapper">
        <div class="table-wrapper">
          <b-table>
            <template #tableHead>
              <tr>
                <b-table-head>ID</b-table-head>
                <b-table-head>Nama Asset Lelang</b-table-head>
                <b-table-head>Tanggal Mulai</b-table-head>
                <b-table-head>Tanggal Akhir</b-table-head>
                <b-table-head>Status</b-table-head>
                <b-table-head>Total Peserta</b-table-head>
                <b-table-head>Harga Tertinggi</b-table-head>
                <b-table-head>Aksi</b-table-head>
              </tr>
            </template>
            <template #tableBody>
              <tr v-if="isLoading">
                <td colspan="8" class="info">Sedang memuat data...</td>
              </tr>
              <tr v-else-if="auctions.length === 0">
                <td colspan="8" class="info">Tidak ada lelang berlangsung</td>
              </tr>

              <tr v-for="(item, index) in auctions" :key="index" v-else>
                <b-table-data>{{ item.id_lelang }}</b-table-data>
                <b-table-data>{{ item.barang?.nama_barang }}</b-table-data>
                <b-table-data>{{ item.tgl_mulai_lelang ?? 'Belum ditetapkan' }}</b-table-data>
                <b-table-data>{{ item.tgl_akhir_lelang ?? 'Belum ditetapkan' }}</b-table-data>
                <b-table-data>
                  <span class="block opened-status" v-if="item.status == 'dibuka'">
                    {{ item.status }}
                  </span>
                  <span v-else class="block closed-status">
                    {{ item.status }}
                  </span>
                </b-table-data>
                <b-table-data>
                  <span class="block total-person"
                    >{{ item.history_lelangs_count }} Orang</span
                  ></b-table-data
                >
                <b-table-data>
                  <span class="block highest-price">
                    {{ moneyFormater(item.history_lelangs_max_penawaran_harga) }}
                  </span>
                </b-table-data>
                <b-table-data>
                  <div class="action-container">
                    <BaseButton
                      @click="
                        $router.push({ name: 'detailLiveAuction', params: { id: item.id_lelang } })
                      "
                      class="action-btn"
                      :variant="'info'"
                    >
                      <template #btn-content>
                        <IconEyeOn :theme="'secondary'" class="icon"></IconEyeOn>
                      </template>
                    </BaseButton>
                  </div>
                </b-table-data>
              </tr>
            </template>
          </b-table>
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

.content-wrapper {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: start;
  background-color: #f8f8f8f8;
  border-radius: 10px;
  padding: 1rem;
  width: 100%;
  gap: 2rem;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

.table-wrapper .info {
padding: 2rem;
  text-align: center !important;
}

.table-wrapper table th,
.table-wrapper table td {
  vertical-align: middle;
  text-align: left;
}

.table-wrapper table tbody tr:nth-child(even) {
  background-color: #f1f1f1;
}

.block {
  padding: 0.4rem 0.6rem;
  border-radius: 0.5rem;
  text-align: center;
  font-size: small;
  font-weight: 600;
}

.closed-status {
  border: 1px solid red;
  background-color: #e88787;
  color: red;
}

.opened-status {
  border: 1px solid green;
  background-color: #98f098d6;
  color: green;
}

.total-person {
  border: 1px solid #0080ff;
  background-color: #7fbefdba;
  color: #0080ff;
}

.highest-price {
  border: 1px solid #ffb700;
  background-color: #f3f341b2;
  color: #ffb700;
}

.action-container {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 0.5rem;
}

.action-btn {
  max-width: fit-content;
  max-height: fit-content;
}

.utility-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  width: 100%;
}

.scheduler-auction-btn {
  max-width: 15% !important;
}

.scheduler-auction-btn p {
  white-space: nowrap;
}

.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.row--end {
  display: flex;
  justify-content: end;
  gap: 5px;
}

.modal-wrapper__form {
  width: 100%;
  display: flex;
  justify-content: start;
  flex-direction: column;
  align-items: center;
  gap: 0.8rem;
}

.modal-wrapper__form-group {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: start;
  gap: 0.8rem;
  width: 100%;
}

.modal-wrapper__heading {
  color: var(--text-heading-color);
}

.modal-wrapper__description {
  color: var(--text-description-color);
  text-align: center;
}

.modal-wrapper__button {
  padding: 8px;
  border: 1px solid #c5c5c5c5;
  color: var(--text-heading-color);
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: transparent;
  width: max-content !important;
}

.modal-wrapper__button--secondary {
  width: max-content;
}

.modal-wrapper__button:hover {
  background-color: #c3c3c3c5;
}

.btn-disabled {
  background-color: gray;
}

.btn-disabled:hover {
  opacity: 100%;
}
</style>
