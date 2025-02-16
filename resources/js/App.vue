<script setup>
import { ref } from "vue"; // تأكد من أنك استوردت ref
import Navigation from "@/Layouts/Navigation.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const menuItems = [
    {
        name: "Users",
        to: "adminUsers",
        auth: true,
        allUser: false,
        role: "admin",
    },
    {
        name: "Subjects",
        to: "adminSubjects",
        auth: true,
        allUser: false,
        role: "admin",
    },
    {
        name: "Role",
        to: "adminRole",
        auth: true,
        allUser: false,
        role: "admin",
    },
    {
        name: "Permission",
        to: "adminPermission",
        auth: true,
        allUser: false,
        role: "admin",
    },

    { name: "Message", to: "message", auth: true, allUser: true },
    { name: "Home", to: "home", auth: true, allUser: true },
    { name: "Login", to: "login", auth: false, allUser: true },
    { name: "Register", to: "register", auth: false, allUser: true },
];
const adminRouteNames = [
    "adminUsers",
    "adminRole",
    "adminPermission",
    "adminSubjects",
];
// تعريف toggleMobileMenu
const toggleMobileMenu = ref(false);
</script>

<template>
    <!-- <div>
        <Navigation @toggle="toggleMobileMenu" :menuItems="menuItems" />
    </div> -->
    <AdminLayout :menuItems="menuItems"  @toggle="toggleMobileMenu" :adminRouteNames="adminRouteNames">
        <router-view v-slot="{ Component, route }">
            <div
                :key="route.name"
                class="dark:bg-gray-900 text-gray-900 dark:text-white min-h-[100vh] max-h-[100vh] pt-16"
            >
                <Component :is="Component" />
            </div>
        </router-view>
    </AdminLayout>
</template>

<style>
.hidden-scrollbar {
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.hidden-scrollbar::-webkit-scrollbar {
    display: none;
}
.scroll-container {
    overflow: hidden;
    position: relative;
}

.scroll-container-inner {
    overflow-y: scroll;
    height: 100%;
    padding-right: 15px;
    box-sizing: content-box;
}

.scroll-container-inner::-webkit-scrollbar {
    display: none;
}
.touch-scroll {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

.touch-scroll::-webkit-scrollbar {
    display: none;
}
</style>
