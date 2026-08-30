<script setup lang="js">
import IconEyeOn from '@/components/icons/IconEyeOn.vue'
import IconEyeOff from '@/components/icons/IconEyeOff.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseLabel from '@/components/BaseLabel.vue'
import BaseInput from '@/components/BaseInput.vue'
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import AuthService from '@/services/AuthService'

const authSvc = new AuthService()
const router = useRouter()

let isShowPw = ref(false)
let isShowConfirmPw = ref(false)
let selectedRoleId = ref(0)
let role = ref('public')

let isLoading = ref(false)
let setErrors = ref([])

const showedPassword = () => {
  isShowPw.value = !isShowPw.value
}

const showedConfirmPassword = () => {
  isShowConfirmPw.value = !isShowConfirmPw.value
}

const roles = [
  {
    value: 'masyarakat',
    title: 'Masyarakat',
  },
  {
    value: 'administrator',
    title: 'Administrator',
  },
]

const clickedRole = (id) => {
  selectedRoleId.value = id
  role.value = roles[id].value
}
const userData = reactive({
  nama_lengkap: '',
  username: '',
  email: '',
  telp: '',
  password: '',
  confirm_password: '',
})

const submitData = async () => {
  const { nama_lengkap, username, email, telp, password, confirm_password } = userData
  try {
    isLoading.value = true

    const response = await authSvc.register(
      role.value,
      nama_lengkap,
      username,
      email,
      telp,
      password,
      confirm_password,
    )

    if (response.status === 200) {
      window.location.href = '/login'
    } else {
      throw response
    }
  } catch (error) {
    isLoading.value = false

    if (error.status === 422) {
      console.log(error.response.data.errors)
      setErrors.value = error.response.data.errors
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="register-page">
    <h1>Buat Akun Baru</h1>
    <p>Selamat Datang di LelangMudah</p>

    <form @submit.prevent="submitData">
      <div class="form-group">
        <label style="font-weight: 600">Daftar sebagai</label>
        <div class="roles-container">
          <button
            type="button"
            v-for="(role, index) in roles"
            :key="index"
            @click="clickedRole(index)"
            :class="index === selectedRoleId ? 'role-selected' : 'role-btn'"
          >
            <p>{{ role.title }}</p>
          </button>
        </div>
      </div>

      <div class="input-container">
        <div class="form-group">
          <BaseLabel :for="'fullname-input'" :value="'Nama lengkap'"></BaseLabel>
          <BaseInput
            :id="'fullname-input'"
            :type="'text'"
            :placeholder="'John Doe'"
            v-model="userData.nama_lengkap"
          ></BaseInput>
          <p class="error" v-if="setErrors.nama_lengkap" v-text="setErrors.nama_lengkap[0]"></p>
        </div>
        <div class="form-group">
          <BaseLabel :for="'username-input'" :value="'Username'"></BaseLabel>
          <BaseInput
            :id="'username-input'"
            :type="'text'"
            :placeholder="'Johndoe123'"
            v-model="userData.username"
          ></BaseInput>
          <p class="error" v-if="setErrors.username" v-text="setErrors.username[0]"></p>
        </div>
      </div>
      <div class="form-group" v-if="role === 'masyarakat'">
        <BaseLabel :for="'email-input'" :value="'Email'"></BaseLabel>
        <BaseInput :id="'email-input'" :type="'email'" :placeholder="'Email'" v-model="userData.email"></BaseInput>
        <p class="error" v-if="setErrors.email" v-text="setErrors.email[0]"></p>
      </div>
      <div class="form-group">
        <BaseLabel :for="'phone-input'" :value="'Nomor Telepon'"></BaseLabel>
        <BaseInput
          :id="'phone-input'"
          :type="'tel'"
          :placeholder="'081234567890'"
          v-model="userData.telp"
        ></BaseInput>
        <p class="error" v-if="setErrors.telp" v-text="setErrors.telp[0]"></p>
      </div>

      <div class="form-group">
        <BaseLabel :for="'password-input'" :value="'Password'"></BaseLabel>
        <div class="form-group-password">
          <BaseInput
            :id="'password-input'"
            :type="isShowPw ? 'text' : 'password'"
            :placeholder="'Password'"
            v-model="userData.password"
          ></BaseInput>
          <button type="button" id="toggle-password" @click="showedPassword">
            <div v-if="isShowPw">
              <IconEyeOn></IconEyeOn>
            </div>
            <div v-else>
              <IconEyeOff></IconEyeOff>
            </div>
          </button>
        </div>
        <p class="error" v-if="setErrors.password" v-text="setErrors.password[0]"></p>
      </div>
      <div class="form-group" style="margin-bottom: 8px">
        <BaseLabel :for="'confirm-password-input'" :value="'Konfirmasi password'"></BaseLabel>
        <div class="form-group-password">
          <BaseInput
            :id="'confirm-password-input'"
            :type="isShowConfirmPw ? 'text' : 'password'"
            :placeholder="'Konfirmasi password'"
            v-model="userData.confirm_password"
          ></BaseInput>
          <button type="button" id="toggle-password" @click="showedConfirmPassword">
            <div v-if="isShowConfirmPw">
              <IconEyeOn></IconEyeOn>
            </div>
            <div v-else>
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
      <BaseButton type="submit" :disabled="isLoading" :class="isLoading ? 'btn-disabled' : ''">
        <template #btn-content>
          <p>Daftar</p>
        </template>
      </BaseButton>
    </form>
    <p class="nav-link">
      Sudah punya akun? <router-link :to="{ name: 'login' }">Masuk</router-link>
    </p>
  </div>
</template>

<style scoped>
.register-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  flex-direction: column;
}

.register-page h1 {
  color: black;
  font-weight: 600;
  font-size: clamp(26px, 1vw, 42px);
  text-align: center;
  margin-bottom: 0.2rem;
}

form {
  margin-top: 1rem;
  max-width: 400px;
  padding: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  gap: 10px;
}

.input-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

.form-group {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: start;
  row-gap: 5px;
  width: 100%;
}

.form-group-password {
  width: 100%;
  border-radius: 10px;
  border: 1px solid #cccccc;
  display: flex;
  justify-content: center;
  align-items: center;
  padding-inline: 10px;
}

.form-group-password input {
  border: none;
  outline: none;
  margin-right: 5px;
  padding-inline: 0px;
}

.form-group-password button {
  background-color: transparent;
  border: none;
  display: flex;
  justify-content: center;
}

.roles-container {
  width: 100%;
  gap: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #e2e2e2cc;
  border-radius: 8px;
  padding: 2px;
}

.role-btn {
  padding-block: 10px;
  border-radius: 8px;
  width: 100%;
  background: transparent;
  border: none;
}

.role-selected {
  padding-block: 10px;
  border-radius: 8px;
  width: 100%;
  border: none;
  background: white;
  color: #0bbd0b;
  font-weight: 600;
}

.btn-disabled {
  background-color: #3b3b3b !important;
}

.btn-disabled:hover {
  background-color: #3b3b3b !important;
}
</style>
