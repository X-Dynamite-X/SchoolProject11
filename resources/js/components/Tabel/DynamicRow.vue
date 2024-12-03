

<script setup>
defineProps({
  item: Object, // بيانات الصف
  columns: {
    type: Array, // أسماء الحقول
    required: true,
  },
});
</script>

<template>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
      <!-- عرض الأعمدة -->
      <td
        v-for="(column, index) in columns"
        :key="index"
        class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"
      >
        <!-- التحقق من وجود custom slot -->
        <slot :name="`column-${column}`" :item="item" :field="column">
          <!-- العرض الافتراضي إذا لم يكن هناك slot -->
          {{ item[column] }}
        </slot>
      </td>

      <!-- عرض الإجراءات -->
      <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
        <slot name="actions" :item="item"></slot>
      </td>
    </tr>
  </template>
