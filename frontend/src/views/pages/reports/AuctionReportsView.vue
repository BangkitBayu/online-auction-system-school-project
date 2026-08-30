<script setup lang="js">
import BaseButton from '@/components/BaseButton.vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import IconPrint from '@/components/icons/IconPrint.vue'
import BTableData from '@/components/table/b-table-data.vue'
import BTableHead from '@/components/table/b-table-head.vue'
import BTable from '@/components/table/b-table.vue'
import UserProfile from '@/components/UserProfile.vue'
import ReportServices from '@/services/ReportServices'
import dateIsoFormater from '@/utils/dateIsoFormater'
import moneyFormater from '@/utils/moneyFormater'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import { onMounted, ref } from 'vue'

let isOpenAside = ref(true)
let expiredAuctions = ref([])
const reportSvc = new ReportServices()
let isLoading = ref(false)
let pagination = ref({})

const fetchAuctionsReport = async (page = 1) => {
  try {
    isLoading.value = true

    const response = await reportSvc.index({ page })

    if (response.status === 200) {
      pagination.value = response.data?.data
      expiredAuctions.value = response.data?.data
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

const printAuctionReportDetail = async (id, filename) => {
  try {
    const response = await reportSvc.printAuctionReportDetail(id)

    const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', filename)
    document.body.appendChild(link)
    link.click()
    link.remove()
  } catch (error) {
    console.error(error)
  }
}

onMounted(async () => {
  fetchAuctionsReport()
})
</script>
<template>
  <BaseLayoutDashboard>
    <template #header>
      <DashboardHeader>
        <HamburgerToggle @click="isOpenAside = !isOpenAside"></HamburgerToggle>
        <UserProfile></UserProfile>
      </DashboardHeader>
    </template>
    <template #aside>
      <BaseAside :is-open="isOpenAside"></BaseAside>
    </template>
    <template #main>
      <section class="row">
        <h1 class="page-title">Laporan Lelang</h1>
        <router-link class="nav-link" :to="{ name: 'dashboardView' }">
          < Kembali ke halaman Dashboard</router-link
        >
      </section>
      <section class="auction-reports__wrapper">
        <div class="auction-reports__table-container">
          <BTable>
            <template #tableHead>
              <tr>
                <BTableHead>ID</BTableHead>
                <BTableHead>Nama Lot</BTableHead>
                <BTableHead>Kategori Lot</BTableHead>
                <BTableHead>Tanggal Selesai</BTableHead>
                <BTableHead>Pemenang</BTableHead>
                <BTableHead>Harga Akhir</BTableHead>
                <BTableHead>Aksi</BTableHead>
              </tr>
            </template>

            <template #tableBody>
              <tr v-if="expiredAuctions.length === 0 && !isLoading">
                <td class="auction-reports__table-td-info" colspan="7">Belum ada data</td>
              </tr>

              <tr v-else-if="isLoading">
                <td class="auction-reports__table-td-info" colspan="7">Sedang memuat data ...</td>
              </tr>

              <tr v-else v-for="item in expiredAuctions" :key="item.id_lelang">
                <BTableData>{{ item.id_lelang }}</BTableData>
                <BTableData>{{ item.nama_lot }}</BTableData>
                <BTableData>
                  <span class="block-text">{{ item.kategori_lot }} </span>
                </BTableData>
                <BTableData>{{ dateIsoFormater(item.tgl_selesai) }}</BTableData>
                <BTableData :class="{ red: item.pemenang === 'kosong' }">{{
                  item.pemenang
                }}</BTableData>
                <BTableData>{{ moneyFormater(item.harga_akhir) }}</BTableData>
                <BTableData>
                  <BaseButton
                    class="auction-reports__table-cta-btn"
                    @click="
                      printAuctionReportDetail(
                        item.id_lelang,
                        `laporan-pemenang-lelang-${item.id_lelang}`,
                      )
                    "
                  >
                    <template #btn-content>
                      <IconPrint></IconPrint>
                    </template>
                  </BaseButton>
                </BTableData>
              </tr>
            </template>
          </BTable>
          <BasePagination
            v-if="pagination.last_page"
            :pagination="pagination"
            @page-changed="expiredAuctions"
          >
          </BasePagination>
        </div>
      </section>
    </template>
  </BaseLayoutDashboard>
</template>
<style lang="css" scoped>
.page-title {
  color: var(--text-heading-color);
}

.row {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-block: 0.5rem 1.5rem;
}

.auction-reports__wrapper {
  background-color: #f8f8f8;
  display: flex;
  justify-content: start;
  align-items: start;
  flex-direction: column;
  min-height: auto;
  width: 100%;
  border-radius: 1rem;
  padding: 1rem;
  gap: 1rem;
}

.auction-reports__table-td-info {
  padding: 2rem;
  text-align: center;
}

.auction-reports__table-container {
  width: 100%;
  overflow-x: auto;
}

.auction-reports__table-cta-btn {
  width: fit-content;
  height: fit-content;
}

.red {
  color: red;
  font-weight: 600;
}

.block-text {
  padding: 0.6rem;
  display: inline-block;
  border-radius: 1rem;
  background-color: var(--primary-color);
}
</style>
