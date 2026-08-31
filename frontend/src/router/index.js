import { createRouter, createWebHistory } from 'vue-router'
import RegisterView from '@/views/pages/RegisterView.vue'
import LoginView from '@/views/pages/LoginView.vue'
import HomeView from '@/views/pages/HomeView.vue'
import AssetAuctionView from '@/views/pages/auctions/AssetAuctionView.vue'
import DashboardView from '@/views/pages/DashboardView.vue'
import DetailAuctionView from '@/views/pages/auctions/DetailAuctionView.vue'
import LiveAuctionView from '@/views/pages/auctions/LiveAuctionView.vue'
import DetailLiveAuctionView from '@/views/pages/auctions/DetailLiveAuctionView.vue'
import FormAddAssetView from '@/views/pages/auctions/FormAddAssetView.vue'
import FormEditAssetView from '@/views/pages/auctions/FormEditAssetView.vue'
import PublicAuctionDetailsView from '@/views/pages/auctions/PublicAuctionDetailsView.vue'
import AuctionReportsView from '@/views/pages/reports/AuctionReportsView.vue'
import AuctionsHistoryView from '@/views/pages/auctions/AuctionsHistoryView.vue'
import OfficersView from '@/views/pages/officers/OfficersView.vue'
import OfficerAddFormView from '@/views/pages/officers/OfficerAddFormView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },

    {
      path: '/dashboard',
      name: 'dashboardView',
      component: DashboardView,
      meta: {
        role: ['administrator', 'petugas', 'masyarakat'],
        requiresAuth: true
      }
    },
    {
      path: '/auctions',
      name: 'assetAuctions',
      component: AssetAuctionView,
      meta: {
        role: ['administrator', 'petugas'],
        requiresAuth: true
      }
    },
    {
      path: '/auctions/add',
      name: 'formAddAsset',
      component: FormAddAssetView,
      meta: {
        role: ['petugas'],
        requiresAuth: true
      }
    },
    {
      path: '/auctions/edit/:id',
      name: 'formEditAsset',
      component: FormEditAssetView,
      meta: {
        role: ['petugas'],
        requiresAuth: true
      }
    },
    {
      path: '/auctions/detail/:id',
      name: 'detailAuction',
      component: DetailAuctionView,
      meta: {
        role: ['administrator', 'petugas'],
        requiresAuth: true
      }
    },
    {
      path: '/auctions/live',
      name: 'liveAuctions',
      component: LiveAuctionView,
      meta: {
        role: ['administrator', 'petugas'],
        requiresAuth: true
      }
    },
    {
      path: '/auctions/history',
      name: 'auctionsHistory',
      component: AuctionsHistoryView,
      meta: {
        role: ['masyarakat'],
        requiresAuth: true
      }
    },
    {
      path: '/auctions/live/:id',
      name: 'detailLiveAuction',
      component: DetailLiveAuctionView,
      meta: {
        role: ['administrator', 'petugas'],
        requiresAuth: true
      }
    },
    {
      path: '/auction-details/:id',
      name: 'publicAuctionDetails',
      component: PublicAuctionDetailsView,
    },
    {
      path: '/reports',
      name: 'auctionReports',
      component: AuctionReportsView,
      meta: {
        role: ['administrator', 'petugas'],
        requiresAuth: true
      }
    },
    {
      path: '/officers',
      name: 'officers',
      component: OfficersView,
      meta: {
        role: ['administrator'],
        requiresAuth: true
      }
    },
    {
      path: '/officers/add',
      name: 'officerAddForm',
      component: OfficerAddFormView,
      meta: {
        role: ['administrator'],
        requiresAuth: true
      }
    },
    {
      // Menangani route yang tidak ada
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      redirect: { name: 'home' }
      // component: NotFoundView
    },
  ],
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token")
  const user = JSON.parse(localStorage.getItem("user"))
  if (to.meta.requiresAuth && !token) {
    router.push({ name: 'login' })
  }
  else if (to.meta.role && !to.meta.role.includes(user.role)) {
    router.push({ name: 'home' })
  }
  else {
    next()
  }
});

export default router
