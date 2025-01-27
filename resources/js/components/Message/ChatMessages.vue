<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/Stores/Auth";
import CheckDouble from "@/components/Icon/CheckDouble.vue";
import Check from "@/components/Icon/Check.vue";
import ChatFooter from "@/components/Message/ChatFooter.vue";

const authStore = useAuthStore();

defineProps({
    activeChat: Object,
});
</script>

<template>
    <div
        v-for="(message, index) in activeChat?.messages || []"
        :key="index"
        :class="[
            message.sender_id == authStore.user.user.id
                ? 'ml-auto bg-blue-500 text-white text-end'
                : 'mr-auto bg-gray-200 dark:bg-gray-700 dark:text-white',
        ]"
        class="p-2 rounded-lg max-w-md w-fit break-words"
    >
        <div class="break-words">
            {{ message.text }}
        </div>
        <div class="text-xs text-gray-400 mt-1">
            {{ message.created_at }}
        </div>
        <div
            v-if="message.sender_id == authStore.user.user.id"
            class="text-xs mt-1 flex items-center space-x-1 justify-end"
        >
            <span v-if="message.is_read == true">
                <CheckDouble />
            </span>
            <span v-else class="text-gray-400">
                <Check />
            </span>
        </div>
    </div>
</template>
