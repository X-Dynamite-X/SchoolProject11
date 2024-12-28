<script setup>
defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
});
defineEmits(['update:modelValue']);
</script>

<template>
<select
    id="rows-per-page"
    :value="modelValue"
    @change="$emit('update:modelValue', Number($event.target.value))"
    class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
>
    <option value="5">5 Rows</option>
    <option value="10">10 Rows</option>
    <option value="25">25 Rows</option>
    <option value="50">50 Rows</option>
</select>

</template>
