import { defineStore } from "pinia";
import axios from "axios"
import router from "@/router/router";

const csrf =()=> axios.get('/sanctum/csrf-cookie');

export const useAuthStore = defineStore('auth', {
    state: () => ({
        // authUser: null,
        // authRole: null,

        authUser: JSON.parse(localStorage.getItem('authUser')) || null,
        authRole:  JSON.parse(localStorage.getItem('authRole')) || null,
        authErrors: [],
        authStatus: null,
    }),
    getters: {
        user: (state) => state.authUser,
        errors: (state) => state.authErrors,
        roles: (state) => state.authRole,
        status: (state) => state.authStatus,
    },
    actions: {
        saveUserToStorage(user) {
            this.authUser = user;
            this.authRole = user.roles;
            localStorage.setItem('authUser', JSON.stringify(user));
            localStorage.setItem('authRole', JSON.stringify(user.roles));

        },
        clearUserFromStorage() {
            this.authUser = null;
            localStorage.removeItem('authUser');
            localStorage.removeItem('authRole');

        },
        async getToken() {
            await axios.get('/sanctum/csrf-cookie');
        },
        async getUser() {
            try {
                // await csrf();
                await this.getToken();
                const response = await axios.get('/api/user'); // تأكد من صحة التوجيه
                console.log(response.data);
                this.saveUserToStorage(response.data);



                // this.authUser = response.data;
            } catch (error) {
                console.error("User Fetch Error:", error);
                this.authUser = null;
                this.clearUserFromStorage();

            }
        },
        async handleLogin(data){
            this.authErrors=[];
            console.log(data);
            await this.getToken()
            try{
                const r = await axios.post('/login', {
                    email: data.email,
                    password: data.password,
                    });
                    console.log(r.data);
                    await this.getUser();
                router.push("/");
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
                await this.handleLogout();
            } catch (error) {
                if (error.response?.status === 422) {
                    this.authErrors = error.response.data.errors;
                    console.log(error.response);
                }
            }
        },
        async handleLogout() {
            await axios.post('/logout');
            this.clearUserFromStorage();
            this.authUser = null;
            router.push("/login");
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
