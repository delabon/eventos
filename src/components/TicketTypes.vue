<template>
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold">Ticket types</h3>
            <button class="default-btn" @click.prevent="addTicketType">Add type</button>
        </div>
        <div id="ticket-types">
            <div v-for="(ticketType, index) in ticketTypes" :key="index"
                 class="mb-4 border border-gray-300 p-3 rounded">
                <div class="flex items-center justify-between mb-3">
                    <strong>{{ ticketType.name }}</strong>
                    <button class="font-bold text-lg" @click.prevent="removeTicketType(index)">x</button>
                </div>
                <label class="mb-2">
                    Ticket name
                    <input type="text" v-model="ticketType.name" placeholder="Ticket Name"
                           class="w-full border rounded p-1 mb-2">
                    <p v-if="ticketType.errors.name" class="error">{{ ticketType.errors.name }}</p>
                </label>
                <div class="grid grid-cols-3 gap-4 mt-3">
                    <label class="mb-2">
                        Price
                        <input type="number" v-model="ticketType.price" placeholder="Price"
                               class="w-full border rounded p-1 mb-2">
                        <p v-if="ticketType.errors.price" class="error">{{ ticketType.errors.price }}</p>
                    </label>
                    <label class="mb-2">
                        Quantity
                        <input type="number" v-model="ticketType.quantity" placeholder="Quantity"
                               class="w-full border rounded p-1 mb-2">
                        <p v-if="ticketType.errors.quantity" class="error">{{ ticketType.errors.quantity }}</p>
                    </label>
                    <label>
                        Max quantity per person
                        <input type="number" v-model="ticketType.max_quantity_per_person"
                               placeholder="Max quantity per person" class="w-full border rounded p-1 mb-2">
                        <p v-if="ticketType.errors.max_quantity_per_person" class="error">{{ ticketType.errors.max_quantity_per_person }}</p>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { watchEffect } from 'vue';

const props = defineProps({
    ticketTypes: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['update:ticketTypes', 'validate']);

const addTicketType = () => {
    emit('update:ticketTypes', [
        ...props.ticketTypes,
        {
            id: 0,
            name: 'Free',
            price: 0,
            quantity: 50,
            max_quantity_per_person: 5,
            errors: {}
        }
    ]);
};

const removeTicketType = (index) => {
    emit('update:ticketTypes', props.ticketTypes.filter((_, i) => i !== index));
};

const validateTicketTypes = () => {
    let hasErrors = false;

    props.ticketTypes.forEach((item) => {
        item.errors = {};

        if (item.name === '') {
            item.errors['name'] = 'The name field is required.';
            hasErrors = true;
        }

        if (typeof item.price !== 'number' || item.price < 0) {
            item.errors['price'] = 'The price field is invalid.';
            hasErrors = true;
        }

        if (typeof item.quantity !== 'number' || item.quantity < 0) {
            item.errors['quantity'] = 'The quantity field is invalid.';
            hasErrors = true;
        }

        if (typeof item.max_quantity_per_person !== 'number' || item.max_quantity_per_person < 0) {
            item.errors['max_quantity_per_person'] = 'The max quantity per person field is invalid.';
            hasErrors = true;
        }
    });

    emit('validate', hasErrors);
};

watchEffect(validateTicketTypes);
</script>
