<script setup>
import { ref,defineProps } from 'vue';
import { useRouter } from 'vue-router';
import UserMenu from '../components/NavBar/UserMenu.vue';
import Logo from '../components/NavBar/Logo.vue';
import NavMenu from '../components/NavBar/NavMenu.vue';
import NavMenuButton from '../components/NavBar/NavMenuButton.vue';
import MobileMenu from '../components/NavBar/MobileMenu.vue';

const prpos =defineProps({
    menuItems: {
        type: Array,
        required: true,
    },
})

const showMobileMenu = ref(false);

// دالة لتغيير حالة ظهور القائمة
const toggleMobileMenu = () => {
showMobileMenu.value = !showMobileMenu.value;
 
};

</script>
<template>
    <nav class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
          <!-- زر القائمة للجوال -->
          <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
            <button
                type="button"
                class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                @click="toggleMobileMenu( )"
                >
                <span class="sr-only">Toggle menu</span>
                <svg
                    class="block h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                >
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                    />
                </svg>
            </button>

          </div>
          <!-- الشعار والقوائم -->
          <div class="flex flex-1 items-center justify-between">
            <Logo />
            <NavMenu :menuItems="prpos.menuItems" />
            <UserMenu />
        </div>

        </div>
      </div>
      <MobileMenu v-if="showMobileMenu" @close="toggleMobileMenu(false)" :menuItems="prpos.menuItems"  />

    </nav>
  </template>
