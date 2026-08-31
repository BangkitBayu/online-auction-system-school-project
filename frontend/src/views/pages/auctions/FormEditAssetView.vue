<script setup lang="js">
import BaseInput from '@/components/BaseInput.vue'
import BaseLabel from '@/components/BaseLabel.vue'
import BaseTextarea from '@/components/BaseTextarea.vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import UserProfile from '@/components/UserProfile.vue'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import { onMounted, reactive, ref } from 'vue'
import BaseSelect from '@/components/BaseSelect.vue'
import CategoryAuctionService from '@/services/CategoryAuctionServices'
import BaseButton from '@/components/BaseButton.vue'
import ItemServices from '@/services/ItemServices'
import { useRouter, useRoute } from 'vue-router'
import BTab from '@/components/tab/b-tab.vue'

const categoryAuctionSvc = new CategoryAuctionService()
const itemSvc = new ItemServices()
const router = useRouter()
const route = useRoute()

let categoriesAuction = ref([])
let asset = ref({})
let users = ref([])

let isOpenMenu = ref(true)
let isLoading = ref(false)
let setErrors = ref({}) // Diubah ke Object

const tabs = [
  { name: 'data-lelang', label: 'Data Lelang' },
  { name: 'jadwalkan-lelang', label: 'Jadwalkan Lelang' },
]

let thumbnailUploaded = ref(null)
let imagePreview = ref('')

const formData = reactive({
  nama_barang: '',
  tgl: '',
  harga_awal: '',
  deskripsi_barang: '',
  id_kategori_barang: '',
  tgl_mulai_lelang: '',
  tgl_akhir_lelang: '',
})

