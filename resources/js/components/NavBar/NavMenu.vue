<script setup>
import { defineProps, ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { useAuthStore } from "@/Stores/auth";

const authStore = useAuthStore();
const route = useRoute();
defineProps({
    menuItems: {
        type: Array,
        required: true,
    },
});
const isAuth = ref(false);
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
</script>

<template>
    <div class="hidden sm:ml-6 sm:block">
        <div class="flex space-x-4">
            <div v-for="item in menuItems" :key="item.name">
                <router-link
                    :to="{ name: item.to }"
                    :class="[
                        'rounded-md px-3 py-2 text-sm font-medium',
                        route.name === item.to
                            ? 'bg-gray-900 text-white'
                            : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                    ]"
                    aria-current="page"
                    v-if="
                        (isAuth === item.auth && item.allUser) ||
                        (isAuth === item.auth &&
                            item.role === authStore.roles.name)
                    "
                >
                    {{ item.name }}
                </router-link>
            </div>
        </div>
    </div>
</template>
