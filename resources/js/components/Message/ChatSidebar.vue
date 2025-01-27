<script setup>
import { inject, nextTick, defineEmits } from "vue";

// الخصائص التي يتلقاها المكون
const props = defineProps({
    isSidebarVisible: Boolean,
    isWideScreen: Boolean,
    searchQuery: String,
    filteredChats: Array,
    activeChatId: Number,
});

// تعريف الحدث
const emit = defineEmits([
    "update:searchQuery",
    "triggerScroll",
    "selectChat",
    "createChat",
]);

// الحصول على الدالة من المكون الأب
const scrollToBottom = inject("scrollToBottom");

const selectChat = (chatId) => {
    emit("selectChat", chatId);
    nextTick(() => {
        setTimeout(scrollToBottom, 100); // التأكد من استدعاء دالة التمرير بعد تنفيذ التغييرات
    });
};

// تحديث searchQuery عند تغيير قيمة البحث
const updateSearchQuery = (event) => {
    let search = event.target.value.trim();
    if (search.length > 0) {
        emit("update:searchQuery", event.target.value);
    } else {
        emit("update:searchQuery", "");
    }
};
const createChat = (userId) => {
    emit("createChat", userId);
};
</script>

<template>
    <aside
        class="absolute md:static max-h-[92vh] w-[100vw] md:w-1/3 lg:w-1/4 bg-gray-100 dark:bg-gray-800 dark:border-gray-700 border-r border-gray-300 overflow-y-auto transition-transform transform md:translate-x-0 touch-scroll hide-scrollbar"
        :class="{ '-translate-x-full': !isSidebarVisible && !isWideScreen }"
    >
        <div class="p-4 flex flex-col">
            <h2 class="text-lg font-bold dark:text-white">Chats</h2>
            <input
                @input="updateSearchQuery"
                v-model="props.searchQuery"
                type="text"
                placeholder="Search users..."
                class="mt-2 p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
        </div>
        <template v-if="props.searchQuery.length === 0">
            <ul class="overflow-y-scroll touch-scroll">
                <li
                    v-for="conversation in props.filteredChats"
                    :key="conversation.id"
                    class="p-2 border-b hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    :class="{
                        'bg-gray-300 dark:bg-gray-700':
                            conversation.id === props.activeChatId,
                    }"
                    @click="selectChat(conversation.id)"
                >
                    <div class="font-semibold dark:text-white">
                        {{ conversation.other_user.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{
                            (
                                conversation.messages?.[
                                    conversation.messages.length - 1
                                ]?.text || "No messages yet"
                            ).slice(0, 20) +
                            (conversation.messages?.[
                                conversation.messages.length - 1
                            ]?.text?.length > 20
                                ? "..."
                                : "")
                        }}
                    </div>
                </li>
            </ul>
        </template>
        <template v-else>
            <ul class="overflow-y-scroll touch-scroll">
                <li
                    v-for="conversation in props.filteredChats"
                    :key="conversation.id"
                    class="p-2 border-b hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    :class="{
                        'bg-gray-300 dark:bg-gray-700':
                            conversation.id === props.activeChatId,
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
</template>
