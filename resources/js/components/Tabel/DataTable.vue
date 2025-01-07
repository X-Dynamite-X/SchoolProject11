<script setup>
defineProps({
    data: Array, // البيانات المراد عرضها
    loading: Boolean, // حالة التحميل
    columns: Array, // تعريف الأعمدة
    id: String, // معرّف الجدول
    availableData:{
        type:Boolean,
        default:true
    },
});

const emit = defineEmits(["sort"]); // تعريف الحدث 'sort'
</script>

<template>
    <table
        :id="id"
        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-300 overflow-scroll"
    >
        <thead
            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
        >
            <slot name="header" />
        </thead>
        <tbody>
            <slot name="row" :item="item" v-for="item in data" :key="item.id">
                <!-- يتم تعريف الصفوف من خلال DynamicRow -->
            </slot>
        </tbody>
    </table>

    <!-- عرض رسالة عند التحميل -->
    <div v-if="loading" class="mt-4 text-center text-gray-500">Loading...</div>

    <!-- عرض رسالة عند عدم وجود بيانات -->
    <div v-else-if="!data.length && availableData  " class="mt-4 text-center text-gray-500">
        No data available.
    </div>
</template>
