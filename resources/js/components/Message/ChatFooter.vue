<script setup>
import { ref } from "vue";
import { useAuthStore } from "@/Stores/Auth";

const authStore = useAuthStore();
const newMessage = ref("");

const emit = defineEmits(["sendMessage"]);

const sendMessage = () => {
    if (newMessage.value.trim()) {
        emit("sendMessage", authStore.user.user.id, newMessage.value); // Pass userId and newMessage
        newMessage.value = ""; // Clear the input after sending
    }
};
</script>

<template>
    <footer
        class="p-4 bg-gray-100 dark:bg-gray-800 border-t border-gray-300 dark:border-gray-700"
    >
        <div class="flex space-x-2">
            <input
                v-model="newMessage"
                type="text"
                placeholder="Type your message..."
                class="flex-1 p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
            <button
                type="submit"
                @click="sendMessage"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            >
                Send
            </button>
        </div>
    </footer>
</template>
