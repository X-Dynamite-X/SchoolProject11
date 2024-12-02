<script setup>
import { useAuthStore } from "@/Stores/auth";
const authStore = useAuthStore();
</script>
<template>
    <div class="text-red-800 font-bold text-2xl text-center">
        this is Home Page
        <br />
        <div v-if="authStore.user">
            {{ authStore.user.user }}
            <br />
            =====================================
            <br />
            <p v-for="role in authStore.roles" :key="role.id">

            {{ role.name }}
            </p>
        </div>
    </div>
</template>
