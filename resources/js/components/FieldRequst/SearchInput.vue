<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    modelValue: String,
    placeholder: {
        type: String,
        default: "Search...",
    },
});
const emit = defineEmits(["update:modelValue"]);

const inputValue = ref(props.modelValue);
const delay = ref(null);

// مراقبة القيمة لإرسال التحديث مع التأخير
watch(inputValue, (newValue) => {
    if (delay.value) clearTimeout(delay.value); // تنظيف المؤقت السابق
    delay.value = setTimeout(() => {
        // إرسال القيمة إلى المكون الأب
        emit("update:modelValue", newValue);
    }, 500); // تأخير لمدة 500 مللي ثانية
});
</script>

<template>
    <div class="items-center space-x-2  ">
        <div class="relative  ">
            <input
                id="searchInput"
                type="text"
                v-model="inputValue"
                :placeholder="props.placeholder"
                class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            />
            <div
                class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none"
            >
                <label for="searchInput">
                    <slot name="icon"></slot>
                </label>
            </div>
        </div>
    </div>
</template>
