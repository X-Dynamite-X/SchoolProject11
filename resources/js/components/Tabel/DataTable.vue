<template>
    <table class="table-auto w-full border-collapse">
        <thead>
            <tr>
                <th
                    v-for="column in columns"
                    :key="column.key"
                    class="px-4 py-2 border-b bg-gray-100 dark:bg-gray-800 text-left text-gray-700 dark:text-gray-300"
                >
                    {{ column.label }}
                </th>
            </tr>
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

<script setup>
defineProps({
    data: Array, // البيانات المراد عرضها
    loading: Boolean, // حالة التحميل
    columns: Array, // تعريف الأعمدة
});
</script>
