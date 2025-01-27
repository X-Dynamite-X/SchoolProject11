<script setup>
import { ref, computed, onMounted, watch, provide } from "vue";
import { useMessageStore } from "@/Stores/message";
import { useAuthStore } from "@/Stores/Auth";
import CloseIcon from "@/components/Icon/CloseIcon.vue";
import MenuIcon from "@/components/Icon/MenuIcon.vue";
import ChatSidebar from "@/components/Message/ChatSidebar.vue";
import ChatHeader from "@/components/Message/ChatHeader.vue";
import ChatMessages from "@/components/Message/ChatMessages.vue";
import ChatFooter from "@/components/Message/ChatFooter.vue";

// المتغيرات العامة
const authStore = useAuthStore();
const messageStore = useMessageStore();
const loading = ref(true);
const conversations = ref([]);
const activeChatId = ref(null);
const searchQuery = ref("");
const isSidebarVisible = ref(true);
const conversationQuery = ref([]);
const conversationId = ref("");
const messagesContainer = ref(null);
const oldConversationId = ref(null);
let checkReadMessageChannel = null;

// استرجاع المحادثات
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

// نقل المحادثة إلى الأعلى
const moveConversationsInLastMessage = (currentIndex) => {
    if (currentIndex !== 0) {
        const [movedConversation] = conversations.value.splice(currentIndex, 1);
        conversations.value.unshift(movedConversation);
    }
    return conversations.value;
};

// التمرير إلى أسفل
const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

provide("scrollToBottom", scrollToBottom);

// تحديث الرسائل غير المقروءة
const isReadMessageInConversation = (conversationId) => {
    const conversation = conversations.value.find(
        (conv) => conv.id === conversationId
    );

    if (!conversation || !conversation.messages) {
        console.warn(`Conversation with ID ${conversationId} not found.`);
        return;
    }

    conversation.messages.forEach((message) => {
        if (message.sender_id === authStore.user.user.id && !message.is_read) {
            message.is_read = true;
        }
    });
};

// الاستجابة للرسائل الجديدة
const responseNewMessage = () => {
    const connectedChannels = new Set();
    const conversationMap = new Map(
        conversations.value.map((conv) => [conv.id, conv])
    );

    conversations.value.forEach((conversation) => {
        if (!connectedChannels.has(conversation.id)) {
            const addMessageChannel = window.Echo.private(
                `conversation_${conversation.id}`
            );
            connectedChannels.add(conversation.id);

            addMessageChannel.listen(".new-message", (data) => {
                if (data.sender_id !== authStore.user.user.id) {
                    const newMessage = {
                        id: data.message_id ?? null,
                        sender_id: data.sender_id,
                        text: data.text,
                        created_at: data.created_at,
                    };

                    const existingConversation = conversationMap.get(
                        data.conversation_id
                    );

                    if (existingConversation) {
                        existingConversation.messages.push(newMessage);

                        if (activeChatId.value === data.conversation_id) {
                            setTimeout(scrollToBottom, 100);
                       
                            cheakMessageIsRead(data.conversation_id);
                        }

                        const index = conversations.value.findIndex(
                            (conv) => conv.id === data.conversation_id
                        );
                        moveConversationsInLastMessage(index);
                    }
                }
            });
        }
    });
};

// تحديد المحادثة النشطة
const selectChat = (chatId) => {
    activeChatId.value = chatId;
    conversationId.value = chatId;
    isSidebarVisible.value = false;

    if (oldConversationId.value !== chatId) {
        cheakMessageIsRead(chatId);
    }

    checkInAuntherUserIsReadMessageOrNot();
};

// التحقق من الرسائل غير المقروءة
const cheakMessageIsRead = (conversationId) => {
    const conversation = conversations.value.find(
        (conv) => conv.id === conversationId
    );

    if (!conversation || !conversation.messages) {
        console.warn(`Conversation with ID ${conversationId} not found.`);
        return;
    }

    const hasUnreadMessages = conversation.messages.some(
        (message) => message.sender_id !== authStore.user.user.id && !message.is_read
    );

    if (hasUnreadMessages) {
        messageStore.editCheckValueInMessage(conversationId);
    } else {
        console.log("No unread messages found.");
    }
};

