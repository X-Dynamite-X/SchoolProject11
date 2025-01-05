<script setup>
import { ref, defineEmits } from "vue";
import InputForm from "@/components/FieldRequst/InputForm.vue";
import { useAdminStore } from "@/Stores/admin";
const adminStore = useAdminStore();

// Props

const prpos = defineProps({
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

const emit = defineEmits(["close", "create"]);

const closeModal = () => {
    console.log(adminStore.errors);
    adminStore.clearErrors();
    emit("close");
};
const createModal = () => {
    emit("create", prpos.data); // إرسال البيانات إلى المكون الأعلى
};
</script>
<template>
    <div
        v-if="prpos.show"
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
                            class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full"
                        >
                            <h3
                                class="text-base text-center font-semibold leading-6 text-gray-900 dark:text-gray-100"
                                id="modal-title"
                            >
                                {{ prpos.title }}
                            </h3>
                            <!-- Content -->
                            <div class="mt-4">
                                <slot name="content">
                                    <!-- محتوى افتراضي إذا لم يتم تقديم محتوى داخل الـ slot -->
                                    <div
                                        v-for="column in prpos.columns"
                                        :key="column.key"
                                    >
                                        <slot
                                            :name="`column-${column.key}`"
                                            :data="data"
                                            :column="column"
                                        >
                                            <!-- العرض الافتراضي إذا لم يكن هناك slot -->
                                            <InputForm
                                                v-if="
                                                    column.key !== 'id' &&
                                                    column.showInCreate
                                                "
                                                :label="column.label"
                                                :name="column.name"
                                                :id="column.key"
                                                :type="column.type"
                                                :modelValue="
                                                    prpos.data[column.key]
                                                "
                                                v-model="prpos.data[column.key]"
                                                :placeholder="
                                                    column.placeholder
                                                "
                                                :required="column.required"
                                                :errorMessage="
                                                    adminStore.errors[
                                                        column.name
                                                    ] || null
                                                "
                                                :autocomplete="
                                                    column.autocomplete
                                                "
                                            />
                                        </slot>
                                    </div>
                                </slot>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                >
                    <button
                        type="button"
                        class="mt-3 mx-1 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-600 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto"
                        @click="closeModal()"
                    >
                        Cancel
                    </button>
                    <slot name="actionsCreateBtn">
                        <button
                            type="button"
                            class="mt-3 mx-1 inline-flex w-full justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-700 sm:mt-0 sm:w-auto"
                            @click="createModal()"
                        >
                            Create
                        </button>
                    </slot>
                    <slot name="actions"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
