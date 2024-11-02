import {defineStore} from "pinia";

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
        }
    }
})