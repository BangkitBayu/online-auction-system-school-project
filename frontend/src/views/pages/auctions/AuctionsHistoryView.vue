<script setup lang="js">
import BaseButton from '@/components/BaseButton.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import { onMounted, reactive, ref } from 'vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import UserProfile from '@/components/UserProfile.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import ItemServices from '@/services/ItemServices.js'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import bSearchBar from '@/components/searchBar/b-search-bar.vue'
import BTable from '@/components/table/b-table.vue'
import bTableHead from '@/components/table/b-table-head.vue'
import bTableData from '@/components/table/b-table-data.vue'
import BasePagination from '@/components/BasePagination.vue'
import IconDetail from '@/components/icons/IconDetail.vue'
import IconEdit from '@/components/icons/IconEdit.vue'
import IconDelete from '@/components/icons/IconDelete.vue'
import { useRouter } from 'vue-router'
import dateIsoFormater from '@/utils/dateIsoFormater'
import BaseModal from '@/components/BaseModal.vue'
import IconClose from '@/components/icons/IconClose.vue'

const user = JSON.parse(localStorage.getItem('user'))

const itemSvc = new ItemServices()

let auctions = reactive([])
let pagination = ref({})
let isLoading = ref(false)

let isOpenMenu = ref(true)
let isOpenModal = ref(false)

function openedMenu() {
  isOpenMenu.value = !isOpenMenu.value
}

const openedModalDelete = (id) => {
  isOpenModal.value = !isOpenModal.value
  itemId.value = id
}

const getAllAuction = async (page = 1) => {
  try {
    isLoading.value = true

    // Kirim nomor halaman ke service (misal: auctionSvc.index({ page }) atau query string)
    const response = await itemSvc.index({ page })

    if (response.status == 200) {
      pagination.value = response.data.data
      auctions = response.data.data.data // Gunakan .value jika auctions adalah ref()
    } else {
      throw response
    }
  } catch (error) {
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

let itemId = ref(null)

const destroyItem = async (id) => {
  try {
    isLoading.value = true
    const response = await itemSvc.destroy(id)

    if (response?.status === 200) {
      isOpenModal.value = false
      window.location.reload()
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
  getAllAuction()
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
      <h3 class="title-page">Asset Lelang</h3>
      <div class="content-wrapper">
        <div class="top-content-container">
          <b-search-bar></b-search-bar>
          <BaseButton
            v-if="user.role === 'petugas'"
            class="create-asset-btn"
            @click="$router.push({ name: 'formAddAsset' })"
            ><template #btn-content>Tambah asset</template></BaseButton
          >
        </div>
        <div class="middle-content-container">
          <div class="table-container">
            <b-table>
              <template #tableHead>
                <tr>
                  <b-table-head>ID</b-table-head>
                  <b-table-head>Nama Asset Lelang</b-table-head>
                  <b-table-head>Tanggal</b-table-head>
                  <b-table-head>Harga Awal</b-table-head>
                  <b-table-head>Kategori Lelang</b-table-head>
                  <b-table-head>Aksi</b-table-head>
                </tr>
              </template>
              <template #tableBody>
                <tr v-if="isLoading">
                  <b-table-data colspan="6" class="info">Sedang memuat data...</b-table-data>
                </tr>
                <tr v-else-if="auctions.length == 0">
                  <b-table-data colspan="6" class="info">Data masih kosong...</b-table-data>
                </tr>
                <tr v-for="(item, index) in auctions" :key="index" v-else>
                  <b-table-data> {{ item.id_barang }}</b-table-data>
                  <b-table-data>{{ item.nama_barang }}</b-table-data>
                  <b-table-data>{{ dateIsoFormater(item.tgl) }}</b-table-data>
                  <b-table-data>{{
                    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
                      item.harga_awal,
                    )
                  }}</b-table-data>
                  <b-table-data>{{ item.kategori_barang?.nama_kategori_barang }}</b-table-data>
                  <b-table-data>
                    <div class="action-container">
                      <BaseButton
                        class="action-btn"
                        :variant="'info'"
                        @click="
                          $router.push({ name: 'detailAuction', params: { id: item.id_barang } })
                        "
                        ><template #btn-content><IconDetail></IconDetail></template
                      ></BaseButton>
                      <BaseButton
                        v-if="user.role === 'petugas'"
                        class="action-btn"
                        @click="
                          $router.push({ name: 'formEditAsset', params: { id: item.id_barang } })
                        "
                        ><template #btn-content><IconEdit></IconEdit></template
                      ></BaseButton>
                      <BaseButton
                        v-if="user.role === 'petugas'"
                        class="action-btn"
                        :variant="'danger'"
                        @click="openedModalDelete(item.id_barang)"
                        ><template #btn-content><IconDelete></IconDelete></template
                      ></BaseButton>
                    </div>
                  </b-table-data>
                </tr>
              </template>
            </b-table>
          </div>
          <BasePagination
            v-if="pagination.last_page"
            :pagination="pagination"
            @page-changed="getAllAuction"
          >
          </BasePagination>
        </div>
      </div>
      <BaseModal :is-open="isOpenModal">
        <template #modalHeader>
          <div class="row">
            <h3 class="modal-wrapper__heading">Konfirmasi Hapus</h3>
            <button class="modal-wrapper__button" @click="isOpenModal = !isOpenModal">
              <IconClose></IconClose>
            </button>
          </div>
        </template>
        <template #modalBody>
          <p class="modal-wrapper__description">
            Apakah anda yakin ingin menghapus asset lelang ini?
          </p>
        </template>
        <template #modalFooter>
          <div class="row--end">
            <BaseButton class="modal-wrapper__button" @click="isOpenModal = !isOpenModal"
              ><template #btn-content>Cancel</template></BaseButton
            >
            <BaseButton
              class="modal-wrapper__button--secondary"
              :variant="'danger'"
              :disabled="isLoading ? 'true' : ''"
              :class="isLoading ? 'btn-disabled' : ''"
              @click="destroyItem(itemId)"
              ><template #btn-content
                ><p v-if="isLoading">Loading...</p>
                <p v-else>Hapus</p></template
              ></BaseButton
            >
          </div>
        </template>
      </BaseModal>
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

.create-asset-btn {
  max-width: 15% !important;
}

.create-asset-btn p {
  white-space: none;
}

.top-content-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  width: 100%;
}

.middle-content-container {
  display: flex;
  flex-direction: column;
  justify-content: start;
  align-items: start;
  width: 100%;
}

.table-container {
  width: 100%;
  overflow-x: auto;
}

.table-container .info {
  text-align: center !important;
}

.table-container table th,
.table-container table td {
  vertical-align: middle;
  text-align: left;
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
