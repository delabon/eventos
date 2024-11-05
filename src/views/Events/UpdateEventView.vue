<template>
    <main>
        <h1 class="title">Update event</h1>

        <EventForm :formData="formData" :ticketTypes="ticketTypes" @submit="handleSubmit" @update:ticketTypes="updateTicketTypes" @update:cover-image="updateCoverImage"/>
    </main>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import {useEventsStore} from "@/stores/events.js";
import {useRoute, useRouter} from "vue-router";
import {useAuthStore} from "@/stores/auth.js";
import EventForm from "@/components/EventForm.vue";

const route = useRoute()
const router = useRouter()
const formData = reactive({
    name: '',
    description: '',
    status: 'draft',
    country: 'US',
    address: '',
    city: '',
    postal_code: '',
    start_at: '',
    end_at: '',
    cover_image: null
});
const ticketTypes = ref([]);
const {updateEvent, getEvent, getTicketTypes} = useEventsStore();
const authStore = useAuthStore();
const eventData = ref({
    id: null,
    user_id: null,
})

function formatDateToLocalInput(date) {
    const d = new Date(date);
    const pad = (n) => n.toString().padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

onMounted(async () => {
    const eventObj = await getEvent(route.params.id);

    if (authStore.user.id !== eventObj.user_id) {
        router.push({name: 'home'})
        return
    }

    formData.name = eventObj.name;
    formData.description = eventObj.description;
    formData.status = eventObj.status;
    formData.country = eventObj.country;
    formData.address = eventObj.address;
    formData.city = eventObj.city;
    formData.postal_code = eventObj.postal_code;
    formData.start_at = formatDateToLocalInput(eventObj.start_at);
    formData.end_at = formatDateToLocalInput(eventObj.end_at);
    formData.cover_image = eventObj.cover_image;

    eventData.value.id = eventObj.id;
    eventData.value.user_id = eventObj.user_id;

    (await getTicketTypes(eventObj.id)).forEach((item) => {
        ticketTypes.value.push({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            max_quantity_per_person: item.max_quantity_per_person,
            errors: {}
        })
    })

    const format = 'Y-m-d\\TH:i';

    document.querySelector('#event-start-at').min = new Date().format(format);
    document.querySelector('#event-end-at').min = new Date(Date.parse(eventObj.start_at)).format(format);
})

const handleSubmit = () => {
    updateEvent({
        id: eventData.value.id,
        user_id: eventData.value.user_id,
    }, formData, ticketTypes.value)
};

const updateTicketTypes = (newTicketTypes) => {
    ticketTypes.value = newTicketTypes;
};

const updateCoverImage = (coverImage) => {
    formData.cover_image = coverImage;
};
</script>