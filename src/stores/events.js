import {defineStore} from "pinia";
import {useAuthStore} from "@/stores/auth.js";

async function createTicketType (ticketType) {
    await fetch('/api/ticket-types', {
        method: 'post',
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(ticketType)
    });
}

async function updateTicketType (ticketType) {
    await fetch(`/api/ticket-types/${ticketType.id}`, {
        method: 'put',
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(ticketType)
    });
}

export const useEventsStore = defineStore('eventsStore', {
    state: () => ({
        events: [],
        errors: {}
    }),
    actions: {
        async getEvents () {
            const res = await fetch('/api/events', {
                method: 'get',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
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
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            });
            const data = await res.json();

            if (res.ok) {
                return data
            }
        },
        async getTicketTypes (eventId) {
            const res = await fetch(`/api/ticket-types/${eventId}`, {
                method: 'get',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();

            if (res.ok) {
                return data
            }

            return [];
        },
        async getEvent (id) {
            const res = await fetch(`/api/events/${id}`, {
                method: 'get',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();

            if (res.ok) {
                if (data.event.cover_image) {
                    data.event.cover_image = `/assets/${data.event.cover_image}`;
                }

                return data.event
            }

            return null;
        },
        async createEvent (formData, ticketTypes){
            const res = await fetch('/api/events', {
                method: 'post',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await res.json();

            if (data.errors) {
                this.errors = data.errors;
            } else {
                ticketTypes.forEach((item) => {
                    item.event_id = data.event.id
                    createTicketType(item)
                })
                this.errors = {};
                this.router.push({
                    name: 'myEvents'
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
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            });

            const data = await res.json();

            if (data.errors) {
                alert(errorMessage);
            } else {
                this.errors = {};
                location.reload()
            }
        },
        async updateEvent (eventData, formData, ticketTypes){
            const authStore = useAuthStore();
            const errorMessage = 'You do not have permissions to update this event.';

            if (authStore.user.id !== eventData.user_id) {
                alert(errorMessage);
                return;
            }

            const res = await fetch(`/api/events/${eventData.id}`, {
                method: 'put',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await res.json();

            if (data.errors) {
                this.errors = data.errors;
            } else {
                this.errors = {};

                ticketTypes.forEach(async (item) => {
                    item.event_id = data.id

                    if (item.id) {
                        await updateTicketType(item)
                    } else {
                        await createTicketType(item)
                    }
                });

                this.router.push({
                    name: 'myEvents'
                });
            }
        }
    }
})