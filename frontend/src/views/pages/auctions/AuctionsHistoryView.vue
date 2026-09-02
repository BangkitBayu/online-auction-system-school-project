<script setup lang="js">
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import { computed, onMounted, reactive, ref } from 'vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import UserProfile from '@/components/UserProfile.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import BTable from '@/components/table/b-table.vue'
import bTableHead from '@/components/table/b-table-head.vue'
import bTableData from '@/components/table/b-table-data.vue'
import dateIsoFormater from '@/utils/dateIsoFormater'
import BaseSelect from '@/components/BaseSelect.vue'
import AuctionServices from '@/services/AuctionServices'

const auctionSvc = new AuctionServices()

const user = JSON.parse(localStorage.getItem('user'))

let isLoading = ref(false)

let isOpenMenu = ref(true)

const fetchHistories = async () => {
  try {
    isLoading.value = true
    const response = await auctionSvc.getHistories()

    if (response.status === 200) {
      histories.value = response.data.data
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

let currentFilteredHistories = ref('tidak-ada')
let histories = ref([])

const filteredHistories = computed(() => {
  if (currentFilteredHistories.value === 'menang') {
    return histories.value.filter((item) => {
      return item.status_lelang === 'ditutup' && item.id_pemenang === user.id
    })
  }
  if (currentFilteredHistories.value === 'kalah') {
    return histories.value.filter((item) => {
      return item.status_lelang === 'ditutup' && item.id_pemenang !== user.id
    })
  }
  if (currentFilteredHistories.value === 'sedang-berlangsung') {
    return histories.value.filter((item) => {
      return item.status_lelang === 'dibuka'
    })
  }

  return histories.value
})
console.log(histories.value)

const options = [
  {
    label: 'Tidak ada',
    value: 'tidak-ada',
  },
  {
    label: 'Menang',
    value: 'menang',
  },
  {
    label: 'Kalah',
    value: 'kalah',
  },
  {
    label: 'Sedang Berlangsung',
    value: 'sedang-berlangsung',
  },
]

onMounted(async () => {
  fetchHistories()
})
</script>

<template>
  <BaseLayoutDashboard>
    <template #aside>
      <BaseAside :is-open="isOpenMenu"></BaseAside>
    </template>
    <template #header>
      <DashboardHeader>
        <HamburgerToggle @click="isOpenMenu = !isOpenMenu"></HamburgerToggle>
        <UserProfile></UserProfile>
      </DashboardHeader>
    </template>
    <template #main>
      <div class="row">
        <h1 class="heading">Riwayat Penawaran Saya</h1>
      </div>
      <div class="auction-history-wrapper">
        <div class="row--no-margin">
          <div class="row-group">
            <span>Filter by</span>
            <BaseSelect class="auction-history__select" v-model="currentFilteredHistories">
              <template #options>
                <option :value="item.value" v-for="(item, index) in options" :key="index">
                  {{ item.label }}
                </option>
              </template>
            </BaseSelect>
          </div>
        </div>
        <div class="auction-history__table-container">
          <b-table>
            <template #tableHead>
              <tr>
                <b-table-head>ID</b-table-head>
                <b-table-head>Nama Lot</b-table-head>
                <b-table-head>Tawaran Saya</b-table-head>
                <b-table-head>Tawaran Tertinggi</b-table-head>
                <b-table-head>Status</b-table-head>
                <b-table-head>Tanggal Selesai</b-table-head>
              </tr>
            </template>
            <template #tableBody>
              <tr v-if="isLoading">
                <td colspan="6" class="info">Sedang memuat data...</td>
              </tr>
              <tr v-else-if="filteredHistories.length === 0 && !isLoading">
                <td colspan="6" class="info">Data kosong</td>
              </tr>
              <tr v-for="item in filteredHistories" :key="item.id_lelang" v-else>
                <b-table-data> {{ item.id_lelang }}</b-table-data>
                <b-table-data>{{ item.nama_lot }}</b-table-data>
                <b-table-data>{{
                  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
                    item.penawaran_peserta,
                  )
                }}</b-table-data>
                <b-table-data>
                  <span v-if="item.penawaran_tertinggi_saat_ini === 0">Menghitung</span>
                  <span v-else>
                    {{
                      new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
                        item.penawaran_tertinggi_saat_ini,
                      )
                    }}
                  </span>
                </b-table-data>
                <b-table-data>
                  <span
                    class="badge badge-menang"
                    v-if="item.status_lelang === 'ditutup' && item.id_pemenang === user.id"
                    >Menang</span
                  >
                  <span
                    class="badge badge-kalah"
                    v-if="item.status_lelang === 'ditutup' && item.id_pemenang !== user.id"
                    >Kalah</span
                  >
                  <span
                    class="badge badge-sedang-berlangsung"
                    v-if="item.status_lelang === 'dibuka'"
                    >Sedang Berlangsung</span
                  >
                </b-table-data>
                <b-table-data>{{ dateIsoFormater(item.tgl_selesai) }}</b-table-data>
              </tr>
            </template>
          </b-table>
        </div>
      </div>
    </template>
  </BaseLayoutDashboard>
</template>

<style lang="css" scoped>
/* header .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2rem;
  background: white;
} */

.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  margin-bottom: 1rem;
}

.row--no-margin {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.row-group {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
  align-items: center;
}

.heading {
  color: var(--text-heading-color);
}

.auction-history-wrapper {
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

.auction-history__table-container {
  width: 100%;
  overflow-x: auto;
}

.auction-history__select {
  max-width: fit-content;
}

.table-container table th,
.table-container table td {
  vertical-align: middle;
  text-align: left;
}

.info {
  padding: 2rem;
  text-align: center;
}

.table-container table tbody tr:nth-child(even) {
  background-color: #f1f1f1;
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

.row--end {
  display: flex;
  justify-content: end;
  gap: 5px;
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

.badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 9999px;
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
}

.badge-sedang-berlangsung {
  background: #e0f2fe;
  color: #0369a1;
} /* biru soft */
.badge-menang {
  background: #dcfce7;
  color: #15803d;
} /* hijau soft */
.badge-kalah {
  background: #fee2e2;
  color: #b91c1c;
} /* merah soft */
</style>
