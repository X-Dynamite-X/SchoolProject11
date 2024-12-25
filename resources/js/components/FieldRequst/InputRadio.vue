<script setup>
import { ref } from "vue";

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: "text",
    },
    autocomplete: {
        type: String,
        default: "off",
    },
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
    options: {
        type: Array,
        default: [],
    },
    errorMessage: {
        type: Array,
        default: "",
    },
 
});



const emit = defineEmits(["update:modelValue"]);
function updateModelValue(value) {
     emit("update:modelValue", value);
}

</script>

<template>
    <div class="w-full">
        <!-- التسمية -->
        <label
            :for="id"
            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <!-- القائمة -->
        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <li
                 v-for="option in options"
                :key="option.value"
                class="flex items-center justify-between p-3 rounded-lg border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 shadow-sm hover:shadow-md transition-all"
            >
                <!-- النص -->
                <label
                    :for="`${id}-${option.value}`"
                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 flex-1 cursor-pointer"
                >
                    {{ option.label }}
                </label>

                <!-- المدخل -->
                <input
                    :type="type"
                    :name="name"
                    :id="`${id}-${option.value}`"
                    :autocomplete="autocomplete"
                    :placeholder="placeholder"
                    :required="required"
                    :value="option.value"
                    :checked="modelValue === option.value"
                    @input="updateModelValue($event.target.value)"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-500 dark:text-gray-100 dark:placeholder-gray-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 cursor-pointer"
                />
            </li>
        </ul>

        <!-- رسالة الخطأ -->
        <p v-if="errorMessage" class="mt-2 text-sm text-red-600">
            {{ errorMessage }}
        </p>
    </div>
</template>
