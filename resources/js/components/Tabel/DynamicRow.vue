<script setup>
defineProps({
    item: Object, // البيانات الخاصة بالصف
    columns: Array, // تعريف الأعمدة مع الحقول
});
</script>

<template>
    <tr
        class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
        <!-- عرض الأعمدة التي تحمل showInTabel: true -->
        <td
            v-for="column in columns.filter(col => col.showInTabel)"
            :key="column.key"
            class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"
            >
            <!-- عرض الحقول الديناميكية -->
            <slot
                :name="`column-${column.key}`"
                :item="item"
                :field="column.key"
            >
                <!-- العرض الافتراضي إذا لم يكن هناك slot -->
                {{ item[column.key] }}
            </slot>
        </td>

        <!-- عرض الإجراءات -->
        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
            <slot name="column-actions" :item="item"></slot>
        </td>
    </tr>
</template>
