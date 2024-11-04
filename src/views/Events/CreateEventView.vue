<template>
    <main>
        <h1 class="title">Create event</h1>

        <form @submit.prevent="handleSubmit" class="w-full mx-auto mb-6">
            <div class="w-full bg-white border border-gray-200 rounded-lg shadow space-y-6 p-6">
                <div>
                    <strong>Name</strong>
                    <input type="text" placeholder="Name" v-model="formData.name">
                    <p v-if="errors.name" class="error">{{ errors.name[0] }}</p>
                </div>
                <div>
                    <strong>Description</strong>
                    <textarea rows="6" placeholder="Description" v-model="formData.description"></textarea>
                    <p v-if="errors.description" class="error">{{ errors.description[0] }}</p>
                </div>
                <div>
                    <strong>Status</strong>
                    <select v-model="formData.status" class="w-full border rounded bg-white p-1">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                    <p v-if="errors.status" class="error">{{ errors.status[0] }}</p>
                </div>
                <div>
                    <div>
                        <strong>Address</strong>
                        <input type="text" placeholder="Address" v-model="formData.address">
                        <p v-if="errors.address" class="error">{{ errors.address[0] }}</p>
                    </div>
                    <div class="grid grid-cols-3 gap-6 mt-6">
                        <div>
                            <strong>Country</strong>
                            <select v-model="formData.country" class="w-full border rounded bg-white p-1">
                                <option value="US">United States</option>
                                <option value="TN">Tunisia</option>
                            </select>
                            <p v-if="errors.country" class="error">{{ errors.country[0] }}</p>
                        </div>
                        <div>
                            <strong>City</strong>
                            <input type="text" placeholder="City" v-model="formData.city">
                            <p v-if="errors.city" class="error">{{ errors.city[0] }}</p>
                        </div>
                        <div>
                            <strong>Postal code</strong>
                            <input type="text" placeholder="Zip" v-model="formData.postal_code">
                            <p v-if="errors.postal_code" class="error">{{ errors.postal_code[0] }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
            <div>
                <strong>Start date</strong>
                <input id="event-start-at" type="datetime-local" v-model="formData.start_at" class="w-full border rounded p-1">
                <p v-if="errors.start_at" class="error">{{ errors.start_at[0] }}</p>
            </div>
            <div>
                <strong>End date</strong>
                <input id="event-end-at" type="datetime-local" v-model="formData.end_at"
                       class="w-full border rounded p-1">
                <p v-if="errors.end_at" class="error">{{ errors.end_at[0] }}</p>
            </div>
        </div>
            </div>

            <div class="w-full bg-white border border-gray-200 rounded-lg shadow space-y-6 p-6 my-6">
                <TicketTypes :ticketTypes="ticketTypes" @update:ticketTypes="updateTicketTypes" @validate="handleValidation" />
            </div>

            <button class="primary-btn">Create</button>
        </form>
    </main>
</template>

<script setup>
import {onMounted, reactive, ref, watch} from 'vue';
import { useEventsStore } from '@/stores/events.js';
import { storeToRefs } from 'pinia';
import TicketTypes from '@/components/TicketTypes.vue';
import {watchStartEndDates} from "@/utils/watchStartEndDates.js";

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
const { errors } = storeToRefs(useEventsStore());
const { createEvent } = useEventsStore();
const ticketTypes = ref([]);
let hasValidationErrors = ref(false);

onMounted(() => {
    const date = new Date();
    const format = 'Y-m-d\\TH:i';

    document.querySelector('#event-start-at').min = date.format(format);
    document.querySelector('#event-end-at').min = date.format(format);
})

watch(() => formData.start_at, watchStartEndDates(formData));

const handleSubmit = () => {
    if (validateEvent()) {
        return;
    }

    if (!ticketTypes.value.length) {
        alert('Add at least one ticket type.');
        return;
    }

    if (hasValidationErrors.value) {
        alert('Please fix the ticket errors.');
        return;
    }

    createEvent(formData, ticketTypes.value);
};

const validateEvent = () => {
    let hasErrors = false;

    if (formData.name === '') {
        errors.value.name = ['The name field is required.'];
        hasErrors = true;
    }

    if (formData.description === '') {
        errors.value.description = ['The description field is required.'];
        hasErrors = true;
    }

    if (formData.address === '') {
        errors.value.address = ['The address field is required.'];
        hasErrors = true;
    }

    if (formData.city === '') {
        errors.value.city = ['The city field is required.'];
        hasErrors = true;
    }

    if (formData.postal_code === '') {
        errors.value.postal_code = ['The postal code field is required.'];
        hasErrors = true;
    }

    if (formData.start_at === '') {
        errors.value.start_at = ['The start date field is required.'];
        hasErrors = true;
    }

    if (formData.end_at === '') {
        errors.value.end_at = ['The end date field is required.'];
        hasErrors = true;
    }

    return hasErrors;
}

const handleValidation = (hasErrors) => {
    hasValidationErrors.value = hasErrors;
};

const updateTicketTypes = (newTicketTypes) => {
    ticketTypes.value = newTicketTypes;
};
</script>