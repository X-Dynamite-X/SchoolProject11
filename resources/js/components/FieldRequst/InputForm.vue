<script setup>
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
    errorMessage: {
        type: Array,
        default: "",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <div>
         <label
            :for="id"
            class="block text-sm font-medium text-gray-700 dark:text-gray-200"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div class="mt-2">
            <input
                :type="type"
                :name="name"
                :id="id"
                :autocomplete="autocomplete"
                :placeholder="placeholder"
                :required="required"
                :disabled="disabled"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                class="border border-gray-300 bg-white px-3 py-2 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 block w-full p-2.5 sm:text-base dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                :class="
                    errorMessage
                        ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700  focus:ring-red-500 dark:bg-gray-700 focus:border-red-500  dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                        : ''
                "
            />
        </div>

        <!-- رسالة الخطأ -->

        <div v-for="error in errorMessage" :key="error">
            <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-500">
                {{ error }}
            </p>
        </div>
    </div>
</template>
