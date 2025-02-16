<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { useAuthStore } from "@/Stores/auth";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    adminMenuItems: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["close"]);
const route = useRoute();
const authStore = useAuthStore();

const isAuth = ref(false);

const isActive = (menuItem) => route.name === menuItem.to;
onMounted(() => {
    if (authStore.user) {
        isAuth.value = true;
    } else {
        isAuth.value = false;
    }
});
watch(
    () => authStore.user,
    (newVal, oldVal) => {
        if (newVal) {
            isAuth.value = true;
        } else {
            isAuth.value = false;
        }
    }
);
const closeSidebar = () => {
    emit("close");
};
</script>

<template>
    <div class="relative">
        <!-- Backdrop -->
        <div
            v-if="isOpen"
            @click="closeSidebar"
            class="fixed inset-0 bg-gray-800/60 z-20 lg:hidden"
        ></div>

        <!-- Sidebar -->
        <div
            :class="[
                'fixed h-full inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 transition-transform duration-300 ease-in-out transform',
                isOpen ? 'translate-x-0' : '-translate-x-full',
                'lg:translate-x-0 lg:static lg:w-auto',
            ]"
        >
            <!-- Sidebar Content -->
            <div class="h-full flex flex-col">
                <!-- Header -->
                <div
                    class="px-4 py-6 border-b border-gray-200 dark:border-gray-700"
                >
                    <h2
                        class="text-xl font-semibold text-gray-800 dark:text-white"
                    >
                        Admin Dashboard
                    </h2>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <div v-for="item in adminMenuItems" :key="item.to">
                        <router-link
                            :to="{ name: item.to }"
                            v-if="
                                (isAuth === item.auth && item.allUser) ||
                                (isAuth === item.auth &&
                                    item.role === authStore.roles.name)
                            "
                            :class="[
                                'flex items-center px-4 py-2 text-sm rounded-lg transition-colors',
                                isActive(item)
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
                            ]"
                        >
                            {{ item.name }}
                        </router-link>
                    </div>
                </nav>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img
                                class="h-8 w-8 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="Admin avatar"
                            />
                        </div>
                        <div class="ml-3">
                            <p
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ authStore.user?.user.name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Administrator
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
