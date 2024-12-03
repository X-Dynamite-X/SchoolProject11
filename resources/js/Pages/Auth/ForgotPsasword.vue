<script setup>
import { ref,onMounted , onUnmounted} from "vue";
import { useAuthStore } from "@/Stores/auth";
import BigLogo from "@/components/AllApp/BigLogo.vue";
import InputForm from "@/components/FieldRequst/InputForm.vue";
const authStore = useAuthStore();

const email = ref("");
let unregisterRouterHook;

onMounted(() => {
  unregisterRouterHook = router.afterEach(() => {
    authStore.clearErrors(); // تفريغ الأخطاء عند تغيير المسار
  });
});

onUnmounted(() => {
  // إزالة المراقبة عند تدمير المكون
  if (unregisterRouterHook) {
    unregisterRouterHook();
  }
});
</script>

<template>
    <div
        class="flex flex-1 flex-col justify-center px-6 py-12 lg:px-8 "
    >

        <BigLogo>
            <h2
                class="mt-6 text-center text-2xl font-bold tracking-tight sm:text-3xl"
            >
                Sign in to your account
            </h2>
        </BigLogo>
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" @submit.prevent=" authStore.handelForgotPsasword(email)">

                <InputForm
                        type="email"
                        name="email"
                        id="email"
                        autocomplete="email"
                        required="true"
                        placeholder="Email Address"
                        label="Your email:"
                        v-model="email"
                        :errorMessage="authStore.errors.email"
                    />
                <div
                    v-if="authStore.status"
                    class="m-2 p-2 text-green-900 font-semibold bg-green-300 rounded-md"
                >
                    {{ authStore.status }}
                </div>
                <div>
                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
