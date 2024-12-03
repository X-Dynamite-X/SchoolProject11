
<script setup>
defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Search...',
  },
});
defineEmits(['update:modelValue']);
</script>

<template>
    <div class="mb-4 flex items-center space-x-2">
      <label for="searchInput">
        <slot name="icon"></slot>
      </label>
      <input
        id="searchInput"
        type="text"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        class="w-full p-2 border border-gray-300 rounded shadow-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
    </div>
  </template>
