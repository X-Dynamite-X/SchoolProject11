<script setup>
import { defineProps } from "vue";
import { useRoute } from "vue-router";
const route = useRoute();
defineProps({
    menuItems: {
        type: Array,
        required: true,
    },
    isUser: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="sm:hidden bg-gray-800">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <div v-for="item in menuItems" :key="item.name">
                <router-link
                    :to="{ name: item.to }"
                    v-if="isUser === item.auth"

                    :class="[
                        'block rounded-md px-3 py-2 text-base font-medium',
                        route.name === item.to
                            ? 'bg-gray-900 text-white'
                            : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                    ]"
                    aria-current="page"
                >
                    {{ item.name }}
                </router-link>
            </div>
        </div>
        <button
            class="w-full bg-gray-900 text-white py-2 text-center"
            @click="$emit('close')"
        >
            Close Menu
        </button>
    </div>
</template>
