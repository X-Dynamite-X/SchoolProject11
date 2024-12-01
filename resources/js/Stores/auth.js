import { defineStore } from "pinia";
import axios from "axios"
import router from "@/router/router";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        authUser: null,
        authErrors: [],
        authStatus: null,
    }),
    getters: {
        user: (state) => state.authUser,
        errors: (state) => state.authErrors,
        status: (state) => state.authStatus,
    },
    actions: {
        async getToken() {
            await axios.get('/sanctum/csrf-cookie');
        },
        async getUser() {
            await this.getToken();
            try {
                const data = await axios.get("/api/user");
                this.authUser = data.data;
            } catch (error) {
                console.error("Failed to fetch user:", error);
                this.authUser = null;
            }
        },
        async handleLogin(data) {
            this.authErrors = [];
            let x = await this.getToken();

            console.log(x);
            try {
                await axios.post('/login', {
                    email: data.email,
                    password: data.password,
                });
                router.push("/");
            } catch (error) {
                console.log(error);
                if (error.response?.status === 422) {
                    this.authErrors = error.response.data.errors;
                }
            }
        },
        async handleRegister(data) {
            this.authErrors = [];
            await this.getToken();
            try {
                await axios.post('/register', {
                    name: data.name,
                    email: data.email,
                    password: data.password,
                    password_confirmation: data.password_confirmation,
                });
                await axios.post('/logout');
                router.push("/login");
            } catch (error) {
                if (error.response?.status === 422) {
                    this.authErrors = error.response.data.errors;
                    console.log(error.response);
                }
            }
        },
        async handleLogout() {
            await axios.post('/logout');
            router.push("/login");
            this.authUser = null;
        },
        async handleForgotPassword(data) {
            this.authErrors = [];
            await this.getToken();
            try {
                const response = await axios.post('/forgot-password', {
                    email: data,
                });
                this.authStatus = response.data.status;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.authErrors = error.response.data.errors;
                }
            }
        },
        async handleResetPassword(resetData) {
            this.authErrors = [];
            await this.getToken();
            try {
                await axios.post('/reset-password', resetData);
                router.push("/login");
            } catch (error) {
                if (error.response?.status === 422) {
                    this.authErrors = error.response.data.errors;
                }
            }
        },
        async updateProfile(data) {
            try {
                const response = await axios.put(`/api/v1/user/update`, data);
                router.push('/profile');
                this.authUser = response.data.profile;
            } catch (error) {
                console.error("Error updating profile:", error);
                if (error.response?.status === 422) {
                    this.authErrors = error.response.data.errors;
                }
            }
        },
        clearErrors() {
            this.authErrors = [];
        },
    },
});
