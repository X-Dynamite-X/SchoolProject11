
<script setup>
import { ref, watch } from "vue";

// Props for the alert
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
    duration: {
        type: Number,
        default: 3000, // 3 seconds
    },
});

// Dynamic classes
const show = ref(true);
const alertClass = ref("");
const iconClass = ref("");

function setClasses(title) {
    switch (title) {
        case "delete":
        case "danger":
        case "error":
            alertClass.value =
                "bg-red-50 text-red-800 dark:bg-gray-800 dark:text-red-400";
            iconClass.value = "text-red-500 dark:text-red-400";
            break;
        case "success":
            alertClass.value =
                "bg-green-50 text-green-800 dark:bg-gray-800 dark:text-green-400";
            iconClass.value = "text-green-500 dark:text-green-400";
            break;
        case "info":
            alertClass.value =
                "bg-blue-50 text-blue-800 dark:bg-gray-800 dark:text-blue-400";
            iconClass.value = "text-blue-500 dark:text-blue-400";
            break;
        case "warning":
            alertClass.value =
                "bg-yellow-50 text-yellow-800 dark:bg-gray-800 dark:text-yellow-400";
            iconClass.value = "text-yellow-500 dark:text-yellow-400";
            break;
        default:
            alertClass.value =
                "bg-gray-50 text-gray-800 dark:bg-gray-800 dark:text-gray-400";
            iconClass.value = "text-gray-500 dark:text-gray-400";
    }
}

// Set classes on mount
setClasses(props.title);

// Auto-hide after duration
watch(show, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            show.value = false;
        }, props.duration);
    }
});
</script>
<template>
    <div
        class="fixed top-20 right-3 w-1/4 z-50 transition-transform duration-300 transform"
        :class="{ 'translate-x-0': show, 'translate-x-full': !show }"
    >
        <div
            class="p-4 mb-4 flex items-center text-sm rounded-lg shadow-lg"
            :class="alertClass"
            role="alert"
        >
            <!-- Icon Section -->
            <svg
                aria-hidden="true"
                class="flex-shrink-0 w-5 h-5 mr-3"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                :class="iconClass"
            >
                <path
                    v-if="title === 'success'"
                    fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                    clip-rule="evenodd"
                ></path>
                <path
                    v-if="
                        title === 'danger' ||
                        title === 'delete' ||
                        title === 'error'
                    "
                    fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.682-1.36 3.447 0l5.52 9.82c.75 1.333-.213 3.08-1.725 3.08H4.462c-1.512 0-2.475-1.747-1.725-3.08l5.52-9.82zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-.25-5.25a.75.75 0 10-1.5 0v3.5a.75.75 0 101.5 0v-3.5z"
                    clip-rule="evenodd"
                ></path>
                <path
                    v-if="title === 'info'"
                    fill-rule="evenodd"
                    d="M18 10A8 8 0 11.868 5.877a.75.75 0 011.196-.92A6.5 6.5 0 1017.5 10a.75.75 0 011.5 0zm-7.25 3.25a.75.75 0 10-1.5 0v2.5a.75.75 0 001.5 0v-2.5zm0-7a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0V6.25z"
                    clip-rule="evenodd"
                ></path>
                <path
                    v-if="title === 'warning'"
                    d="M9.998 2.002a8 8 0 1 0 0 16 8 8 0 0 0 0-16zM11 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-3a.75.75 0 0 1-.75-.75V7a.75.75 0 0 1 1.5 0v3.25A.75.75 0 0 1 10 11z"
                ></path>
            </svg>

            <!-- Message Section -->
            <span class="font-medium">{{ message }}</span>
        </div>
    </div>
</template>
