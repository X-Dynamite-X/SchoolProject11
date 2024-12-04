<script setup>
    defineProps({
        nameFeild:{
            type:String,
            required:true
        }
    })
</script>
<template>
    <th
        class="px-4 py-2 text-center border-b border-blue-200 dark:border-blue-700"
    >
        {{ nameFeild }}
    </th>
</template>
