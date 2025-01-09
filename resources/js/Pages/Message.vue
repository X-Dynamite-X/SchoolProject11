<script setup>
import { ref, computed } from "vue";

const chats = ref([
    { id: 1, name: "Ahmed", lastMessage: "Hi!", messages: [{ text: "test", isSender: true }, { text: "test", isSender: true }, { text: "test", isSender: false }, { text: "test", isSender: true },
    { text: "test", isSender: true }, { text: "test", isSender: true }, { text: "test", isSender: false }, { text: "test", isSender: true }
    ,{ text: "test", isSender: true }, { text: "test", isSender: true }, { text: "test", isSender: false }, { text: "test", isSender: true }
    ,{ text: "test", isSender: true }, { text: "test", isSender: true }, { text: "test", isSender: false }, { text: "test", isSender: true }
    ,{ text: "test", isSender: true }, { text: "test", isSender: true }, { text: "test", isSender: false }, { text: "test", isSender: true }
    ] },
    { id: 2, name: "Sarah", lastMessage: "How are you?", messages: [] },
    { id: 3, name: "Mohammed", lastMessage: "Good morning!", messages: [] },
]);

const activeChatId = ref(null);
const newMessage = ref("");
const searchQuery = ref("");

// الدالة لحساب المحادثة النشطة
const activeChat = computed(() => {
    return chats.value.find((chat) => chat.id === activeChatId.value);
});

// الدالة لتصفية المحادثات بناءً على البحث
const filteredChats = computed(() => {
    return chats.value.filter(chat =>
        chat.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// تغيير المحادثة النشطة
const selectChat = (chatId) => {
    activeChatId.value = chatId;
};

// إرسال رسالة جديدة
const sendMessage = () => {
    if (newMessage.value.trim() && activeChat.value) {
        activeChat.value.messages.push({
            text: newMessage.value,
            isSender: true,
        });
        newMessage.value = "";
    }
};
</script>

<template>
    <div class="flex h-[92vh] dark:bg-gray-900">
        <!-- قائمة المحادثات -->
        <aside
            class="w-full touch-scroll md:w-1/3 lg:w-1/4 bg-gray-100 dark:bg-gray-800 dark:border-gray-700 border-r border-gray-300 overflow-y-auto"
        >
            <div class="p-4 flex flex-col">
                <h2 class="text-lg font-bold dark:text-white">Chats</h2>
                <!-- شريط البحث -->
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search users..."
                    class="mt-2 p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                />
            </div>
            <ul>
                <li
                    v-for="chat in filteredChats"
                    :key="chat.id"
                    class="p-2 border-b hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    :class="{
                        'bg-gray-300 dark:bg-gray-700':
                            chat.id === activeChatId,
                    }"
                    @click="selectChat(chat.id)"
                >
                    <div class="font-semibold dark:text-white">
                        {{ chat.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ chat.lastMessage }}
                    </div>
                </li>
            </ul>
        </aside>

        <!-- واجهة الدردشة -->
        <main class="flex-1 bg-white dark:bg-gray-900">
            <div class="h-[92vh] flex flex-col">
                <!-- الرأس -->
                <header
                    class="p-4 bg-gray-100 dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700"
                >
                    <h2 class="text-lg font-bold dark:text-white">
                        {{ activeChat?.name || "Select a chat" }}
                    </h2>
                </header>

                <!-- الرسائل -->
                <div class="flex-1  touch-scroll overflow-y-auto p-4 space-y-3">
                    <div
                        v-for="(message, index) in activeChat?.messages || []"
                        :key="index"
                        :class="{
                            'ml-auto bg-blue-500 text-white': message.isSender,
                            'mr-auto bg-gray-200 dark:bg-gray-700 dark:text-white':
                                !message.isSender,
                        }"
                        class="p-2 rounded-lg max-w-md"
                    >
                        {{ message.text }}
                    </div>
                </div>

                <!-- إدخال الرسائل -->
                <footer
                    class="p-4 bg-gray-100 dark:bg-gray-800 border-t border-gray-300 dark:border-gray-700"
                >
                    <form @submit.prevent="sendMessage" class="flex space-x-2">
                        <input
                            v-model="newMessage"
                            type="text"
                            placeholder="Type your message..."
                            class="flex-1 p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        />
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                        >
                            Send
                        </button>
                    </form>
                </footer>
            </div>
        </main>
    </div>
</template>
