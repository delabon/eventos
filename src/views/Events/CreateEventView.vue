<template>
    <main>
        <h1 class="title">Create event</h1>

        <EventForm :formData="formData" :ticketTypes="ticketTypes" @submit="handleSubmit" @update:ticketTypes="updateTicketTypes"/>
    </main>
</template>

<script setup>
import {reactive, ref} from 'vue';
import { useEventsStore } from '@/stores/events.js';
import EventForm from "@/components/EventForm.vue";

const { createEvent } = useEventsStore();
const formData = reactive({
    name: '',
    description: '',
    status: 'draft',
    country: 'US',
    address: '',
    city: '',
    postal_code: '',
    start_at: '',
    end_at: ''
});
const ticketTypes = ref([]);

const handleSubmit = (formData) => {
    createEvent(formData, ticketTypes.value);
};

const updateTicketTypes = (newTicketTypes) => {
    ticketTypes.value = newTicketTypes;
};

</script>