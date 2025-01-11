<script setup>
import { onMounted, ref, computed } from "vue";
import { useMessageStore } from "@/Stores/message";
import { useAuthStore } from "@/Stores/Auth";
import CloseIcon from "@/components/Icon/CloseIcon.vue";
import MenuIcon from "@/components/Icon/MenuIcon.vue";

const authStore = useAuthStore();
const messageStore = useMessageStore();

// المتغيرات الأساسية
const loading = ref(true);
const conversations = ref([]); // المحادثات المحملة من الـ API
const activeChatId = ref(null); // المحادثة النشطة
const newMessage = ref(""); // الرسالة الجديدة
const searchQuery = ref(""); // البحث
const isSidebarVisible = ref(false); // التحكم في إظهار القائمة الجانبية

// تحميل المحادثات من الـ API
const fetchDataConversation = async () => {
    try {
        await messageStore.getConversations();
        conversations.value = messageStore.conversations;
    } catch (error) {
        console.error("Failed to fetch conversations:", error);
    } finally {
        loading.value = false;
    }
};

// استدعاء المحادثات عند التحميل
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
        conversation?.other_user?.name
            ?.toLowerCase()
            .includes(searchQuery.value.toLowerCase())
    );
});

// اختيار المحادثة النشطة
const selectChat = (chatId) => {
    activeChatId.value = chatId;
    isSidebarVisible.value = false; // إخفاء القائمة عند اختيار محادثة على الشاشات الصغيرة
};

// إرسال رسالة جديدة
const sendMessage = (id) => {
    if (newMessage.value.trim() && activeChat.value) {
        activeChat.value.messages.push({
            text: newMessage.value,
            sender_id: id,
            created_at: new Date().toLocaleString(),
        });
        newMessage.value = "";
    }
};

// التحكم في القائمة الجانبية
const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

// التحقق من الشاشات الكبيرة
const isWideScreen = computed(() => {
    return window.innerWidth >= 768;
});
</script>

<template>
    <div class="flex h-[92.9vh] dark:bg-gray-900">
        <button
    @click="toggleSidebar"
    class="absolute top-[9%] right-[10%] md:hidden p-2 bg-blue-500 text-white rounded-md max-h-[3rem] shadow-lg hover:bg-blue-600 z-10 transition-transform duration-300"
 >
    <template v-if="isSidebarVisible">
        <CloseIcon class="transition-transform duration-150" />
      
    </template>
    <template v-else>
        <MenuIcon class="transition-transform duration-150" />
 
    </template>
</button>


        <!-- قائمة المحادثات -->
        <aside
            v-show="isSidebarVisible || isWideScreen"
            class="absolute md:static w-[100vw]    md:w-1/3 lg:w-1/4 bg-gray-100 dark:bg-gray-800 dark:border-gray-700 border-r border-gray-300 overflow-y-auto transition-transform transform md:translate-x-0"
            :class="{ '-translate-x-full': !isSidebarVisible && !isWideScreen }"
        >
            <div class="p-4 flex flex-col ">
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
            <div class="h-full flex flex-col">
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
                            {{ message.created_at  }}
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
