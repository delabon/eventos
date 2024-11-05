<template>
    <main>
        <h1 class="title">Latest events</h1>

        <div v-if="events.length">
            <div v-for="event in events" :key="event.id" class="border-l-4 border-black pl-4 mb-8">
                <h2 class="font-bold text-xl">{{ event.name }}</h2>
                <p class="text-xs text-slate-600 mb-4">
                    Created by <strong>{{ event.user.name }}</strong><br>
                    Status <strong>{{ event.status }}</strong>
                </p>
                <p>
                    {{ event.description }}
                    <br>
                    <RouterLink :to="{name: 'showEvent', params: {id: event.id}}" class="text-blue-500 underline">Go to
                        event
                    </RouterLink>
                </p>
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

const {getEvents} = useEventsStore();
const events = ref([])

onMounted(async () => {
    events.value = await getEvents()
})
</script>