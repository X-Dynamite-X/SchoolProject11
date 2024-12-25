<script setup>
defineProps({
    currentPage: {
        type: Number,
        required: true,
    },
    totalPages: {
        type: Number,
        required: true,
    },
});

defineEmits(["change-page"]);
</script>

<template>
    <div
        class="flex flex-col sm:flex-row items-center justify-between mt-6 space-y-4 sm:space-y-0 sm:space-x-4"
    >
        <!-- زر الصفحة السابقة -->
        <button
            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="currentPage === 1"
            @click="$emit('change-page', currentPage - 1)"
        >
            &laquo; Previous
        </button>

        <!-- رقم الصفحة -->
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            Page
            <strong class="text-gray-900 dark:text-gray-100">
                {{ currentPage }}
            </strong>
            of
            <strong class="text-gray-900 dark:text-gray-100">
                {{ totalPages }}
            </strong>
        </span>

        <!-- زر الصفحة التالية -->
        <button
            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="currentPage === totalPages"
            @click="$emit('change-page', currentPage + 1)"
        >
            Next &raquo;
        </button>
    </div>
</template>
