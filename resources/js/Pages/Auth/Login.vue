<script setup>
import {ref} from "vue"
import BigLogo from "@/components/AllApp/BigLogo.vue";
import InputForm from "@/components/Auth/InputForm.vue";
import { useAuthStore } from "@/Stores/auth";
const authStore = useAuthStore() ;
const form = ref({
    email: "",
    password: "",
})
 
</script>
<template>
    <div class="flex flex-col justify-center px-4 py-8 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900  ">
        <!-- شعار أو عنوان -->
        <BigLogo>
            <h2
                class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                Sign in to your account
            </h2>
        </BigLogo>
        <!-- الفورم -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md lg:max-w-lg">
            <div class="bg-white dark:bg-gray-800   py-8 px-6 shadow rounded-3xl sm:px-10">
                <form class="space-y-6" @submit.prevent="authStore.handleLogin(form)" method="POST">
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
                        :errorMessage="authStore.erroes.email"

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
                        :errorMessage="authStore.erroes.password"
                    />

                    <!-- زر تسجيل الدخول -->
                    <div>
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-base"
                        >
                            Sign in
                        </button>
                    </div>
                </form>

                <!-- رابط التسجيل -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    dont have acount ?
                    <router-link
                    :to="{name:'register'}"
                    class="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Register
                    </router-link>
                </p>
            </div>
        </div>
    </div>
</template>
