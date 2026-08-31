<script setup lang="js">
import BaseButton from '@/components/BaseButton.vue'
import BaseHeader from '@/components/BaseHeader.vue'
import BaseInput from '@/components/BaseInput.vue'
import BaseLabel from '@/components/BaseLabel.vue'
import BaseModal from '@/components/BaseModal.vue'
import IconClose from '@/components/icons/IconClose.vue'
import AuctionServices from '@/services/AuctionServices'
import HomeServices from '@/services/HomeServices'
import dateIsoFormater from '@/utils/dateIsoFormater'
import moneyFormater from '@/utils/moneyFormater'
import { onMounted, reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const homeSvc = new HomeServices()
let auctionDetails = ref({})
const route = useRoute()
const router = useRouter()
let isOpenModal = ref(false)
const auctionSvc = new AuctionServices()
let isLoading = ref(false)
let isError = ref(false)
let setErrors = ref([])

const idLelang = route.params.id

const openedModalBid = async () => {
  if (localStorage.getItem('user')) {
    isOpenModal.value = true
  } else {
    alert('Anda belum login, login terlebih dahulu!')
    router.push({ name: 'login' })
  }
}

const fetchAuctionById = async (id) => {
  try {
    const response = await homeSvc.show(id)

    if (response.status === 200) {
      auctionDetails.value = response.data?.data
      console.table(auctionDetails.value)
    } else {
      throw response
    }
  } catch (error) {
    console.error(error)
  }
}

const formBidding = reactive({
  penawaran_harga: '',
})

const joinBid = async () => {
  const { penawaran_harga } = formBidding
  const idBarang = auctionDetails.value.barang?.id
  try {
    isLoading.value = true
    isError.value = false
    isOpenModal.value = isError.value
    const response = await auctionSvc.joinBid(idLelang, idBarang, penawaran_harga)

    if (response.status === 201) {
      window.location.reload()
    } else {
      throw response
    }
  } catch (error) {
    isLoading.value = false
    isError.value = true
    if (error.response?.status === 422) {
      setErrors.value = error.response?.data.errors
      isOpenModal.value = isError.value
    }
  } finally {
    isLoading.value = false
    isError.value = false
    isOpenModal.value = isError.value
  }
}

onMounted(async () => {
  fetchAuctionById(route.params.id)
})
</script>

<template>
  <div class="auction-wrapper">
    <BaseHeader></BaseHeader>
    <main class="auction-wrapper__main">
      <div class="auction-content">
        <img
          :src="auctionDetails.barang?.thumbnail_url"
          :alt="auctionDetails.barang?.nama"
          loading="lazy"
          class="auction-content__img"
        />
        <section class="auction-details">
          <router-link class="nav-link" :to="{ name: 'home' }">< Kembali ke beranda</router-link>
          <h2 class="auction-details__heading" v-text="auctionDetails.barang?.nama"></h2>
          <p class="auction-details__description" v-text="auctionDetails.barang?.deskripsi"></p>
          <div class="row">
            <section class="col">
              <p class="auction-details__label">Harga Awal</p>
              <h3
                class="auction-details__price"
                v-text="moneyFormater(auctionDetails.barang?.harga_awal)"
              ></h3>
            </section>
          </div>

          <div class="col--end col--red">
            <p class="auction-details__label--white">Periode</p>
            <span class="auction-details__label--yellow">
              {{ dateIsoFormater(auctionDetails.periode?.mulai) }} s.d.
              {{ dateIsoFormater(auctionDetails.periode?.selesai) }}</span
            >
          </div>
          <BaseButton @click="openedModalBid"
            ><template #btn-content>Ikut Lelang</template></BaseButton
          >
        </section>
      </div>
    </main>

    <BaseModal :is-open="isOpenModal">
      <template #modalHeader>
        <section class="modal-wrapper--row">
          <h3 class="modal-wrapper__heading">Nominal Penawaran</h3>
          <button class="modal-wrapper__button" @click="isOpenModal = false">
            <IconClose></IconClose>
          </button>
        </section>
      </template>

      <template #modalBody>
        <section class="modal-wrapper--col">
          <form id="formBidding" @submit.prevent="joinBid" class="modal-wrapper__form">
            <div class="modal-wrapper__form-group">
              <BaseLabel for="penawaran-harga-input" value="Penawaran Harga"></BaseLabel>
              <BaseInput
                id="penawaran-harga-input"
                type="number"
                v-model="formBidding.penawaran_harga"
              ></BaseInput>
              <p class="modal-wrapper__description">
                Hanya tuliskan nominalnya saja tanpa dipisahkan titik, contohnya 1000000
              </p>
            </div>
          </form>
        </section>
      </template>

      <template #modalFooter>
        <section class="modal-wrapper--row--end">
          <BaseButton class="modal-wrapper__button" @click="isOpenModal = false">
            <template #btn-content>Cancel</template>
          </BaseButton>

          <BaseButton
            form="formBidding"
            class="modal-wrapper__button--secondary"
            :disabled="isLoading"
            :class="{ 'btn-disabled': isLoading }"
          >
            <template #btn-content>
              <p v-if="isLoading">Loading...</p>
              <p v-else>Bid</p>
            </template>
          </BaseButton>
        </section>
      </template>
    </BaseModal>
  </div>
</template>

<style lang="css" scoped>
.auction-wrapper {
  min-width: 100vw;
  min-height: 100vh;
  overflow-x: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}

.auction-content {
  padding: 1.5rem;
  margin-top: 2rem;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.8rem;
}

.auction-content__img {
  width: 100%;
  height: 100%;
  border-radius: 0.5rem;
  object-fit: cover;
}

.auction-details {
  width: auto;
  height: auto;
  border-radius: 1rem;
  padding: 1rem;
  display: flex;
  justify-content: flex-start;
  flex-direction: column;
  align-items: start;
  gap: 1rem;
}

.auction-details__heading {
  color: var(--text-heading-color);
}

.auction-details__description {
  color: var(--text-description-color);
}

.auction-details__label {
  color: var(--text-description-color);
  font-weight: 600;
  font-size: small;
}

.auction-details__label--white {
  color: white;
  font-weight: 600;
  font-size: small;
}

.auction-details__label--yellow {
  color: yellow;
  font-weight: 600;
  font-size: small;
}

.auction-details__price {
  color: var(--primary-color);
  font-size: larger;
  margin-top: 0.2rem;
}

.row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  width: 100%;
}

.col {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-direction: column;
}

.col--end {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.8rem;
  width: 100%;
}

.col--red {
  border-radius: 0.5rem;
  background-color: indianred;
}

.modal-wrapper--row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.modal-wrapper--row--end {
  display: flex;
  justify-content: end;
  align-items: center;
  width: 100%;
  gap: 0.2rem;
}

.modal-wrapper--col {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.modal-wrapper__form {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}
.modal-wrapper__form-group {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-direction: column;
  gap: 0.4rem;
  width: 100%;
}

.modal-wrapper__heading {
  color: var(--text-heading-color);
}

.modal-wrapper__description {
  color: var(--text-description-color);
  text-align: left;
  font-size: small;
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
  padding: 8px 15px !important;
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
