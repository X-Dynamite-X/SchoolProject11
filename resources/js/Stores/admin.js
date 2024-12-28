import { defineStore } from "pinia";
import $, { error, get } from "jquery";

const csrf = () => $.get("/sanctum/csrf-cookie");

export const useAdminStore = defineStore("admin", {
    state: () => ({
        AllSubjects: [],
        AllUsers: [],
        Errors: [],

    }),
    getters: {
        users: (state) => state.AllUsers,
        errors: (state) => state.Errors,
    },
    actions: {
        async getUsers() {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "get",
                        url: "/api/admin/user ",
                        dataType: "json",
                        success: (data) => {
                            console.log("Fetching data from server...");

                            this.AllUsers = data.users;
                            resolve(data);
                        },
                        error: (error) => {
                            const errors =
                                error.responseJSON?.message ||
                                "An error occurred";
                            $("#errurMessageInputUser").text(errors);
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error);
                throw error;
            }
        },
        async createUser(data) {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "post",
                        url: `/api/admin/user`,
                        data: data,
                        success: (response) => {
                            console.log("User create successfully:", response);

                            this.AllUsers.push(response.user);
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("User create Error:", error);
                            console.log(error.responseJSON.errors);

                            this.Errors = error.responseJSON.errors;
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("User create Error:", error);
                throw error;
            }
        },
        async deleteUser(data) {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/admin/user/${data.id}`,
                        success: async (response) => {
                            console.log("User deleted successfully:", response);
                            console.log(response);
                            this.AllUsers = this.AllUsers.filter(
                                (user) => user.id !== data.id
                            );
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("User Delete Error:", error);
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("Unexpected Error During User Deletion:", error);
                throw error;
            }
        },
        async updateUser(data) {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "put",
                        url: `/api/admin/user/${data.id}`,
                        data: {
                            roles: data.roles[0].name,
                        },
                        success: (response) => {
                            console.log(this.AllUsers[data.id]);
                            this.AllUsers[data.id] = response.user;
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("User Update Error:", error);
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("User Update Error:", error);
                throw error;
            }
        },
        clearErrors() {
            this.Errors = {}; // طريقة لإعادة تعيين الأخطاء
        },
        async getSubjects(url = "/api/admin/subject", keyword = "") {
            const cacheKey = `${url}?keyword=${keyword}`;
            if (this.subjectCache[cacheKey]) {
                console.log("Fetching from cache:", cacheKey);
                return this.subjectCache[cacheKey];
            }
            await csrf();
            try {
                const response = await axios.get(url, { params: { keyword } }); // تمرير الكلمة كمعلومة
                console.log("Fetching from API:", cacheKey);
                this.subjectCache[cacheKey] = response.data.subjects;
                console.log(response.data.subjects);

                return response.data.subjects;
            } catch (error) {
                console.error("User Fetch Error:", error);
                throw error;
            }
        },
    },
});
