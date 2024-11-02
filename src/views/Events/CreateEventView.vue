<template>
  <main>
    <h1 class="title">Create event</h1>

    <form @submit.prevent="createEvent(formData)" class="w-1/2 mx-auto space-y-6">
      <div>
        <input type="text" placeholder="Name" v-model="formData.name">
        <p v-if="errors.name" class="error">{{ errors.name[0] }}</p>
      </div>
      <div>
        <textarea rows="6" placeholder="Description" v-model="formData.description"></textarea>
        <p v-if="errors.description" class="error">{{ errors.description[0] }}</p>
      </div>
      <div>
        <select v-model="formData.status" class="w-full border rounded bg-white p-1">
          <option value="draft">Draft</option>
          <option value="published">Published</option>
        </select>
        <p v-if="errors.status" class="error">{{ errors.status[0] }}</p>
      </div>
      <div>
        <select v-model="formData.country" class="w-full border rounded bg-white p-1">
          <option value="US">United States</option>
          <option value="TN">Tunisia</option>
        </select>
        <p v-if="errors.country" class="error">{{ errors.country[0] }}</p>
      </div>
      <div>
        <input type="text" placeholder="Address" v-model="formData.address">
        <p v-if="errors.address" class="error">{{ errors.address[0] }}</p>
      </div>
      <div>
        <input type="text" placeholder="City" v-model="formData.city">
        <p v-if="errors.city" class="error">{{ errors.city[0] }}</p>
      </div>
      <div>
        <input type="text" placeholder="Zip" v-model="formData.postal_code">
        <p v-if="errors.postal_code" class="error">{{ errors.postal_code[0] }}</p>
      </div>
      <div>
        <input type="datetime-local" v-model="formData.start_at" class="w-full border rounded p-1">
        <p v-if="errors.start_at" class="error">{{ errors.start_at[0] }}</p>
      </div>
      <div>
        <input id="event-ent-at" type="datetime-local" v-model="formData.end_at" class="w-full border rounded p-1">
        <p v-if="errors.end_at" class="error">{{ errors.end_at[0] }}</p>
      </div>
      <button class="primary-btn">Create</button>
    </form>
  </main>
</template>

<script setup>
import {reactive, watch} from "vue";
import {useEventsStore} from "@/stores/events.js";
import {storeToRefs} from "pinia";

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
const {createEvent} = useEventsStore();

watch(() => formData.start_at, (newStartAt) => {
  if (formData.end_at < newStartAt) {
    formData.end_at = '';
  }

  document.querySelector('#event-ent-at').min = newStartAt
})

</script>

<style scoped>

</style>