<template>
  <main>
    <div v-if="event">
      <div class="border-l-4 border-blue-500 pl-4 mt-12">
        <h2 class="font-bold text-xl">{{ event.name }}</h2>
        <p class="text-xs text-slate-600 mb-4">
          Created by <strong>{{ event.user.name }}</strong>
        </p>
        <p>
          {{ event.description }}
        </p>
      </div>
    </div>
    <div v-else>
      <h2>Event does not exist.</h2>
    </div>
  </main>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {useEventsStore} from "@/stores/events.js";
import {useRoute} from "vue-router";
import {useAuthStore} from "@/stores/auth.js";

const route = useRoute()
const authStore = useAuthStore()
const {getEvent, deleteEvent} = useEventsStore();
const event = ref(null)

onMounted(async () => {
  event.value = await getEvent(route.params.id)
})
</script>

<style scoped>

</style>