<template>
  <main>
    <h1 class="title">My events</h1>

    <table v-if="events.length" class="w-full border-collapse">
      <thead>
        <tr>
          <th class="border-b border-gray-300 p-2">Name</th>
          <th class="border-b border-gray-300 p-2">Author</th>
          <th class="border-b border-gray-300 p-2">Status</th>
          <th class="border-b border-gray-300 p-2">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="event in events" :key="event.id">
          <td class="border-b border-gray-300 p-2">{{ event.name }}</td>
          <td class="border-b border-gray-300 p-2">{{ event.user.name }}</td>
          <td class="border-b border-gray-300 p-2">{{ event.status }}</td>
          <td class="border-b border-gray-300 p-2">
            <div class="flex items-center justify-end space-x-4 mt-4">
              <RouterLink :to="{name: 'showEvent', params: {id: event.id}}" class="border border-blue-500 text-blue-500 px-3 py-1 hover:bg-blue-500 hover:text-white">Show</RouterLink>

              <RouterLink v-if="authStore.user && authStore.user.id === event.user_id" :to="{ name: 'updateEvent', params: {id: event.id} }" class="border border-green-500 text-green-500 px-3 py-1 hover:bg-green-500 hover:text-white">Edit</RouterLink>

              <form v-if="authStore.user && authStore.user.id === event.user_id" @submit.prevent="deleteEvent(event)">
                <button class="border border-red-500 text-red-500 px-3 py-1 hover:bg-red-500 hover:text-white" type="submit">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

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