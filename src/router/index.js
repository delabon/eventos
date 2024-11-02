import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from "@/views/Auth/RegisterView.vue";
import LoginView from "@/views/Auth/LoginView.vue";
import {useAuthStore} from "@/stores/auth.js";
import CreateEventView from "@/views/Events/CreateEventView.vue";
import ShowEventView from "@/views/Events/ShowEventView.vue";
import UpdateEventView from "@/views/Events/UpdateEventView.vue";
import MyEventsView from "@/views/Events/MyEventsView.vue";

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
      meta: {
        auth: true
      }
    },
    {
      path: '/events/:id',
      name: 'showEvent',
      component: ShowEventView,
    },
    {
      path: '/events/edit/:id',
      name: 'updateEvent',
      component: UpdateEventView,
      meta: {
        auth: true
      }
    },
    {
      path: '/my-events',
      name: 'myEvents',
      component: MyEventsView,
      meta: {
        auth: true
      }
    },
  ]
})

router.beforeEach(async (to) => {
  const authStore = useAuthStore();
  await authStore.getUser()

  if (to.meta.guest) {
    if (authStore.user) {
      return {
        name: 'home'
      }
    }
  } else if (to.meta.auth) {
    if (!authStore.user) {
      return {
        name: 'login'
      }
    }
  }
})

export default router
