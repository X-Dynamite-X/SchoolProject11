<script setup>
import { onMounted, ref, watch, computed } from "vue";
import { useMessageStore } from "@/Stores/message";
import { useAuthStore } from "@/Stores/Auth";
import CloseIcon from "@/components/Icon/CloseIcon.vue";
import MenuIcon from "@/components/Icon/MenuIcon.vue";

const authStore = useAuthStore();
const messageStore = useMessageStore();
const loading = ref(true);
const conversations = ref([]);
const activeChatId = ref(null);
const newMessage = ref("");
const searchQuery = ref("");
const isSidebarVisible = ref(false);
const conversationQuery = ref([""]);
const conversationId = ref("");
const messagesContainer = ref(null);

const fetchDataConversation = async () => {
    try {
        await messageStore.getConversations();
        conversations.value = messageStore.conversations;
    } catch (error) {
        console.error("Failed to fetch conversations:", error);
    } finally {
        loading.value = false;
        responseNewMessage();
    }
};
const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop =
            messagesContainer.value.scrollHeight;
    }
};

onMounted(() => {
    fetchDataConversation();
});

const activeChat = computed(() => {
    return conversations.value.find(
        (conversation) => conversation.id === activeChatId.value
    );
});

watch(searchQuery, async (newQuery) => {
    if (newQuery.length > 0) {
        try {
            const response = await messageStore.searchConversations(newQuery);
            if (response.conversations) {
                conversationQuery.value = response.conversations;
            }
        } catch (error) {
            console.error("Error fetching conversations:", error);
        }
    }
});
const filteredChats = computed(() => {
    if (searchQuery.value.length === 0) {
        return conversations.value.filter((conversation) =>
            conversation?.other_user?.name
                ?.toLowerCase()
                .includes(searchQuery.value.toLowerCase())
        );
    } else {
        return conversationQuery.value.filter(
            (conversation) => conversation.name
        );
    }
});

const selectChat = (chatId) => {
    activeChatId.value = chatId;
    conversationId.value = chatId;
    isSidebarVisible.value = false;
    setTimeout(scrollToBottom, 100);
};
const createChat = async (userId) => {
    const existingConversation = conversations.value.find(
        (conversation) => conversation.other_user.id === userId
    );

    if (existingConversation) {
        selectChat(existingConversation.id);
        return;
    }
    try {
        const response = await messageStore.createConversation(userId);

        if (response.conversation) {
            conversations.value.push(response.conversation);
            searchQuery.value = "";
            selectChat(response.conversation.id);
        }
    } catch (error) {
        console.error("Failed to create conversation:", error);
    }
};

const sendMessage = (id) => {
    if (newMessage.value.trim() && activeChat.value) {
        let created_at = new Date().toLocaleString();
        activeChat.value.messages.push({
            text: newMessage.value,
            sender_id: id,
            created_at: created_at,
        });
        let data = {
            text: newMessage.value,
            created_at: created_at,
            conversationId: conversationId.value,
        };
        setTimeout(scrollToBottom, 100);

        let index = conversations.value.findIndex(
            (conversation) => conversation.id === activeChatId.value
        );

        moveConversationsInLastMessage(index);

        newMessage.value = "";
        createNewMessage(data);
    }
};
const createNewMessage = async (data) => {
    await messageStore.createMessage(data);
};

const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

const isWideScreen = computed(() => {
    return window.innerWidth >= 768;
});
const addConversationChannel = window.Echo.private(
    `user_${authStore.user.user.id}`
);
addConversationChannel.listen(".add-conversation", function (data) {
    const newConversation = {
        id: data.conversation.id,
        messages: data.conversation.messages,
        other_user: data.conversation.other_user,
    };
    conversations.value.push(newConversation);
});

function responseNewMessage() {
    for (let i = 0; i < conversations.value.length; i++) {
        let conversationId = conversations.value[i].id;
        const addMessageChannel = window.Echo.private(
            `conversation_${conversationId}`
        );
        addMessageChannel.listen(".new-message", function (data) {
            if (data.sender_id != authStore.user.user.id) {
                const newMessage = {
                    id: data.message_id ?? null,
                    sender_id: data.sender_id,
                    text: data.text,
                    created_at: data.created_at,
                };
                const existingConversation = conversations.value.find(
                    (conv) => conv.id === data.conversation_id
                );

                if (existingConversation) {
                    existingConversation.messages.push(newMessage);
                    if (activeChatId.value === data.conversation_id) {
                        setTimeout(scrollToBottom, 100);
                    }
                    let index = conversations.value.findIndex(
                        (conversation) =>
                            conversation.id === data.conversation_id
                    );
                    moveConversationsInLastMessage(index);
                }
            }
        });
    }
}
function moveConversationsInLastMessage(currentIndex) {
    if (currentIndex !== 0) {
        const [movedConversation] = conversations.value.splice(currentIndex, 1);
        conversations.value.unshift(movedConversation);
    }
    return conversations.value;
}
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
            class="absolute md:static w-[100vw] md:w-1/3 lg:w-1/4 bg-gray-100 dark:bg-gray-800 dark:border-gray-700 border-r border-gray-300 overflow-y-auto transition-transform transform md:translate-x-0"
            :class="{ '-translate-x-full': !isSidebarVisible && !isWideScreen }"
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
            <template v-if="searchQuery.length === 0">
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
            </template>
            <template v-else>
                <ul>
                    <li
                        v-for="conversation in filteredChats"
                        :key="conversation.id"
                        class="p-2 border-b hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                        :class="{
                            'bg-gray-300 dark:bg-gray-700':
                                conversation.id === activeChatId,
                        }"
                        @click="createChat(conversation.id)"
                    >
                        <div class="font-semibold dark:text-white">
                            {{ conversation.name }}
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
            </template>
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
                <div
                    ref="messagesContainer"
                    class="flex-1 touch-scroll overflow-y-auto p-4 space-y-3"
                >
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
