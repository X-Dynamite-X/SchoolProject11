import { defineStore } from "pinia";
import axios from "axios"
import router from "@/router/router";

const csrf =()=> axios.get('/sanctum/csrf-cookie');

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
            try {
                await csrf();
                await this.getToken();
                const response = await axios.get('/api/user'); // تأكد من صحة التوجيه
                this.authUser = response.data;
            } catch (error) {
                console.error("User Fetch Error:", error);
                this.authUser = null;
            }
        },
        async handleLogin(data){
            // this.authErrors=[];
            console.log(data);
            await this.getToken()
            try{
                const r = await axios.post('/login', {
                    email: data.email,
                    password: data.password,
                    });
                    console.log(r.data);
                    await this.getUser();
                // router.push("/");
            }
            catch(error){
                if(error.response.status === 422){
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
