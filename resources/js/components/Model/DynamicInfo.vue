<script setup>
import { ref ,defineEmits} from "vue";

// Props
defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: "Info",
    },
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Object,
        required: true,
    },
});
const emit = defineEmits(["close"]);

// Functions
const closeModal = () => {
  emit("close"); // يجب تعريف emit هنا
};
</script>
<template>
    <div
        v-if="show"
        class="fixed inset-0 z-10 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="flex min-h-full items-center justify-center p-4 text-center sm:p-0"
        >
            <div
                class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-700 shadow-xl transition-all sm:w-6/12"
            >
                <!-- Header -->
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mt-3  text-center sm:ml-4 sm:mt-0 sm:text-left w-full"
                        >
                            <h3
                                class="text-base text-center  font-semibold leading-6 text-gray-900 dark:text-gray-100"
                                id="modal-title"
                            >
                                {{ title }}
                            </h3>
                            <!-- Content -->
                            <div class="mt-4">

                                <slot name="content">
                                    <!-- محتوى افتراضي إذا لم يتم تقديم محتوى داخل الـ slot -->
                                    <div
                                    v-for="column in columns"
                                    :key="column.key"
                                >
                                    <div>
                                        <p>
                                            <slot
                                                :name="`column-${column.key}`"
                                                :data="data"
                                                :column="column"
                                            >
                                                <!-- العرض الافتراضي إذا لم يكن هناك slot -->
                                                <strong
                                                    >{{ column.label }}:</strong
                                                >
                                                {{ data[column.key] }}
                                            </slot>
                                        </p>
                                    </div>
                                </div>
                                </slot>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                >
                    <button
                        type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-600 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto"
                        @click="closeModal()"
                    >
                        Cancel
                    </button>
                    <slot name="actions">
                        <!-- أزرار إضافية -->
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>
