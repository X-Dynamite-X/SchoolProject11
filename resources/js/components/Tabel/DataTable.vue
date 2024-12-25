<script setup>
defineProps({
    data: Array, // البيانات المراد عرضها
    loading: Boolean, // حالة التحميل
    columns: Array, // تعريف الأعمدة
    id:String,
});
 
</script>


<template>
    <table :id="id" class="table-auto w-full border-collapse overflow-scroll">

        <thead>
            <slot name="header" />

        </thead>
        <tbody>
            <slot name="row" :item="item" v-for="item in data" :key="item.id">
                <!-- يتم تعريف الصفوف من خلال DynamicRow -->
            </slot>
        </tbody>
    </table>

    <!-- عرض رسالة عند التحميل -->
    <div v-if="loading" class="mt-4 text-center text-gray-500">
        Loading...
    </div>

    <!-- عرض رسالة عند عدم وجود بيانات -->
    <div v-else-if="!data.length" class="mt-4 text-center text-gray-500">
        No data available.
    </div>
</template>

