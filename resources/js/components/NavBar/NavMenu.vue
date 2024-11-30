<script setup>
import { defineProps } from 'vue';
import { useRoute } from 'vue-router';
defineProps({
    menuItems: {
        type: Array,
        required: true,
    },
});


const route = useRoute();

function t(){
    console.log(route.name);

}

</script>

<template>
    <div class="hidden sm:ml-6 sm:block">
        <div class="flex space-x-4">
            <router-link
                @click="t()"
                v-for="item in menuItems"
                :key="item.name"
                :to="{ name: item.to }"
                :class="[
                'rounded-md px-3 py-2 text-sm font-medium',
                route.name === item.to ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white',
            ]"
            aria-current="page"
            >
            {{ item.name }}
            </router-link>
        </div>
    </div>
  </template>

