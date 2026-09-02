<script setup lang="js">
import BaseButton from '@/components/BaseButton.vue'
import BaseLogo from '@/components/BaseLogo.vue'
import AuthService from '@/services/AuthService'
import bDropdown from '@/components/dropdown/b-dropdown.vue'
import bDropdownItem from '@/components/dropdown/b-dropdown-item.vue'
import { useRoute } from 'vue-router'

import BaseAlert from '@/components/BaseAlert.vue'
import { ref } from 'vue'

let showAlert = ref(false)
let alertMessage = ref('')
let alertType = ref('')

const closeAlert = () => {
  showAlert.value = false
}

const route = useRoute()

const user = JSON.parse(localStorage.getItem('user'))

const isActiveRoute = (routeName) => {
  return route.name == routeName
}

const authSvc = new AuthService()

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: true,
  },
})

const handleLogout = async () => {
  const user = JSON.parse(localStorage.getItem('user'))

  showAlert.value = true
  alertMessage.value = 'Sedang logout, silahkan tunggu sebentar...'
  alertType.value = 'info'
  setTimeout(() => {
    showAlert.value = false
  }, 5000)
  try {
    const response = authSvc.logout(user.username, user.role)
    if (response.status === 200) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  } catch (error) {
    showAlert.value = true
    alertMessage.value = 'Gagal logout, silahkan coba lagi!'
    alertType.value = 'error'
    setTimeout(() => {
      showAlert.value = false
    }, 5000)
    console.error(error)
  }
}
</script>

<template>
  <div class="aside-wrapper" :class="props.isOpen ? 'aside-active' : 'aside-nonactive'">
    <!-- Alert component -->
    <BaseAlert :is-show="showAlert" :message="alertMessage" :type="alertType" @close="closeAlert">
    </BaseAlert>
    <BaseLogo :variants="'secondary'"></BaseLogo>
    <section class="container" id="first-menu">
      <ul class="nav-container">
        <li :class="{ 'active-route': isActiveRoute('dashboardView') }">
          <router-link :to="{ name: 'dashboardView' }">Dashboard</router-link>
        </li>
      </ul>
    </section>
    <b-dropdown :dropdown-value="'Lelang'">
      <template #dropdown-items>
        <b-dropdown-item
          :target="'assetAuctions'"
          :value="'Asset lelang'"
          v-if="user.role === 'administrator' || user.role === 'petugas'"
        ></b-dropdown-item>
        <b-dropdown-item
          :target="'liveAuctions'"
          :value="'Lelang berlangsung'"
          v-if="user.role === 'administrator' || user.role === 'petugas'"
        ></b-dropdown-item>
        <b-dropdown-item
          :target="'auctionsHistory'"
          :value="'Riwayat penawaran saya'"
          v-if="user.role === 'masyarakat'"
        ></b-dropdown-item>
      </template>
    </b-dropdown>
    <b-dropdown :dropdown-value="'Petugas'" v-if="user.role === 'administrator'">
      <template #dropdown-items>
        <b-dropdown-item :target="'officers'" :value="'Kelola Petugas'"></b-dropdown-item>
      </template>
    </b-dropdown>
    <b-dropdown
      :dropdown-value="'Laporan'"
      v-if="user.role === 'administrator' || user.role === 'petugas'"
    >
      <template #dropdown-items>
        <b-dropdown-item :target="'auctionReports'" :value="'Laporan lelang'"></b-dropdown-item>
      </template>
    </b-dropdown>
    <BaseButton type="button" id="btn-logout" :variant="'danger'" @click="handleLogout"
      ><template #btn-content>
        <p>Logout</p>
      </template>
    </BaseButton>
    <slot></slot>
  </div>
</template>

<style lang="css" scoped>
section.container {
  display: flex;
  justify-content: center;
  align-items: start;
  flex-direction: column;
  margin-block: 0.2rem;
  width: 100%;
}

section#first-menu {
  margin-top: 1.5rem;
}

section.container h3 {
  font-size: small;
  color: #e4e4e4;
  font-weight: 700;
}

section.container .nav-container {
  width: 100%;
  display: block;
  margin-block: 0.4rem;
  list-style-type: none;
}
section.container .nav-container li {
  width: 100%;
  padding: 0.5rem;
  border-radius: 8px;
  display: block;
}

section.container .nav-container li:hover {
  background-color: #10ed10a7;
}

section.container .nav-container a {
  text-decoration: none;
  color: white;
  font-weight: 400;
  font-size: small;
  text-align: start;
}

.active-route {
  background-color: #10ed10a7;
}

#btn-logout {
  padding: 8px;
  font-size: small;
  margin-top: 1.7rem;
}

.aside-nonactive {
  display: none;
}

.aside-active {
  display: flex;
}

.aside-wrapper {
  justify-content: start;
  flex-direction: column;
  align-items: start;
  padding: 1.5rem 1rem;
  height: 100%;
  background-color: var(--primary-color);
}
</style>
