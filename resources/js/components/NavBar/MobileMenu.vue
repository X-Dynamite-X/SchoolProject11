<script setup>
import { defineProps } from 'vue';
import {   useRoute } from 'vue-router';
 const route = useRoute();
defineProps({
  menuItems: {
    type: Array,
    required: true,
  },
});
</script>

<template>
    <div class="sm:hidden bg-gray-800">
      <div class="space-y-1 px-2 pb-3 pt-2">
        <router-link
          v-for="item in menuItems"
          :key="item.name"
          :to="{ name: item.to }"
          :class="[
            'block rounded-md px-3 py-2 text-base font-medium',
            route.name === item.to  ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white',
          ]"
          aria-current="page"
        >
          {{ item.name }}
        </router-link>
      </div>
        <button
        class="w-full bg-gray-900 text-white py-2 text-center"
        @click="$emit('close')"
      >
        Close Menu
      </button>
    </div>
  </template>

