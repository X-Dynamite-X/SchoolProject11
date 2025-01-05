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
        subjects: (state) => state.AllSubjects,
        errors: (state) => state.Errors,
    },
    actions: {
        async getUsers() {
            if (this.AllUsers.length > 0) {
                return this.AllUsers;
            } else {
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
                                reject(error);
                            },
                        });
                    });
                } catch (error) {
                    console.error("User Fetch Error:", error);
                    throw error;
                }
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
                            console.log("update is done");

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
        async getSubjects() {
            if (this.AllSubjects.length > 0) {
                return this.AllSubjects;
            } else {
                await csrf();
                try {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            type: "get",
                            url: "/api/admin/subject ",
                            dataType: "json",
                            success: (data) => {
                                this.AllSubjects = data.subjects;
                                resolve(data);
                            },
                            error: (error) => {
                                const errors =
                                    error.responseJSON?.message ||
                                    "An error occurred";
                                reject(error);
                            },
                        });
                    });
                } catch (error) {
                    console.error("User Fetch Error:", error);
                    throw error;
                }
            }
        },
        async createSubject(data) {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "post",
                        url: `/api/admin/subject`,
                        data: data,
                        success: (response) => {
                            console.log(
                                "subject create successfully:",
                                response
                            );

                            this.AllSubjects.push(response.subject);
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("subject create Error:", error);
                            console.log(error.responseJSON.errors);

                            this.Errors = error.responseJSON.errors;
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("subject create Error:", error);
                throw error;
            }
        },
        async updateSubject(data) {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "put",
                        url: `/api/admin/subject/${data.id}`,
                        data: {
                            name: data.name,
                            success_mark: data.success_mark,
                            full_mark: data.full_mark,
                        },
                        success: (response) => {
                            console.log("update is done");
                            console.log(response);
                            // this.AllSubjects[response.subject.id] = response.subject

                            resolve(response);
                        },
                        error: (error) => {
                            console.error("subject Update Error:", error);
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("subject Update Error:", error);
                throw error;
            }
        },
        async deleteSubject(data) {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/admin/subject/${data.id}`,
                        success: async (response) => {
                            console.log(
                                "Subject deleted successfully:",
                                response
                            );
                            console.log(response);
                            this.AllSubjects = this.AllSubjects.filter(
                                (subject) => subject.id !== data.id
                            );
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("Subject Delete Error:", error);
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error(
                    "Unexpected Error During Subject Deletion:",
                    error
                );
                throw error;
            }
        },
        async createSubjectUsers(subject_id, data) {
            await csrf();
            console.log(data[0]);
            console.log(`/api/admin/subjectUsers/${subject_id}`);
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "post",
                        url: `/api/admin/subjectUsers/${subject_id}`,
                        data: {
                            user_ids: data[0],
                            subject_id: subject_id,
                        },
                        success: (response) => {

                            resolve(response);
                        },
                        error: (error) => {
                            console.error("subject create Error:", error);
                            console.log(error.responseJSON.errors);

                            this.Errors = error.responseJSON.errors;
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("subject create Error:", error);
                throw error;
            }
        },
        async updateSubjectUsers(data) {
            await csrf();
            try {
                console.log(data);

                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "put",
                        url: `/api/admin/subjectUsers/${data.subject_id}/${data.user_id}`,
                        data: data,
                        success: (response) => {
                            console.log(
                                "add User in Subject is  successfully:"
                            );
                            console.log(response);

                            // this.AllSubjects[response.subject.id]["users"].push(response.subject);
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("subject create Error:", error);
                            console.log(error.responseJSON.errors);

                            this.Errors = error.responseJSON.errors;
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("subject create Error:", error);
                throw error;
            }
        },
        async deleteSubjectUsers(data) {
            await csrf();
 

            console.log(data);
            try {

                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "delete",
                        url: `/api/admin/subjectUsers/${data.subject_id}/${data.user_id}`,
                        data: data,
                        success: (response) => {
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("subject create Error:", error);
                            console.log(error.responseJSON.errors);

                            this.Errors = error.responseJSON.errors;
                            reject(error);
                        },
                    });
                });
            } catch (error) {
                console.error("subject create Error:", error);
                throw error;
            }
        },

        clearErrors() {
            this.Errors = {}; // طريقة لإعادة تعيين الأخطاء
        },
    },
});
