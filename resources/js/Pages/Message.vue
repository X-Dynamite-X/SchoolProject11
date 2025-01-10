<script setup>
import { onMounted, ref, computed } from "vue";
import { useMessageStore } from "@/Stores/message";
import { useAuthStore } from "@/Stores/Auth";

const authStore = useAuthStore();

const messageStore = useMessageStore();
const loading = ref(true);

// المحادثات المحملة من الـ API
const conversations = ref([]);

// المحادثة النشطة
const activeChatId = ref(null);

// الرسالة الجديدة
const newMessage = ref("");

// البحث
const searchQuery = ref("");

// تحميل المحادثات من الـ API
const fetchDataConversation = async () => {
    try {
        await messageStore.getConversations();
        conversations.value = messageStore.conversations; // استبدال البيانات بالمحادثات
    } catch (error) {
        console.error("Failed to fetch conversations:", error);
    } finally {
        loading.value = false;
    }
};

// استدعاء بيانات المحادثات عند التركيب
onMounted(() => {
    fetchDataConversation();
});

// المحادثة النشطة
const activeChat = computed(() => {
    return conversations.value.find(
        (conversation) => conversation.id === activeChatId.value
    );
});

// تصفية المحادثات بناءً على البحث
const filteredChats = computed(() => {
    return conversations.value.filter((conversation) =>
        conversation.other_user.name
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase())
    );
});

// اختيار المحادثة النشطة
const selectChat = (chatId) => {
    activeChatId.value = chatId;
};

// إرسال رسالة جديدة
const sendMessage = (id) => {
    if (newMessage.value.trim() && activeChat.value) {
        activeChat.value.messages.push({
            text: newMessage.value,
            sender_id: id,
        });
        console.log(activeChat.value);
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
                    v-for="conversation in filteredChats"
                    :key="conversation.id"
                    class="p-2 border-b hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    :class="{
                        'bg-gray-300 dark:bg-gray-700':
                            conversation.id === activeChatId,
                    }"
                    @click="selectChat(conversation.id)"
                >
                    <div class="font-semibold dark:text-white">
                        {{ conversation.other_user.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{
                            conversation.messages?.[
                                conversation.messages.length - 1
                            ]?.text || "No messages yet"
                        }}
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
                        {{ activeChat?.other_user?.name || "Select a chat" }}
                    </h2>
                </header>

                <!-- الرسائل -->
                <div class="flex-1 touch-scroll overflow-y-auto p-4 space-y-3">
                    <div
                        v-for="(message, index) in activeChat?.messages || []"
                        :key="index"
                        :class="[
                            message.sender_id == authStore.user.user.id
                                ? 'ml-auto bg-blue-500 text-white'
                                : 'mr-auto bg-gray-200 dark:bg-gray-700 dark:text-white',
                        ]"
                        class="p-2 rounded-lg max-w-md"
                    >
                        {{ message.text }}
                        <div class="text-xs text-gray-400 mt-1">
                            {{ message.created_at }}
                        </div>
                    </div>
                </div>

                <!-- إدخال الرسائل -->
                <footer
                    class="p-4 bg-gray-100 dark:bg-gray-800 border-t border-gray-300 dark:border-gray-700"
                >
                    <form
                        @submit.prevent="sendMessage(authStore.user.user.id)"
                        class="flex space-x-2"
                    >
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
