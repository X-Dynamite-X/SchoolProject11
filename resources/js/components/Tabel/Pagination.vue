<script setup>
defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});
defineEmits(["change-page"]);
</script>

<template>
    <div class="flex justify-between items-center mt-4">
        <button
            class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
            :disabled="!pagination.prev_page_url"
            @click="$emit('change-page', pagination.prev_page_url)"
        >
            Previous
        </button>
        <span class="text-sm text-gray-700 dark:text-gray-300">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
        </span>
        <button
            class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
            :disabled="!pagination.next_page_url"
            @click="$emit('change-page', pagination.next_page_url)"
        >
            Next
        </button>
    </div>
</template>
