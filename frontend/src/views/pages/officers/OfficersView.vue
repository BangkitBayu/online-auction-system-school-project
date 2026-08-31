<script setup lang="js">
import BaseButton from '@/components/BaseButton.vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import BSearchBar from '@/components/searchBar/b-search-bar.vue'
import BTableData from '@/components/table/b-table-data.vue'
import BTable from '@/components/table/b-table.vue'
import UserProfile from '@/components/UserProfile.vue'
import OfficerService from '@/services/OfficerService'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import { computed, onMounted, ref } from 'vue'
import IconDelete from '@/components/icons/IconDelete.vue'
import IconEdit from '@/components/icons/IconEdit.vue'
import IconClose from '@/components/icons/IconClose.vue'
import BaseModal from '@/components/BaseModal.vue'

let isOpenAside = ref(true)
const officerSvc = new OfficerService()
let officers = ref([])
let officerKeyword = ref('')
let petugasId = ref(null)
let isOpenModal = ref(false)

let isLoading = ref(false)

const officerSearched = computed(() => {
  if (officerKeyword.value)
    return officers.value.filter((officer) => {
      return (
        officer.username.toLowerCase().includes(officerKeyword.value.toLowerCase()) ||
        officer.nama_petugas.toLowerCase().includes(officerKeyword.value.toLowerCase())
      )
    })
  return officers.value
})

const fetchOfficers = async () => {
  try {
    isLoading.value = true

    const response = await officerSvc.index()

    if (response.status === 200) {
      officers.value = response.data.data
    }
  } catch (error) {
    isLoading.value = false
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

const destroyOfficer = async (id) => {
  try {
    isLoading.value = true
    const response = await officerSvc.destroy(id)

    if (response.status === 200) {
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

const openedModalDelete = (id) => {
  isOpenModal.value = !isOpenModal.value
  petugasId.value = id
}

onMounted(() => {
  fetchOfficers()
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
        <h1 class="heading">Kelola Petugas</h1>
        <router-link class="nav-link" :to="{ name: 'dashboardView' }">
          < Kembali ke halaman Dashboard</router-link
        >
      </section>
      <section class="officer-wrapper">
        <div class="row">
          <BSearchBar v-model="officerKeyword"></BSearchBar>
          <BaseButton class="officer-wrapper__btn" @click="$router.push({ name: 'officerAddForm' })"
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
              <tr v-else-if="officerSearched.length === 0 && !isLoading">
                <td class="officer-wrapper__table-info" colspan="5">Daftar petugas tidak ditemukan</td>
              </tr>
              <tr v-for="item in officerSearched" :key="item.id_petugas" v-else>
                <BTableData>{{ item.id_petugas }}</BTableData>
                <BTableData>{{ item.nama_petugas }}</BTableData>
                <BTableData>{{ item.username }}</BTableData>
                <BTableData>{{ item.telp }}</BTableData>
                <BTableData>
                  <div class="officer-wrapper__table-cta-container">
                    <BaseButton
                      class="officer-wrapper__btn"
                      @click="
                        $router.push({ name: 'officerEditForm', params: { id: item.id_petugas } })
                      "
                      ><template #btn-content><IconEdit></IconEdit></template
                    ></BaseButton>
                    <BaseButton
                      class="officer-wrapper__btn"
                      :variant="'danger'"
                      @click="openedModalDelete(item.id_petugas)"
                      ><template #btn-content><IconDelete></IconDelete></template
                    ></BaseButton>
                  </div>
                </BTableData>
              </tr>
            </template>
          </BTable>
        </div>
      </section>
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
            Apakah anda yakin ingin menghapus petugas ini beserta data datanya?
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
              :disabled="isLoading"
              :class="isLoading ? 'btn-disabled' : ''"
              @click="destroyOfficer(petugasId)"
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

.row--end {
  display: flex;
  justify-content: end;
  gap: 5px;
}

.officer-wrapper__btn {
  max-width: fit-content;
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

.officer-wrapper__table-cta-container {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 0.8rem;
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
