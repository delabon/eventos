import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from "@/views/Auth/RegisterView.vue";
import LoginView from "@/views/Auth/LoginView.vue";
import {useAuthStore} from "@/stores/auth.js";
import CreateEventView from "@/views/Events/CreateEventView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: {
        guest: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {
        guest: true
      }
    },
    {
      path: '/events/create',
      name: 'createEvent',
      component: CreateEventView,
    },
  ]
})

router.beforeEach(async (to) => {
  if (to.meta.guest)  {
    const authStore = useAuthStore();
    await authStore.getUser()

    if (authStore.user) {
      return {
        name: 'home'
      }
    }
  }
})

export default router
