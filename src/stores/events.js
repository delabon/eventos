import {defineStore} from "pinia";
import {useAuthStore} from "@/stores/auth.js";

export const useEventsStore = defineStore('eventsStore', {
    state: () => ({
        events: [],
        errors: {}
    }),
    actions: {
        async getEvents () {
            const res = await fetch('/api/events', {
                method: 'get'
            });
            const data = await res.json();

            if (res.ok) {
                return data
            }
        },
        async getMyEvents () {
            const res = await fetch('/api/events/mine', {
                method: 'get',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
            });
            const data = await res.json();

            if (res.ok) {
                return data
            }
        },
        async getEvent (id) {
            const res = await fetch(`/api/events/${id}`, {
                method: 'get'
            });
            const data = await res.json();

            if (res.ok) {
                return data.event
            }
        },
        async createEvent (formData){
            const res = await fetch('/api/events', {
                method: 'post',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(formData)
            });

            const data = await res.json();

            if (data.errors) {
                this.errors = data.errors;
            } else {
                this.errors = {};
                this.router.push({
                    name: 'home'
                })
            }
        },
        async deleteEvent (event){
            const authStore = useAuthStore();
            const errorMessage = 'You do not have permissions to delete this event.';

            if (authStore.user.id !== event.user_id) {
                alert(errorMessage);
                return;
            }

            const res = await fetch(`/api/events/${event.id}`, {
                method: 'delete',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
            });

            const data = await res.json();

            if (data.errors) {
                alert(errorMessage);
            } else {
                this.errors = {};
                this.router.push({
                    name: 'home'
                })
            }
        },
        async updateEvent (eventData, formData){
            const authStore = useAuthStore();
            const errorMessage = 'You do not have permissions to update this event.';

            if (authStore.user.id !== eventData.user_id) {
                alert(errorMessage);
                return;
            }

            const res = await fetch(`/api/events/${eventData.id}`, {
                method: 'put',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(formData)
            });

            const data = await res.json();

            if (data.errors) {
                this.errors = data.errors
            } else {
                this.errors = {};
                this.router.push({
                    name: 'home'
                })
            }
        }
    }
})