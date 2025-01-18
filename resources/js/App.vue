<script setup>
import { ref } from 'vue'; // تأكد من أنك استوردت ref
import Navigation from "@/Layouts/Navigation.vue";

const menuItems = [
    { name: "Users", to: "adminUsers", auth: true, allUser: false, role: "admin" },
    { name: "Subjects", to: "adminSubjects", auth: true, allUser: false, role: "admin" },
    {name:'Message',to:"message",auth:true,allUser:true},
    { name: "Home", to: "home", auth: true, allUser: true },
    { name: "Login", to: "login", auth: false, allUser: true },
    { name: "Register", to: "register", auth: false, allUser: true }
];

// تعريف toggleMobileMenu
const toggleMobileMenu = ref(false);
</script>


<template>
    <div>
        <Navigation @toggle="toggleMobileMenu" :menuItems="menuItems" />
    </div>

    <router-view v-slot="{ Component, route }">
    <div
        :key="route.name"
        class="dark:bg-gray-900 text-gray-900 dark:text-white min-h-[100vh] max-h-[100vh] pt-16"
    >
        <Component :is="Component" />
    </div>
</router-view>

</template>

<style>
/* لإخفاء شريط التمرير فقط */
.hidden-scrollbar {
    scrollbar-width: none; /* لإخفاء الشريط في Firefox */
    -ms-overflow-style: none; /* لإخفاء الشريط في Internet Explorer */
}

.hidden-scrollbar::-webkit-scrollbar {
    display: none; /* لإخفاء الشريط في Chrome و Edge و Safari */
}
.scroll-container {
    overflow: hidden; /* إخفاء الشريط */
    position: relative; /* ضروري لتثبيت عنصر التمرير */
}

.scroll-container-inner {
    overflow-y: scroll; /* السماح بالتمرير */
    height: 100%;
    padding-right: 15px; /* لتعويض المساحة المخفية لشريط التمرير */
    box-sizing: content-box;
}

.scroll-container-inner::-webkit-scrollbar {
    display: none; /* إخفاء الشريط في المتصفحات الحديثة */
}
.touch-scroll {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch; /* لتجربة تمرير سلسة */
}

.touch-scroll::-webkit-scrollbar {
    display: none; /* إخفاء الشريط */
} 

</style>
