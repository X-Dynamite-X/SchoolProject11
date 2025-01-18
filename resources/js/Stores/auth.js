import { defineStore } from "pinia";
import router from "@/router/router";

const csrf = () => {
    return $.ajax({
        url: "/sanctum/csrf-cookie",
        method: "GET",
    });
};

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
            if (this.authUser) {
                return this.authUser;
            } else {
                await csrf();
                try {
                    const response = await $.ajax({
                        url: "/api/user",
                        method: "GET",
                    });
                    this.authUser = response;
                    this.authRole = response.user.roles[0];

                } catch (error) {
                    this.authUser = null;
                }
            }
        },
        async handleLogin(data) {
            this.authErrors = [];
            await csrf();
            try {
                await $.ajax({
                    url: "/login",
                    method: "POST",
                    data: {
                        email: data.email,
                        password: data.password,
                    },
                });
                this.getUser();
                this.router.push("/");
            } catch (error) {
                if (error.status === 422) {
                    this.authErrors = error.responseJSON.errors;
                }
            }
        },
        async handleRegister(data) {
            this.authErrors = [];
            await csrf();
            try {
                await $.ajax({
                    url: "/register",
                    method: "POST",
                    data: {
                        name: data.name,
                        email: data.email,
                        password: data.password,
                        password_confirmation: data.password_confirmation,
                    },
                });
                await this.handleLogout();
            } catch (error) {
                if (error.status === 422) {
                    this.authErrors = error.responseJSON.errors;
                }
            }
        },
        async handleLogout() {
            await csrf();
            await $.ajax({
                url: "/logout",
                method: "POST",
            });
            this.router.push("/login");
            this.authUser = null;
            this.authRole = null;
        },
        async handleForgotPassword(data) {
            this.authErrors = [];
            await csrf();
            try {
                const response = await $.ajax({
                    url: "/forgot-password",
                    method: "POST",
                    data: { email: data },
                });
                this.authStatus = response.status;
            } catch (error) {
                if (error.status === 422) {
                    this.authErrors = error.responseJSON.errors;
                }
            }
        },
        async handleResetPassword(resetData) {
            this.authErrors = [];
            await csrf();
            try {
                await $.ajax({
                    url: "/reset-password",
                    method: "POST",
                    data: resetData,
                });
                router.push("/login");
            } catch (error) {
                if (error.status === 422) {
                    this.authErrors = error.responseJSON.errors;
                }
            }
        },
        async updateProfile(data) {
            try {
                const response = await $.ajax({
                    url: `/api/v1/user/update`,
                    method: "PUT",
                    data: data,
                });
                router.push("/profile");
                this.authUser = response.profile;
            } catch (error) {
                if (error.status === 422) {
                    this.authErrors = error.responseJSON.errors;
                }
            }
        },
        clearErrors() {
            this.authErrors = [];
        },
    },
});
