<script setup lang="js">
import { ref } from 'vue'
import BaseButton from './BaseButton.vue'
import BaseLogo from './BaseLogo.vue'
import { useRouter } from 'vue-router'
import UserProfile from './UserProfile.vue'
const router = useRouter()

const isLoggedUser = localStorage.getItem('user')

let isOpen = ref(false)

const openedMenu = () => {
  isOpen.value = !isOpen.value
}

const moveToLogin = () => {
  router.push({ name: 'login' })
}
const moveToRegister = () => {
  router.push({ name: 'register' })
}
</script>

<template>
  <header>
    <div class="wrapper">
      <BaseLogo></BaseLogo>

      <div id="hamburger-menu">
        <button id="hamburger-btn" @click="openedMenu">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <nav :style="isOpen == true ? 'display:flex;' : ''">
        <ul>
          <li><router-link class="nav-link" :to="{ name: 'home' }">Beranda</router-link></li>
        </ul>
        <div class="container" v-if="!isLoggedUser">
          <BaseButton id="btn-login" :variant="'primary-bordered'" @click="moveToLogin">
            <template #btn-content>
              <p>Masuk</p>
            </template>
          </BaseButton>
          <BaseButton id="btn-register" :variant="'primary'" @click="moveToRegister">
            <template #btn-content>
              <p>Daftar</p>
            </template>
          </BaseButton>
        </div>
        <div class="container" v-else>
          <UserProfile></UserProfile>
        </div>
      </nav>
    </div>
  </header>
</template>

<style scoped>
header {
  position: fixed;
  top: 0;
  border-bottom: 1px solid #e5e5e5;
  background: white;
  z-index: 100;
}

.wrapper {
  display: flex;
  width: 100vw;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  position: relative;
}

nav {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 2rem;
  /* flex-direction: column; */
  /* justify-content: ; */
}

nav ul {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  list-style-type: none;
  gap: 0.6rem;
}

.nav-link {
  color: #696969;
  text-decoration: none;
  transition: all ease-in-out 0.3s;
  font-weight: 500;
}

.nav-link:hover {
  color: #0bbd0b;
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.6rem;
}

#hamburger-menu {
  display: none;
}

#hamburger-menu button {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: transparent;
  border: none;
  gap: 4px;
}

#hamburger-menu span {
  background: white;
  width: 25px;
  height: 2px;
  border-radius: 8px;
}

@media (max-width: 768px) {
  #hamburger-menu {
    display: block;
  }

  header {
    background: #0bbd0b;
    /* border: 1px solid black; */
  }

  .logo h1 {
    color: white;
  }

  nav {
    display: none;
    padding: 2rem;
    background: #0bbd0b;
    flex-direction: column;
    top: 100%;
    left: 0;
    position: absolute;
    width: 100%;
    transition: all ease-in-out 0.3s;
  }

  nav ul {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  .nav-link {
    color: white;
  }

  .nav-link:hover {
    color: white;
  }

  nav .container {
    width: 100%;
    display: flex;
    flex-direction: column;
  }

  nav .container button {
    width: 100% !important;
    background-color: white !important;
    color: #0bbd0b;
  }

  nav .container button:hover {
    background-color: #d0d0d0c3 !important;
    color: white !important;
  }
}
</style>
