<script setup>
import { defineProps ,onMounted ,ref } from "vue";
import { useRoute } from "vue-router";
import {useAuthStore } from "@/Stores/auth"

const authStore = useAuthStore();

const route = useRoute();

const prpos = defineProps({
    menuItems: {
        type: Array,
        required: true,
    },

});
const isAuth = ref(false);
onMounted(() => {
    console.log(localStorage.getItem("authUser"));

    if(authStore.user){
        isAuth.value = true;
    }else{
        isAuth.value = false;
    }
});

console.log(prpos.isUser);
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
                    v-if="isAuth === item.auth "
                >
                    {{ item.name }}
                </router-link>
            </div>
        </div>
    </div>
</template>
