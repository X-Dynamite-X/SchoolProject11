<script setup >
import { useAuthStore } from '@/Stores/auth';
const authStore = useAuthStore();

</script>
<template>
    <p class="text-red-800 font-bold text-5xl text-center">
        this is Home Page
        {{ authStore.user }}

    </p>
</template>
