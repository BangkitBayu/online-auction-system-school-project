<script setup lang="js">
import BaseButton from '@/components/BaseButton.vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import BSearchBar from '@/components/searchBar/b-search-bar.vue'
import BTableData from '@/components/table/b-table-data.vue'
import BTable from '@/components/table/b-table.vue'
import UserProfile from '@/components/UserProfile.vue'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import { ref } from 'vue'

let isOpenAside = ref(true)

function searchOfficer() {
  alert('aloo')
}

let isLoading = ref(false)

let officers = ref([])
let officerWanted = ref([])
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
        <h1 class="heading">Kelola Petugas</h1>
        <router-link class="nav-link" :to="{ name: 'dashboardView' }">
          < Kembali ke halaman Dashboard</router-link
        >
      </section>
      <section class="officer-wrapper">
        <div class="row">
          <BSearchBar @handle-search="searchOfficer"></BSearchBar>
          <BaseButton class="officer-wrapper__btn"
            ><template #btn-content>Tambah Petugas</template></BaseButton
          >
        </div>

        <div class="officer-wrapper__table-container">
          <BTable>
            <template #tableHead>
              <tr>
                <BTableData>ID</BTableData>
                <BTableData>Nama Lengkap</BTableData>
                <BTableData>Username</BTableData>
                <BTableData>Telp</BTableData>
                <BTableData>Aksi</BTableData>
              </tr>
            </template>
            <template #tableBody>
              <tr v-if="isLoading">
                <td class="officer-wrapper__table-info" colspan="5">Sedang memuat data...</td>
              </tr>
              <tr v-else-if="officers.length === 0 && !isLoading">
                <td class="officer-wrapper__table-info" colspan="5">Daftar petugas kosong</td>
              </tr>
            </template>
          </BTable>
        </div>
      </section>
    </template>
  </BaseLayoutDashboard>
</template>
<style lang="css" scoped>
.heading {
  color: var(--text-heading-color);
}

.row {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-block: 0.5rem 1.5rem;
  gap: 0.8rem;
}

.officer-wrapper {
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

.officer-wrapper__btn {
  max-width: fit-content !important;
}

.officer-wrapper__table-container {
  width: 100%;
  overflow-x: auto;
}

.officer-wrapper__table-info {
  padding: 2rem;
  text-align: center;
  color: var(--text-description-color);
}
</style>
