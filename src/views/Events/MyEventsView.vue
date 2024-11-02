<template>
  <main>
    <h1 class="title">My events</h1>

    <div v-if="events.length">
      <div v-for="event in events" :key="event.id" class="border-l-4 border-blue-500 pl-4 mb-8">
        <h2 class="font-bold text-xl">{{ event.name }}</h2>
        <p class="text-xs text-slate-600 mb-4">
          Created by <strong>{{ event.user.name }}</strong>
        </p>
        <p>
          {{ event.description }}
          <br>
          <RouterLink :to="{name: 'showEvent', params: {id: event.id}}" class="text-blue-500 underline">Go to event</RouterLink>
        </p>

        <div class="flex items-center space-x-4 mt-4">
          <form v-if="authStore.user && authStore.user.id === event.user_id" @submit.prevent="deleteEvent(event)">
            <button class="border border-red-500 text-red-500 px-4 py-2 hover:bg-red-500 hover:text-white" type="submit">Delete</button>
          </form>

          <RouterLink v-if="authStore.user && authStore.user.id === event.user_id" :to="{ name: 'updateEvent', params: {id: event.id} }" class="border border-green-500 text-green-500 px-4 py-2 hover:bg-green-500 hover:text-white">Update</RouterLink>
        </div>
      </div>
    </div>
    <div v-else>
      <h2>There are no events.</h2>
    </div>
  </main>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {RouterLink} from "vue-router";
import {useEventsStore} from "@/stores/events.js";
import {useAuthStore} from "@/stores/auth.js";

const authStore = useAuthStore()
const {getMyEvents, deleteEvent} = useEventsStore();
const events = ref([])

onMounted(async () => {
  events.value = await getMyEvents()
})
</script>