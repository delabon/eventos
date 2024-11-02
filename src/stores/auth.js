import {defineStore} from "pinia";

export const useAuthStore = defineStore('authStore', {
    state: () => ({
        user: null,
        errors: {},
    }),
    getters: {},
    actions: {
        async getUser () {
            if (localStorage.getItem('token')) {
                const res = await fetch('/api/user', {
                    method: 'post',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });

                const data = await res.json();

                if (res.ok) {
                    this.user = data;
                    this.errors = {}
                }
            }
        },
        async authenticate (apiRoute, formData) {
            const res = await fetch(`/api/${apiRoute}`, {
                method: 'post',
                body: JSON.stringify(formData)
            })

            const data = await res.json();

            if (res.ok) {
                if (data.errors) {
                    this.errors = data.errors;
                } else {
                    localStorage.setItem('token', data.token);
                    this.user = data;
                    this.errors = {}

                    this.router.push({
                        name: 'home'
                    })
                }
            }
        },
        async logout () {
            const res = await fetch('/api/logout', {
                method: 'delete',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await res.json();

            if (res.ok){
                this.user = null
                this.errors = {}
                localStorage.removeItem('token')

                this.router.push({
                    name: 'home'
                })
            }
        }
    },
})
