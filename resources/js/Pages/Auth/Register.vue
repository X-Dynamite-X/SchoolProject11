<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/Stores/auth";
import BigLogo from "@/components/AllApp/BigLogo.vue";
import InputForm from "@/components/FieldRequst/InputForm.vue";

const authStore = useAuthStore();
const loading = ref(false);
const router = useRouter();

const form = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

async function sendData(data) {
    loading.value = true;
    try {
        await authStore.handleRegister(data);
        router.push({ name: "welcome" });
    } catch (error) {
        console.log(error);
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <template v-if="loading">
        <div class="flex items-center justify-center h-screen">
            <div
                class="flex flex-col items-center justify-center bg-white border border-gray-300 rounded-lg shadow-lg p-6 w-80 dark:bg-gray-800 dark:border-gray-700"
            >
                <div role="status" class="relative">
                    <svg
                        aria-hidden="true"
                        class="w-16 h-16 text-gray-200 animate-spin dark:text-gray-700 fill-blue-500"
                        viewBox="0 0 100 101"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <!-- SVG paths -->
                    </svg>
                </div>
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
            <BigLogo>
                <h2
                    class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl"
                >
                    Register your account
                </h2>
            </BigLogo>
            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md lg:max-w-lg">
                <div
                    class="bg-white dark:bg-gray-800 py-8 px-6 shadow rounded-3xl sm:px-10"
                >
                    <form class="space-y-6" @submit.prevent="sendData(form)">
                        <InputForm
                            type="text"
                            name="name"
                            id="username"
                            autocomplete="username"
                            :required="true"
                            v-model="form.name"
                            placeholder="Enter User Name"
                            label="Enter User Name"
                            :errorMessage="authStore.errors.name"
                        />
                        <InputForm
                            type="email"
                            name="email"
                            id="email"
                            autocomplete="email"
                            :required="true"
                            v-model="form.email"
                            placeholder="Email Address"
                            label="Email Address"
                            :errorMessage="authStore.errors.email"
                        />
                        <InputForm
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            :required="true"
                            v-model="form.password"
                            placeholder="Password"
                            label="Password"
                            :errorMessage="authStore.errors.password"
                        />
                        <InputForm
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            :required="true"
                            v-model="form.password_confirmation"
                            placeholder="Password Confirmation"
                            label="Password Confirmation"
                        />
                        <div>
                            <button
                                type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-base"
                            >
                                Register
                            </button>
                        </div>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-600">
                        Already have an account?
                        <router-link
                            @click="authStore.clearErrors()"
                            :to="{ name: 'login' }"
                            class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-cyan-600 dark:hover:text-cyan-500"
                        >
                            Sign in
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </template>
</template>
