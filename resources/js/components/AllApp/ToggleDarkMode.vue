<script setup>
import { ref, onMounted } from "vue";

// حالة الوضع
const isDarkMode = ref(false);

// تفعيل الوضع الليلي بناءً على الثيم المخزن
const toggleDarkMode = () => {
    if (isDarkMode.value) {
        document.documentElement.classList.remove("dark"); // إزالة الوضع الليلي
        localStorage.setItem("theme", "light"); // تخزين الوضع الفاتح
    } else {
        document.documentElement.classList.add("dark"); // تفعيل الوضع الليلي
        localStorage.setItem("theme", "dark"); // تخزين الوضع الليلي
    }
    isDarkMode.value = !isDarkMode.value; // تبديل الحالة
};

// تنفيذ الكود عند تحميل الصفحة
onMounted(() => {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        isDarkMode.value = true;
        document.documentElement.classList.add("dark");
    }
});
</script>

<template>
    <button @click="toggleDarkMode" class="ml-4">
        <template v-if="isDarkMode">
            <!-- أيقونة الوضع الليلي -->
            <svg
                width="2rem"
                height="2rem"
                viewBox="0 0 16 16"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path d="M7 3V0H9V3H7Z" fill="#fff"></path>
                <path d="M9 13V16H7V13H9Z" fill="#fff"></path>
                <path
                    d="M11 8C11 9.65685 9.65685 11 8 11C6.34315 11 5 9.65685 5 8C5 6.34315 6.34315 5 8 5C9.65685 5 11 6.34315 11 8Z"
                    fill="#fff"
                ></path>
                <path d="M0 9H3V7H0V9Z" fill="#fff"></path>
                <path d="M16 7H13V9H16V7Z" fill="#fff"></path>
                <path
                    d="M3.75735 5.17157L1.63603 3.05025L3.05025 1.63603L5.17157 3.75735L3.75735 5.17157Z"
                    fill="#fff"
                ></path>
                <path
                    d="M12.2426 10.8284L14.364 12.9497L12.9497 14.364L10.8284 12.2426L12.2426 10.8284Z"
                    fill="#fff"
                ></path>
                <path
                    d="M3.05025 14.364L5.17157 12.2426L3.75735 10.8284L1.63603 12.9498L3.05025 14.364Z"
                    fill="#fff"
                ></path>
                <path
                    d="M12.9497 1.63604L10.8284 3.75736L12.2426 5.17158L14.364 3.05026L12.9497 1.63604Z"
                    fill="#fff"
                ></path>
            </svg>
        </template>
        <template v-else>
            <!-- أيقونة الوضع الفاتح -->
            <svg
                width="2rem"
                height="2rem"
                viewBox="0 0 16 16"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M0 8.00002C0 4.75562 1.93132 1.9623 4.70701 0.707031L5.65436 1.65438C5.2352 2.51383 5 3.47946 5 4.50002C5 8.08987 7.91015 11 11.5 11C12.5206 11 13.4862 10.7648 14.3456 10.3457L15.293 11.293C14.0377 14.0687 11.2444 16 8 16C3.58172 16 0 12.4183 0 8.00002Z"
                    fill="#fff"
                ></path>
                <path
                    d="M11.5 7.00003L9 4.50003L11.5 2.00003L14 4.50003L11.5 7.00003Z"
                    fill="#fff"
                ></path>
            </svg>
        </template>
    </button>
</template>

<style>
/* تأثير سلس للتغيير */
html {
    transition: background-color 0.3s, color 0.3s;
}
</style>
