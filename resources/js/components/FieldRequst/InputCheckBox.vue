<script setup>
import { ref } from "vue";

// استقبال البيانات كـ props
defineProps({
    options: {
        type: Array,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    id: {
        type: String,
        required: true,
    },
    required: {
        type: Boolean,
        default: false,
    },
    errorMessage: {
        type: Array,
        default: () => [],
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: "",
    },
});

// تعريف event للتواصل مع المكون الرئيسي
const emit = defineEmits(["create"]);

// تتبع الخيارات المحددة
const selectedOptions = ref([]);

// وظيفة لإضافة أو إزالة القيم
function toggleOption(optionId) {
    const index = selectedOptions.value.indexOf(optionId);
    if (index === -1) {
        selectedOptions.value.push(optionId);
    } else {
        selectedOptions.value.splice(index, 1);
    }
    emit("create", selectedOptions.value); // إرسال الخيارات المحددة
    console.log("Selected options:", selectedOptions.value);
}
</script>

<template>
    <form class="max-w-md mx-auto space-y-4">
        <!-- Label for the select -->
        <label
            :for="id"
            class="block text-lg font-semibold text-gray-900 dark:text-gray-100"
        >
            {{ label }}
        </label>

        <!-- Custom-styled container for checkboxes -->
        <div
            class="max-h-[25rem] overflow-scroll scroll-container-inner bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm p-4 space-y-3"
        >
            <!-- Render each option -->
            <div
                v-for="option in options"
                :key="option.id"
                class="flex items-center gap-3 p-2 rounded bg-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:bg-gray-900 ps-4 border border-gray-200 dark:border-gray-700"
            >
                <!-- Checkbox -->
                <input
                    type="checkbox"
                    :id="`checkbox-${option.id}`"
                    :value="option.id"
                    :disabled="disabled"
                    @change="toggleOption(option.id)"
                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                />
                <!-- Label for checkbox -->
                <label
                    :for="`checkbox-${option.id}`"
                    class="text-sm font-medium text-gray-900 dark:text-gray-300"
                >
                    {{ option.name }}
                </label>
            </div>
        </div>

        <!-- Display error message if provided -->
        <div  v-for="option in options"
        :key="option.id" >

        <p v-if="errorMessage['user_ids.'+option.id]" class="text-sm text-red-500">
            {{ errorMessage['user_ids.'+option.id][0] }}
        </p>
        </div>
    </form>
</template>
