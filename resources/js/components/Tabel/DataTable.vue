<script setup>
defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

</script>

<template>
    
    <table class="w-full border-collapse text-sm bg-white shadow-lg rounded-lg overflow-hidden dark:bg-gray-800">
      <thead class="bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100">
        <tr>
          <slot name="header"></slot>
        </tr>
      </thead>
      <tbody>
        <tr v-if="loading">
          <td colspan="100%" class="text-center py-6">
            <slot name="loading">
              <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500"></div>
              <span class="ml-4 text-blue-500 dark:text-blue-400">Loading...</span>
            </slot>
          </td>
        </tr>
        <tr v-if="!loading && data.length === 0">
          <td colspan="100%" class="text-center py-6 text-gray-500 dark:text-gray-300">
            <slot name="no-data">No data available.</slot>
          </td>
        </tr>
        <tr v-for="item in data" :key="item.id">
          <slot name="row" :item="item"></slot>
        </tr>
      </tbody>
    </table>
  </template>

