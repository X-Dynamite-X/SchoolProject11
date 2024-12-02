<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import {useAuthStore } from "@/Stores/auth"

const authStore = useAuthStore();

// حالة إظهار القائمة
const showMenu = ref(false);

// دالة لتبديل حالة القائمة عند الضغط على الصورة
const toggleMenu = () => {
  showMenu.value = !showMenu.value;
};

// دالة لإغلاق القائمة إذا تم النقر خارجها
const closeMenu = (event) => {
  const menu = document.getElementById("user-menu");
  const button = document.getElementById("user-menu-button");

  if (menu && !menu.contains(event.target) && !button.contains(event.target)) {
    showMenu.value = false;
  }
};

// إضافة مستمع الحدث عند تركيب المكون وإزالته عند إلغاء التركيب
onMounted(() => {
  document.addEventListener("mousedown", closeMenu);
});

onBeforeUnmount(() => {
  document.removeEventListener("mousedown", closeMenu);
});
</script>

<template>
  <div class="relative ml-3">
    <!-- زر القائمة (الصورة) -->
    <button
      id="user-menu-button"
      type="button"
      class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
      @click="toggleMenu"
    >
      <span class="sr-only">Open user menu</span>
      <img
        class="h-8 w-8 rounded-full"
        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
        alt=""
      />
    </button>

    <!-- القائمة المنسدلة -->
    <div
      v-if="showMenu"
      id="user-menu"
      class="absolute items-center right-0 z-10 mt-2 w-48 origin-top-right rounded-md text-gray-700 bg-white dark:bg-gray-600  dark:text-white py-1 shadow-lg ring-1 ring-black/5"
    >
      <a href="#" class="block px-4 py-2 text-sm ">Your Profile</a>
      <a href="#" class="block px-4 py-2 text-sm ">Settings</a>
      <button  @click="authStore.handleLogout()"  class="block px-4 py-2 text-sm   ">

        Logout
      </button>
    </div>
  </div>
</template>
