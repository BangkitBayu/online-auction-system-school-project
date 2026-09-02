<script setup lang="js">
import BaseInput from '@/components/BaseInput.vue'
import BaseLabel from '@/components/BaseLabel.vue'
import DashboardHeader from '@/components/DashboardHeader.vue'
import HamburgerToggle from '@/components/HamburgerToggle.vue'
import UserProfile from '@/components/UserProfile.vue'
import BaseAside from '@/views/administrator/components/BaseAside.vue'
import BaseLayoutDashboard from '@/views/templates/BaseLayoutDashboard.vue'
import { reactive, ref } from 'vue'
import BaseButton from '@/components/BaseButton.vue'
import IconEyeOff from '@/components/icons/IconEyeOff.vue'
import IconEyeOn from '@/components/icons/IconEyeOn.vue'
import OfficerService from '@/services/OfficerService'
import BaseAlert from '@/components/BaseAlert.vue'

let showAlert = ref(false)
let alertMessage = ref('')
let alertType = ref('')

const closeAlert = () => {
  showAlert.value = false
}

const officerSvc = new OfficerService()
let isOpenMenu = ref(true)
let isLoading = ref(false)
let setErrors = ref([])

let isShowPassword = ref(false)
let isShowConfirmPassword = ref(false)

const formData = reactive({
  nama_petugas: '',
  username: '',
  telp: '',
  password: '',
  confirm_password: '',
})

const submitData = async () => {
  const { nama_petugas, username, telp, password, confirm_password } = formData

  try {
    isLoading.value = true

    const response = await officerSvc.store(
      nama_petugas,
      username,
      telp,
      password,
      confirm_password,
    )

    if (response.status === 201) {
      alertMessage.value = 'Petugas berhasil ditambahkan!'
      alertType.value = 'success'
      setTimeout(() => {
        showAlert.value = false
      }, 5000)
    } else {
      throw response
    }
  } catch (error) {
    isLoading.value = false
    if (error.response?.status === 422) {
      setErrors.value = error.response.data?.errors
      console.error(error)
      showAlert.value = true
      alertMessage.value = 'Terjadi kesalahan, silahkan periksa kembali data anda!'
      alertType.value = 'error'
      setTimeout(() => {
        showAlert.value = false
      }, 5000)
    }
    console.error(error)
  } finally {
    isLoading.value = false
  }
}
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
      <!-- Alert component -->
      <BaseAlert :is-show="showAlert" :message="alertMessage" :type="alertType" @close="closeAlert">
      </BaseAlert>
      <section class="row">
        <h2 class="text__heading">Tambah Petugas</h2>
        <router-link :to="{ name: 'officers' }" class="nav-link" style="margin-top: 0px !important"
          >< Kembali ke daftar petugas</router-link
        >
      </section>
      <section class="form-wrapper">
        <form @submit.prevent="submitData" class="form-wrapper__form-add">
          <div class="form-wrapper__form-group">
            <BaseLabel :for="'nama-lengkap-input'" :value="'Nama Lengkap'"></BaseLabel>
            <BaseInput
              :id="'nama-lengkap-input'"
              :type="'text'"
              v-model="formData.nama_petugas"
            ></BaseInput>
            <p class="error" v-if="setErrors.nama_petugas" v-text="setErrors.nama_petugas[0]"></p>
          </div>
          <div class="form-wrapper__form-group">
            <BaseLabel :for="'username-input'" :value="'Username'"></BaseLabel>
            <BaseInput
              :id="'username-input'"
              :type="'text'"
              v-model="formData.username"
            ></BaseInput>
            <p class="error" v-if="setErrors.username" v-text="setErrors.username[0]"></p>
          </div>
          <div class="form-wrapper__form-group">
            <BaseLabel :for="'telp-input'" :value="'Nomor Telepon'"></BaseLabel>
            <BaseInput :id="'telp-input'" :type="'tel'" v-model="formData.telp"></BaseInput>
            <p class="error" v-if="setErrors.telp" v-text="setErrors.telp[0]"></p>
          </div>
          <div class="form-wrapper__form-group">
            <BaseLabel :for="'password-input'" :value="'Password'"></BaseLabel>
            <div class="form-wrapper__form-group-password">
              <BaseInput
                :id="'password-input'"
                :type="isShowPassword ? 'text' : 'password'"
                :placeholder="'Password'"
                v-model="formData.password"
              ></BaseInput>
              <button type="button" id="toggle-password" @click="isShowPassword = !isShowPassword">
                <div v-show="isShowPassword">
                  <IconEyeOn></IconEyeOn>
                </div>
                <div v-show="!isShowPassword">
                  <IconEyeOff></IconEyeOff>
                </div>
              </button>
            </div>
            <p class="error" v-if="setErrors.password" v-text="setErrors.password[0]"></p>
          </div>
          <div class="form-wrapper__form-group">
            <BaseLabel :for="'confirm-password-input'" :value="'Konfirmasi password'"></BaseLabel>
            <div class="form-wrapper__form-group-password">
              <BaseInput
                :id="'confirm-password-input'"
                :type="isShowConfirmPassword ? 'text' : 'password'"
                :placeholder="'Konfirmasi password'"
                v-model="formData.confirm_password"
              ></BaseInput>
              <button
                type="button"
                id="toggle-password"
                @click="isShowConfirmPassword = !isShowConfirmPassword"
              >
                <div v-show="isShowConfirmPassword">
                  <IconEyeOn></IconEyeOn>
                </div>
                <div v-show="!isShowConfirmPassword">
                  <IconEyeOff></IconEyeOff>
                </div>
              </button>
            </div>
            <p
              class="error"
              v-if="setErrors.confirm_password"
              v-text="setErrors.confirm_password[0]"
            ></p>
          </div>
          <BaseButton
            class="form-wrapper_btn"
            :disabled="isLoading"
            :class="isLoading ? 'btn-disabled' : ''"
            ><template #btn-content>Submit</template></BaseButton
          >
        </form>
      </section>
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
  min-height: fit-content;
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

.form-wrapper__form-group-password {
  width: 100%;
  border-radius: 10px;
  border: 1px solid #cccccc;
  background-color: white;
  display: flex;
  justify-content: center;
  align-items: center;
}

.form-wrapper__description {
  color: var(--text-description-color);
}

.form-wrapper_btn {
  width: max-content;
}

.btn-disabled {
  background-color: #3b3b3b !important;
}

#toggle-password {
  padding-inline: 0.8rem;
  border: none;
  background: transparent;
}
</style>
