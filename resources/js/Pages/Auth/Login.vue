<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

import BigLogo from "@/components/AllApp/BigLogo.vue";
import InputForm from "@/components/FieldRequst/InputForm.vue";
import { useAuthStore } from "@/Stores/auth";

const authStore = useAuthStore();
const form = ref({
    email: "",
    password: "",
});
const loading = ref(false);
const router = useRouter();

async function sendData(data) {
    loading.value = true;
     try {
        await authStore.handleLogin(data); // انتظار انتهاء تسجيل الدخول
    } catch (error) {
        console.log(error);
        
     } finally {
        loading.value = false;
    }
}

onMounted(() => {
    document.title = router.currentRoute.value.name || "Sign In";
});
</script>

<template>
    <template v-if="loading">
        <div class="flex items-center justify-center h-screen">
            <div
                class="flex flex-col items-center justify-center bg-white border border-gray-300 rounded-lg shadow-lg p-6 w-80 dark:bg-gray-800 dark:border-gray-700"
            >
                <!-- دائرة التحميل -->
                <div role="status" class="relative">
                    <svg
                        aria-hidden="true"
                        class="w-16 h-16 text-gray-200 animate-spin dark:text-gray-700 fill-blue-500"
                        viewBox="0 0 100 101"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor"
                        />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill"
                        />
                    </svg>
                </div>
                <!-- النص -->
                <span
                    class="mt-4 text-lg font-semibold text-gray-800 dark:text-white"
                >
                    Loading, please wait...
                </span>
            </div>
        </div>
    </template>

    <template v-else>
        <div
            class="flex flex-col justify-center px-4 py-8 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900"
        >
            <!-- شعار أو عنوان -->
            <BigLogo>
                <template #default>
                    <h2
                        class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl"
                    >
                        Sign in to your account
                    </h2>
                </template>
            </BigLogo>
            <!-- الفورم -->
            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md lg:max-w-lg">
                <div
                    class="bg-white dark:bg-gray-800 py-8 px-6 shadow rounded-3xl sm:px-10"
                >
                    <form
                        class="space-y-6"
                        @submit.prevent="sendData(form)"
                        method="POST"
                    >
                        <!-- البريد الإلكتروني -->
                        <InputForm
                            type="email"
                            name="email"
                            id="email"
                            autocomplete="email"
                            required="true"
                            placeholder="Email Address"
                            label="Email Address"
                            v-model="form.email"
                            :errorMessage="authStore.errors.email"
                        />
                        <!-- كلمة المرور -->
                        <InputForm
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="password"
                            required="true"
                            placeholder="Password"
                            label="Password"
                            v-model="form.password"
                            :errorMessage="authStore.errors.password"
                        />

                        <!-- رسالة الخطأ -->
                         

                        <!-- زر تسجيل الدخول -->
                        <div>
                            <button
                                type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-base"
                                :disabled="loading"
                            >
                                <span v-if="loading" class="loader"></span>
                                <span v-else>Sign in</span>
                            </button>
                        </div>
                    </form>

                    <!-- رابط التسجيل -->
                    <p
                        class="mt-6 text-center text-sm text-gray-600 dark:text-gray-200"
                    >
                        <router-link
                            :to="{ name: 'ForgotPsasword' }"
                            class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-cyan-600 dark:hover:text-cyan-500"
                        >
                            Forgot password?
                        </router-link>
                    </p>
                    <p
                        class="mt-6 text-center text-sm text-gray-600 dark:text-gray-200"
                    >
                        Don't have an account?
                        <router-link
                            @click="authStore.clearErrors()"
                            :to="{ name: 'register' }"
                            class="font-medium text-indigo-600 hover:text-indigo-500"
                        >
                            Register
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </template>
</template>
