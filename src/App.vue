<template>
  <header>
    <div class="wrapper">
      <nav>
        <RouterLink to="/" class="nav-link">Home</RouterLink>

        <div v-if="authStore.user">
          <div class="inline-flex items-center space-x-6">
            <RouterLink :to="{name: 'myEvents'}" class="nav-link">My Events</RouterLink>
            <RouterLink :to="{name: 'createEvent'}" class="nav-link">New Event</RouterLink>

            <form @submit.prevent="authStore.logout">
              <button class="nav-link">Logout</button>
            </form>

            <span class="text-white">Hi <strong>{{ authStore.user.name }}</strong>!</span>
          </div>
        </div>
        <div v-else class="space-x-6">
          <RouterLink to="/login" class="nav-link">Login</RouterLink>
          <RouterLink to="/register" class="nav-link">Register</RouterLink>
        </div>
      </nav>
    </div>
  </header>

  <main class="mt-6">
      <div class="w-full bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
        <div class="flex">
          <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
          <div>
            <p class="font-bold">Please think of this as an MVP (Minimum viable product)!</p>
          </div>
        </div>
      </div>
  </main>

  <RouterView />
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router'
import {useAuthStore} from "@/stores/auth.js";

const authStore = useAuthStore();
</script>
