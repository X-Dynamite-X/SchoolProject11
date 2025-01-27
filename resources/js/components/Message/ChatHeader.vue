<script setup>
const props = defineProps({
    activeChat: Object,
});
</script>
<template>
    <header
        class="p-4 bg-gray-100 dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700"
    >
        <h2 class="text-lg font-bold dark:text-white">
            {{ activeChat?.other_user?.name || "Select a chat" }}
        </h2>
    </header>
</template>

