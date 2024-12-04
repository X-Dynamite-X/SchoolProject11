import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router/router";

const csrf = () => axios.get("/sanctum/csrf-cookie");

export const useAuthStore = defineStore("auth", {
    state: () => ({
        authUser: null,
        authErrors: [],
        authRole: null,
        authStatus: null,
    }),
    getters: {
        user: (satae) => satae.authUser,
        errors: (satae) => satae.authErrors,
        status: (satae) => satae.authStatus,
        roles: (state) => state.authRole,
    },
    actions: {
        async getUser() {
            await csrf();
            try {
                const data = await axios.get("/api/user");
                this.authUser = data.data;
                this.authRole = data.data.roles;
            } catch (error) {
                this.authUser = null;
            }
        },
        async handleLogin(data) {
            this.authErrors = [];
            await csrf();
            try {
                await axios.post("/login", {
                    email: data.email,
                    password: data.password,
                });
                router.push("/");
            } catch (error) {
                if (error.response.status === 422) {
                    this.authErrors = error.response.data.errors;
                }
            }
        },
        async handleRegister(data) {
            this.authErrors = [];
            await csrf();
            try {
                await axios.post("/register", {
                    name: data.name,
                    email: data.email,
                    password: data.password,
                    password_confirmation: data.password_confirmation,
                });
                await this.handleLogout();
            } catch (error) {
                if (error.response?.status === 422) {
                    this.authErrors = error.response.data.errors;
                    console.log(error.response);
                }
            }
        },
        async handleLogout() {
            await csrf();
            await axios.post("/logout");
            this.router.push("/login");
            this.authUser = null;
            this.authRole = null;
        },
        async handleForgotPassword(data) {
            this.authErrors = [];
            await csrf();
            try {
                const response = await axios.post("/forgot-password", {
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
            await csrf();
            try {
                await axios.post("/reset-password", resetData);
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
                router.push("/profile");
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
