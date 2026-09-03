<script setup lang="js">
import BaseButton from '@/components/BaseButton.vue'
import BaseInput from '@/components/BaseInput.vue'
import BaseLabel from '@/components/BaseLabel.vue'
import IconEyeOff from '@/components/icons/IconEyeOff.vue'
import IconEyeOn from '@/components/icons/IconEyeOn.vue'
import { reactive, ref } from 'vue'
import AuthService from '@/services/AuthService'
import { useRouter } from 'vue-router'
import BaseLayout from '../templates/BaseLayout.vue'

let isShow = ref(false)
const authSvc = new AuthService()
const router = useRouter()

const changeShowPw = () => {
  isShow.value = !isShow.value
}

let selectedRoleId = ref(0)
let role = ref('masyarakat')

let isLoading = ref(false)
let setErrors = ref([])

const roles = [
  {
    value: 'masyarakat',
    title: 'Masyarakat',
  },
  {
    value: 'administrator',
    title: 'Administrator',
  },
  {
    value: 'petugas',
    title: 'Petugas',
  },
]
const clickedRole = (id) => {
  selectedRoleId.value = id
  role.value = roles[id].value
}

const userData = reactive({
  username: '',
  password: '',
  isRememberMe: false,
})

const submitData = async () => {
  const { username, password, isRememberMe } = userData

  try {
    isLoading.value = true
    const response = await authSvc.login(role.value, username, password, isRememberMe)
    console.log(role.value, username, password)
    if (response.status !== 200) {
      throw response
    }

    const data = response.data

    localStorage.setItem('token', data.token)
    localStorage.setItem(
      'user',
      JSON.stringify({ id: data.data.id, username: data.data.username, role: data.data.role }),
    )

    router.push({ name: 'dashboardView' })
  } catch (error) {
    isLoading.value = false
    if (error.status === 422) {
      console.log(error.response.data)
      setErrors.value = error.response.data.errors
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <BaseLayout>
    <template #main>
      <div class="login-page">
        <h1>Masuk Lagi</h1>
        <p>Masuk akun yang telah terdaftar</p>

        <form @submit.prevent="submitData">
          <div class="form-group">
            <label style="font-weight: 600">Masuk sebagai</label>
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

          <div class="form-group">
            <BaseLabel :for="'username-input'" :value="'Username'"></BaseLabel>
            <BaseInput
              :id="'username-input'"
              :type="'text'"
              :placeholder="'JohnDoe123'"
              v-model="userData.username"
              autocomplete="username"
            ></BaseInput>
            <p class="error" v-if="setErrors.username" v-text="setErrors.username"></p>
          </div>
          <div class="form-group">
            <BaseLabel :for="'password-input'" :value="'Password'"></BaseLabel>
            <div class="form-group-password">
              <BaseInput
                :id="'password-input'"
                :type="isShow ? 'text' : 'password'"
                autocomplete="current-password"
                :placeholder="'Password'"
                v-model="userData.password"
              ></BaseInput>

              <button type="button" id="toggle-password" @click="changeShowPw">
                <div v-if="isShow">
                  <IconEyeOn></IconEyeOn>
                </div>
                <div v-else>
                  <IconEyeOff></IconEyeOff>
                </div>
              </button>
            </div>
            <p class="error" v-if="setErrors.password" v-text="setErrors.password"></p>
          </div>

          <div class="form-group">
            <input type="checkbox" id="check-remember" v-model="userData.isRememberMe" />
            <label for="check-remember">Ingat saya</label>
          </div>

          <BaseButton type="submit" :disabled="isLoading" :class="isLoading ? 'btn-disabled' : ''">
            <template #btn-content>
              <p>Masuk</p>
            </template>
          </BaseButton>

          <p class="nav-link">
            Belum punya akun? <router-link :to="{ name: 'register' }">Daftar</router-link>
          </p>
        </form>
      </div>
    </template>
  </BaseLayout>
</template>

<style scoped>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  flex-direction: column;
  position: relative;
}

.login-page h1 {
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
  width: 100%;
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

.form-group:nth-child(4) {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  gap: 5px;
  margin-bottom: 8px;
}

.form-group:nth-child(4) label {
  color: #414141;
  font-size: small;
}
.form-group:nth-child(4) input[type='checkbox'] {
  width: 14px;
  height: 14px;
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
  opacity: 100%;
}
</style>