// التحقق من قراءة الرسائل من المستخدم الآخر
const checkInAuntherUserIsReadMessageOrNot = () => {
    if (!activeChatId.value) {
        console.warn("Invalid conversation ID.");
        return;
    }

    if (
        checkReadMessageChannel &&
        oldConversationId.value === activeChatId.value
    ) {
        return;
    }

    if (checkReadMessageChannel) {
        checkReadMessageChannel.stopListening(".read-message");
        checkReadMessageChannel = null;
    }

    oldConversationId.value = activeChatId.value;

    checkReadMessageChannel = Echo.private(
        `message_in_conversation_${activeChatId.value}_isRead`
    );

    checkReadMessageChannel.listen(".read-message", (data) => {
        if (data.conversation_id) {
            isReadMessageInConversation(data.conversation_id);
        } else {
            console.warn("Received data does not contain conversation_id.");
        }
    });
};

// إنشاء محادثة جديدة
const createChat = async (userId) => {
    const existingConversation = conversations.value.find(
        (conv) => conv.other_user.id === userId
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

// تحديث استعلام البحث
const updateSearchQuery = (newQuery) => {
    searchQuery.value = newQuery;
};

// إرسال رسالة
const sendMessage = (userId, messageText) => {
    if (messageText.trim() && activeChat.value) {
        const created_at = new Date().toLocaleString();
        activeChat.value.messages.push({
            text: messageText,
            sender_id: userId,
            created_at: created_at,
        });

        const data = {
            text: messageText,
            created_at: created_at,
            conversationId: activeChatId.value,
        };

        const currentIndex = conversations.value.findIndex(
            (conv) => conv.id === activeChatId.value
        );
        moveConversationsInLastMessage(currentIndex);

        setTimeout(scrollToBottom, 100);
        createNewMessage(data);
    }
};

// إنشاء رسالة جديدة
const createNewMessage = async (data) => {
    await messageStore.createMessage(data);
};

// تبديل الشريط الجانبي
const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

// التحقق من عرض الشريط الجانبي على الشاشات الكبيرة
const isWideScreen = computed(() => {
    return window.innerWidth >= 768;
});

// استرجاع المحادثات عند التحميل
onMounted(() => {
    fetchDataConversation();
});

// تحديد المحادثة النشطة
const activeChat = computed(() => {
    return conversations.value.find(
        (conv) => conv.id === activeChatId.value
    );
});

// مراقبة استعلام البحث
let debounceTimer = null;
watch(searchQuery, (newQuery) => {
    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    debounceTimer = setTimeout(async () => {
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
    }, 300);
});

// تصفية المحادثات
const filteredChats = computed(() => {
    if (searchQuery.value.length === 0) {
        return conversations.value.filter((conv) =>
            conv?.other_user?.name
                ?.toLowerCase()
                .includes(searchQuery.value.toLowerCase())
        );
    } else {
        return conversationQuery.value.filter((conv) => conv.name);
    }
});
</script>

<template>
    <div class="flex h-[92.9vh] dark:bg-gray-900">
        <button
            @click="toggleSidebar"
            class="absolute top-[9%] right-[2%] md:hidden p-2 bg-blue-500 text-white rounded-md max-h-[3rem] shadow-lg hover:bg-blue-600 z-10 transition-transform duration-300"
        >
            <template v-if="isSidebarVisible">
                <CloseIcon class="transition-transform duration-150" />
            </template>
            <template v-else>
                <MenuIcon class="transition-transform duration-150" />
            </template>
        </button>

        <ChatSidebar
            :isSidebarVisible="isSidebarVisible"
            :isWideScreen="isWideScreen"
            :searchQuery="searchQuery"
            :filteredChats="filteredChats"
            @triggerScroll="scrollToBottom"
            :activeChatId="activeChatId"
            @selectChat="selectChat"
            @createChat="createChat"
            @update:searchQuery="updateSearchQuery"
        />

        <main class="flex-1 bg-white dark:bg-gray-900">
            <div class="h-full flex flex-col">
                <ChatHeader :activeChat="activeChat" />
                <div
                    ref="messagesContainer"
                    id="messagesContainer"
                    class="flex-1 touch-scroll overflow-y-auto p-4 space-y-3"
                >
                    <ChatMessages
                        :activeChat="activeChat"
                        @sendMessage="sendMessage"
                    />
                </div>

                <ChatFooter @sendMessage="sendMessage" />
            </div>
        </main>
    </div>
</template>
