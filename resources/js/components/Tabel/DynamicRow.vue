

<script setup>
defineProps({
    item: Object, // البيانات الخاصة بالصف
    columns: Array, // تعريف الأعمدة مع الحقول
});
</script>

<template>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 text-center">
        <td
            v-for="column in columns"
            :key="column.key"
            class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"

        >
            <!-- استخدام slot ديناميكيًا لكل عمود -->
            <slot :name="`column-${column.key}`" :item="item" :field="column.key">
          <!-- العرض الافتراضي إذا لم يكن هناك slot -->
          {{ item[column.key] }}
        </slot>

        </td>
        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
        <slot name="column-actions" :item="item"></slot>
      </td>
    </tr>
</template>


