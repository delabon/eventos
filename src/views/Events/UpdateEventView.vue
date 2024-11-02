<template>
  <main>
    <h1 class="title">Update event</h1>

    <form @submit.prevent="updateEvent(eventData, formData)" class="w-1/2 mx-auto space-y-6">
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
        <strong>Country</strong>
        <select v-model="formData.country" class="w-full border rounded bg-white p-1">
          <option value="US">United States</option>
          <option value="TN">Tunisia</option>
        </select>
        <p v-if="errors.country" class="error">{{ errors.country[0] }}</p>
      </div>
      <div>
        <strong>Address</strong>
        <input type="text" placeholder="Address" v-model="formData.address">
        <p v-if="errors.address" class="error">{{ errors.address[0] }}</p>
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
      <div>
        <strong>Start date</strong>
        <input type="datetime-local" v-model="formData.start_at" class="w-full border rounded p-1">
        <p v-if="errors.start_at" class="error">{{ errors.start_at[0] }}</p>
      </div>
      <div>
        <strong>End date</strong>
        <input id="event-ent-at" type="datetime-local" v-model="formData.end_at" class="w-full border rounded p-1">
        <p v-if="errors.end_at" class="error">{{ errors.end_at[0] }}</p>
      </div>
      <button class="primary-btn">Update</button>
    </form>
  </main>
</template>

<script setup>
import {onMounted, reactive, ref, watch} from "vue";
import {useEventsStore} from "@/stores/events.js";
import {storeToRefs} from "pinia";
import {useRoute, useRouter} from "vue-router";
import {useAuthStore} from "@/stores/auth.js";

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
  end_at: ''
});

const {errors} = storeToRefs(useEventsStore())
const {updateEvent, getEvent} = useEventsStore();
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
  errors.value = {};
  const eventObj = await getEvent(route.params.id);

  if (authStore.user.id !== eventObj.user_id) {
    router.push({ name: 'home' })
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

  eventData.value.id = eventObj.id;
  eventData.value.user_id = eventObj.user_id;
})

watch(() => formData.start_at, (newStartAt) => {
  if (formData.end_at < newStartAt) {
    formData.end_at = '';
  }

  document.querySelector('#event-ent-at').min = newStartAt
})

</script>

<style scoped>

</style>