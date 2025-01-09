<script setup>
defineProps({
    list: {
        type: Array,
        required: true,
    },
    authData: {
        type: Object,
        required: true,
    },
});
</script>
<template>
    <div>
        <ul class="text-red-500">
            <!-- {{ authData }} -->
            <li v-for="(item, index) in list" :key="index">
                {{
                    item.user1.id == authData.user.id
                        ? item.user2.name
                        : item.user1.name
                }}
            </li>
        </ul>
    </div>
</template>
