<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import AdminSidebar from "@/components/SideBar/AdminSidebar.vue";
import Navigation from "@/Layouts/Navigation.vue";

const route = useRoute();
const isSidebarOpen = ref(false);

const props = defineProps({
    adminRouteNames: {
        type: Array,
        required: true,
    },
    menuItems: {
        type: Array,
        required: true,
    },
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

// For Navigation component
const toggleMobileMenu = () => {
    // Implementation for mobile menu toggle if needed
};
</script>

<template>
    <!-- Admin Layout -->
    <div v-if="adminRouteNames.includes(route.name)" class="min-h-[92vh] max-h-[92vh]">
        <!-- Admin Container -->
        <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Mobile Menu Button -->
            <button
                @click="toggleSidebar"
                class="lg:hidden fixed top-4 left-4 z-40 p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none"
                aria-label="Toggle Sidebar"
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

            <!-- Sidebar -->
            <AdminSidebar
                :adminMenuItems="menuItems"
                :is-open="isSidebarOpen"
                @close="isSidebarOpen = false"
            />

            <!-- Main Content -->
            <main class="flex-1 ">
                <div class=" px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>

    <!-- Regular Layout -->
    <div v-else class="min-h-[92vh] max-h-[92vh]">
        <Navigation
            :menuItems="menuItems"
            @toggle="toggleMobileMenu"
        />
        <main class="">
            <slot />
        </main>
    </div>
</template>

<style scoped>
/* Add any scoped styles if needed */
</style>



