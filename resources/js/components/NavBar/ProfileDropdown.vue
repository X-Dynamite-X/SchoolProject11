
<script setup>
import { ref } from "vue";

const isOpen = ref(false);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};
</script>

<template>
    <div class="relative ml-3">
      <button
        type="button"
        class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
        @click="toggleDropdown"
      >
        <img
          class="h-8 w-8 rounded-full"
          src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
          alt=""
        />
      </button>
      <div
        v-if="isOpen"
        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
      >
        <a href="#" class="block px-4 py-2 text-sm text-gray-700">Your Profile</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-700">Settings</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-700">Sign out</a>
      </div>
    </div>
  </template>
