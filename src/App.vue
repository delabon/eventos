<template>
    <header>
        <div class="wrapper">
            <nav>
                <RouterLink to="/" class="nav-link">Home</RouterLink>

                <div v-if="authStore.user">
                    <div class="inline-flex items-center space-x-6">
                        <RouterLink :to="{name: 'myEvents'}" class="nav-link">My Events</RouterLink>

                        <form @submit.prevent="authStore.logout">
                            <button class="nav-link">Logout</button>
                        </form>

                        <span class="text-white">Hi <strong>{{ authStore.user.name }}</strong>!</span>
                    </div>
                </div>
                <div v-else class="space-x-6">
                    <RouterLink to="/login" class="nav-link">Login</RouterLink>
                    <RouterLink to="/register" class="nav-link">Register</RouterLink>
                </div>
            </nav>
        </div>
    </header>

    <RouterView/>
</template>

<script setup>
import {RouterLink, RouterView} from 'vue-router'
import {useAuthStore} from "@/stores/auth.js";

const authStore = useAuthStore();
</script>
