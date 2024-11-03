<template>
    <main>
        <div v-if="event">
            <div class="border-l-4 border-blue-500 pl-4 mt-12">
                <h1 class="font-bold text-3xl">{{ event.name }}</h1>
                <p class="text-xs text-slate-600 my-4">
                    Created by <strong>{{ event.user.name }}</strong><br>
                    Status <strong>{{ event.status }}</strong><br>
                    Starts <strong>{{ formatDateTime(event.start_at) }}</strong><br>
                    Ends <strong>{{ formatDateTime(event.end_at) }}</strong>
                </p>
                <p>
                    {{ event.description }}
                </p>
            </div>

            <div v-if="ticketTypes.length" class="mt-12">
                <h3 class="font-bold text-xl">Ticket types</h3>

                <div class="grid grid-cols-4 gap-7 mt-6">
                    <div v-for="ticketType in ticketTypes" class="border-l-4 border-blue-500 pl-4 mt-6">
                        <strong>{{ ticketType.name }}</strong>
                        <p>Price: ${{ ticketType.price }}</p>
                        <p>Quantity: {{ ticketType.quantity }}</p>
                        <p>Max tickets per person: {{ ticketType.max_quantity_per_person }}</p>
                        <button class="primary-btn mt-3" @click.prevent="reserveTicket">Reserve</button>
                    </div>
                </div>
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

const route = useRoute()
const {getEvent, getTicketTypes} = useEventsStore();
const event = ref(null)
const ticketTypes = ref([])

onMounted(async () => {
    event.value = await getEvent(route.params.id);

    if (event.value) {
        ticketTypes.value = await getTicketTypes(event.value.id);
    }
})

const formatDateTime = (date) => {
    return new Date(date).toLocaleString()
}

const reserveTicket = () => {
    alert('Coming Soon!')
}
</script>