const handleFileUpload = (e) => {
  let file = e.target.files[0]
  if (file) {
    if (imagePreview.value) {
      URL.revokeObjectURL(imagePreview.value) // Revoke memory lama
    }
    thumbnailUploaded.value = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const getItemById = async (id) => {
  try {
    isLoading.value = true
    const response = await itemSvc.getItemById(id)

    if (response.status === 200) {
      asset.value = response.data.data
      formData.nama_barang = asset.value.nama_barang || ''
      formData.harga_awal = asset.value.harga_awal || ''
      formData.deskripsi_barang = asset.value.deskripsi_barang || ''
      formData.id_kategori_barang = asset.value.id_kategori_barang || ''
      formData.tgl = asset.value.tgl || ''
      formData.tgl_mulai_lelang = asset.value.lelang?.tgl_mulai_lelang || ''
      formData.tgl_akhir_lelang = asset.value.lelang?.tgl_akhir_lelang || ''
    }
  } catch (error) {
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

const getCategoriesAuction = async () => {
  try {
    isLoading.value = true
    const response = await categoryAuctionSvc.index()
    if (response.status === 200) {
      categoriesAuction.value = response.data.data
    }
  } catch (error) {
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

const getUsers = async () => {
  try {
    isLoading.value = true
    const response = await itemSvc.getUsers()
    if (response.status === 200) {
      users.value = response.data.data
    }
  } catch (error) {
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

const submitData = async () => {
  const id = route.params.id
  setErrors.value = {}

  const payload = new FormData()
  payload.append('_method', 'PUT')
  payload.append('nama_barang', formData.nama_barang)
  payload.append('tgl', formData.tgl)
  payload.append('harga_awal', formData.harga_awal)
  payload.append('deskripsi_barang', formData.deskripsi_barang)
  payload.append('id_kategori_barang', formData.id_kategori_barang)
  payload.append('tgl_mulai_lelang', formData.tgl_mulai_lelang)
  payload.append('tgl_akhir_lelang', formData.tgl_akhir_lelang)

  if (thumbnailUploaded.value !== null) {
    payload.append('thumbnail', thumbnailUploaded.value)
  }

  try {
    isLoading.value = true
    const response = await itemSvc.edit(id, payload)
    if (response.status === 200 || response.status === 201) {
      router.push({ name: 'assetAuctions' })
    }
  } catch (error) {
    if (error.response?.status === 422) {
      setErrors.value = error.response.data.errors || {}
    }
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  if (route.params.id) {
    getItemById(route.params.id)
  }
  getCategoriesAuction()
  getUsers()
})
</script>

<template>
  <BaseLayoutDashboard>
    <template #header>
      <DashboardHeader>
        <HamburgerToggle @click="isOpenMenu = !isOpenMenu"></HamburgerToggle>
        <UserProfile></UserProfile>
      </DashboardHeader>
    </template>
    <template #aside>
      <BaseAside :is-open="isOpenMenu"></BaseAside>
    </template>
    <template #main>
      <section class="row">
        <h2 class="text__heading">Perbarui Data Asset</h2>
        <router-link
          :to="{ name: 'assetAuctions' }"
          class="nav-link"
          style="margin-top: 0px !important"
        >
          &lt; Kembali ke daftar asset
        </router-link>
      </section>

      <!-- Wrap seluruh Form di luar Tab agar input dari kedua tab terkirim bersamaan -->
      <form @submit.prevent="submitData" enctype="multipart/form-data">
        <BTab :tabs="tabs" :default-tab="'data-lelang'">
          <template #data-lelang>
            <section class="form-wrapper">
              <div class="form-wrapper__form-add">
                <div class="form-wrapper__form-group">
                  <BaseLabel :for="'thumbnail-input'" :value="'Foto Thumbnail'"></BaseLabel>
                  <BaseInput
                    accept="image/*"
                    :id="'thumbnail-input'"
                    :type="'file'"
                    :is-required="false"
                    @change="handleFileUpload"
                  ></BaseInput>
                  <label v-if="imagePreview">Thumbnail Preview</label>
                  <img
                    class="form-wrapper__img-preview"
                    v-if="imagePreview"
                    :src="imagePreview"
                    width="300"
                    height="300"
                  />
                  <p class="error" v-if="setErrors.thumbnail" v-text="setErrors.thumbnail?.[0]"></p>
                </div>

                <div class="form-wrapper__form-group">
                  <BaseLabel :for="'nama-barang-input'" :value="'Nama Barang'"></BaseLabel>
                  <BaseInput
                    :id="'nama-barang-input'"
                    :type="'text'"
                    v-model="formData.nama_barang"
                  ></BaseInput>
                  <p class="error" v-if="setErrors.nama_barang" v-text="setErrors.nama_barang?.[0]"></p>
                </div>

                <div class="form-wrapper__form-group">
                  <BaseLabel :for="'harga-awal-input'" :value="'Harga Awal'"></BaseLabel>
                  <BaseInput
                    :id="'harga-awal-input'"
                    :type="'number'"
                    v-model="formData.harga_awal"
                  ></BaseInput>
                  <p class="form-wrapper__description">
                    Hanya tuliskan nominalnya saja tanpa dipisahkan titik, contohnya 1000000
                  </p>
                  <p class="error" v-if="setErrors.harga_awal" v-text="setErrors.harga_awal?.[0]"></p>
                </div>
                <div class="form-wrapper__form-group">
                  <BaseLabel :for="'tanggal-input'" :value="'Tanggal'"></BaseLabel>
                  <BaseInput
                    :id="'tanggal-input'"
                    :type="'datetime-local'"
                    v-model="formData.tgl"
                  ></BaseInput>
                  <p class="error" v-if="setErrors.tgl" v-text="setErrors.tgl?.[0]"></p>
                </div>

                <div class="form-wrapper__form-group">
                  <BaseLabel :for="'deskripsi-barang-input'" :value="'Deskripsi Barang'"></BaseLabel>
                  <BaseTextarea
                    id="deskripsi-barang-input"
                    :row="'3'"
                    :col="'100'"
                    v-model="formData.deskripsi_barang"
                  ></BaseTextarea>
                  <p
                    class="error"
                    v-if="setErrors.deskripsi_barang"
                    v-text="setErrors.deskripsi_barang?.[0]"
                  ></p>
                </div>

                <div class="form-wrapper__form-group">
                  <BaseLabel :for="'kategori-barang-select'" :value="'Kategori Lelang'"></BaseLabel>
                  <BaseSelect
                    id="kategori-barang-select"
                    :options="categoriesAuction"
                    v-if="!isLoading && categoriesAuction.length !== 0"
                    v-model="formData.id_kategori_barang"
                  >
                    <template #options>
                      <option value="">Pilih kategori lot</option>
                      <option
                        v-for="item in categoriesAuction"
                        :key="item.id"
                        :value="item.id_kategori_barang"
                      >
                        {{ item.nama_kategori_barang }}
                      </option>
                    </template>
                  </BaseSelect>

                  <BaseSelect v-else>
                    <template #options>
                      <option>Sedang memuat kategori...</option>
                    </template>
                  </BaseSelect>
                  <p
                    class="error"
                    v-if="setErrors.id_kategori_barang"
                    v-text="setErrors.id_kategori_barang?.[0]"
                  ></p>
                </div>
              </div>
            </section>
          </template>

          <template #jadwalkan-lelang>
            <section class="form-wrapper">
              <div class="form-wrapper__form-add">
                <div class="form-wrapper__form-group">
                  <BaseLabel :value="'Tanggal Mulai'" :for="'tanggal-mulai-input'"></BaseLabel>
                  <BaseInput
                    :id="'tanggal-mulai-input'"
                    :type="'datetime-local'"
                    v-model="formData.tgl_mulai_lelang"
                  ></BaseInput>
                  <p
                    class="error"
                    v-if="setErrors.tgl_mulai_lelang"
                    v-text="setErrors.tgl_mulai_lelang?.[0]"
                  ></p>
                </div>

                <div class="form-wrapper__form-group">
                  <BaseLabel :value="'Tanggal Berakhir'" :for="'tanggal-akhir-input'"></BaseLabel>
                  <BaseInput
                    :id="'tanggal-akhir-input'"
                    :type="'datetime-local'"
                    v-model="formData.tgl_akhir_lelang"
                  ></BaseInput>
                  <p
                    class="error"
                    v-if="setErrors.tgl_akhir_lelang"
                    v-text="setErrors.tgl_akhir_lelang?.[0]"
                  ></p>
                </div>
              </div>
            </section>
          </template>
        </BTab>

        <!-- Button ditaruh di luar tab agar selalu terlihat -->
        <div style="margin-top: 1rem;">
          <BaseButton
            class="form-wrapper_btn"
            :disabled="isLoading"
            :class="isLoading ? 'btn-disabled' : ''"
          >
            <template #btn-content>{{ isLoading ? 'Memproses...' : 'Submit' }}</template>
          </BaseButton>
        </div>
      </form>
    </template>
  </BaseLayoutDashboard>
</template>

<style lang="css" scoped>
.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  margin-bottom: 1rem;
}

.text__heading {
  color: var(--text-heading-color);
}

.form-wrapper {
  display: flex;
  justify-content: start;
  align-items: start;
  flex-direction: column;
  width: 100%;
}

.form-wrapper__form-add {
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

.form-wrapper__form-group {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: start;
  gap: 0.8rem;
  width: 100%;
}

.form-wrapper__description {
  color: var(--text-description-color);
}

.form-wrapper_btn {
  width: max-content;
}

.form-wrapper__img-preview {
  border-radius: 10px;
}

.btn-disabled {
  background-color: #3b3b3b !important;
}

.error {
  color: #dc3545;
  font-size: 0.875rem;
}
</style>
