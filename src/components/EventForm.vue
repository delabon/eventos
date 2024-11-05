<template>
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
                <strong>Cover image</strong>
                <input type="file" @change="handleCoverImage" class="w-full border rounded bg-white p-1" accept="image/*">
                <input type="hidden" v-model="formData.cover_image" id="event-cover-image">
                <div v-if="formData.cover_image" class="mt-2">
                    <img :src="formData.cover_image" width="250" height="auto" id="event-cover-image-preview">
                    <div class="mt-2">
                        <button class="danger-btn" @click.prevent="handleDeleteCoverImage">Delete</button>
                    </div>
                </div>
                <p v-if="errors.cover_image" class="error">{{ errors.cover_image[0] }}</p>
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

        <button class="primary-btn">Save</button>
    </form>
</template>

<script setup>
import TicketTypes from "@/components/TicketTypes.vue";
import {onMounted, ref, watch} from "vue";
import {storeToRefs} from "pinia";
import {useEventsStore} from "@/stores/events.js";
import {watchStartEndDates} from "@/utils/watchStartEndDates.js";

const props = defineProps({
    formData: Object,
    ticketTypes: Array,
})
const emits = defineEmits([
    'submit',
    'update:ticketTypes',
    'update:coverImage',
]);
let hasValidationErrors = ref(false);
const { errors } = storeToRefs(useEventsStore());

onMounted(() => {
    const date = new Date();
    const format = 'Y-m-d\\TH:i';

    document.querySelector('#event-start-at').min = date.format(format);
    document.querySelector('#event-end-at').min = date.format(format);
})

watch(() => props.formData.start_at, watchStartEndDates(props.formData));

const handleSubmit = () => {
    if (validateEvent()) {
        return;
    }

    if (!props.ticketTypes.length) {
        alert('Add at least one ticket type.');
        return;
    }

    if (hasValidationErrors.value) {
        alert('Please fix the ticket errors.');
        return;
    }

    emits('submit', props.formData, errors)
};

const validateEvent = () => {
    let hasErrors = false;

    if (props.formData.name === '') {
        errors.value.name = ['The name field is required.'];
        hasErrors = true;
    }

    if (props.formData.description === '') {
        errors.value.description = ['The description field is required.'];
        hasErrors = true;
    }

    if (props.formData.address === '') {
        errors.value.address = ['The address field is required.'];
        hasErrors = true;
    }

    if (props.formData.city === '') {
        errors.value.city = ['The city field is required.'];
        hasErrors = true;
    }

    if (props.formData.postal_code === '') {
        errors.value.postal_code = ['The postal code field is required.'];
        hasErrors = true;
    }

    if (props.formData.start_at === '') {
        errors.value.start_at = ['The start date field is required.'];
        hasErrors = true;
    }

    if (props.formData.end_at === '') {
        errors.value.end_at = ['The end date field is required.'];
        hasErrors = true;
    }

    return hasErrors;
};

const handleValidation = (hasErrors) => {
    hasValidationErrors.value = hasErrors;
};

const updateTicketTypes = (newTicketTypes) => {
    emits('update:ticketTypes', newTicketTypes);
};

const handleCoverImage = (event) => {
    const file = event.target.files[0];

    if (validateFile(file)) {
        const reader = new FileReader();
        reader.onload = (e) => {
            emits('update:coverImage', e.target.result)
        };
        reader.readAsDataURL(file);
    } else {
        emits('update:coverImage', null)
    }
};

const validateFile = (file) => {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!allowedTypes.includes(file.type)) {
        errors.value.cover_image = ['Invalid file type. Only JPEG, PNG, and GIF are allowed.'];
        return false;
    }

    if (file.size > maxSize) {
        errors.value.cover_image = ['File size exceeds the maximum limit of 2MB.'];
        return false;
    }

    errors.value.cover_image = [];

    return true;
};

const handleDeleteCoverImage = () => {
    if (!confirm('This action cannot be undone. Are you sure?')) {
        return;
    }

    document.querySelector('input[type="file"]').value = '';
    emits('update:coverImage', null)
}
</script>