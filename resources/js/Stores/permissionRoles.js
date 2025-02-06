import { defineStore } from "pinia";
import $, { error, get } from "jquery";
let csrfV = null;

const csrf = async () => {
    if (csrfV === null) {
        try {
            // جلب الـ CSRF token إذا لم يكن موجودًا
            csrfV = await $.get("/sanctum/csrf-cookie");
            return csrfV;
        } catch (error) {
            console.error("Failed to fetch CSRF token:", error);
            throw error; // إعادة رمي الخطأ للتعامل معه في مكان آخر
        }
    } else {
        // إعادة الـ CSRF token المحفوظ إذا كان موجودًا
        return csrfV;
    }
};
export const usePermssionRoleStore = defineStore("permissionRole", {
    state: () => ({
        AllPermission: [],
        AllRole: [],
        Errors: [],
    }),
    getters: {
        permissions: (state) => state.AllPermission,
        roles: (state) => state.AllRole,
        errors: (state) => state.Errors,
    },
    actions: {
        async getPermission() {
            if (this.AllPermission.length > 0) {
                return this.AllPermission;
            } else {
                try {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            type: "GET",
                            url: "/api/admin/permission ",
                            dataType: "json",
                            success: (response) => {
                                this.AllPermission = response.permissions;
                                resolve(response);
                            },
                            error: (error) => {
                                const errors =
                                    error.responseJSON?.message ||
                                    "An error occurred";
                                reject(errors);
                            },
                        });
                    });
                } catch (error) {
                    console.error("User Fetch Error:", error);
                    throw error;
                }
            }
        },
        async createPermission(data) {
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "/api/admin/permission ",
                        data: {
                            name: data,
                        },
                        dataType: "json",
                        success: (response) => {
                            this.Errors = [];
                            resolve(response);
                        },
                        error: (error) => {
                            const errors =
                                error.responseJSON?.message ||
                                "An error occurred";
                            this.Errors = error.responseJSON.errors;
                            reject(errors);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error.responseJSON?.errors);
                throw error;
            }
        },
        async deletePermission(data) {
            await csrf();

            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/admin/permission/${data.id}`,
                        dataType: "json",
                        success: (response) => {
                            this.AllPermission = this.AllPermission.filter(
                                (permission) => permission.id !== data.id
                            );
                            resolve(response);
                        },
                        error: (error) => {
                            const errorMessage =
                                error.responseJSON?.message ||
                                "An error occurred";
                            console.error("Error:", errorMessage);
                            reject(errorMessage);
                        },
                    });
                });
            } catch (error) {
                console.error("Delete Permission Error:", error.message);
                throw error;
            }
        },
        async updatePermission(data) {
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "Put",
                        url: `/api/admin/permission/${data.id}`,
                        data: {
                            name: data.name,
                        },
                        dataType: "json",
                        success: (response) => {
                            this.Errors = [];
                            resolve(response);
                        },
                        error: (error) => {
                            const errors =
                                error.responseJSON?.message ||
                                "An error occurred";
                            this.Errors = error.responseJSON.errors;
                            reject(errors);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error.responseJSON?.errors);
                throw error;
            }
        },
        async getRole(){
            if (this.AllPermission.length > 0) {
                return this.AllPermission;
            } else {
                try {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            type: "GET",
                            url: "/api/admin/role ",
                            dataType: "json",
                            success: (response) => {
                                this.AllRole = response.roles;

                                resolve(response);
                            },
                            error: (error) => {
                                const errors =
                                    error.responseJSON?.message ||
                                    "An error occurred";
                                reject(errors);
                            },
                        });
                    });
                } catch (error) {
                    console.error("User Fetch Error:", error);
                    throw error;
                }
            }
        },
        async createRole(data){
            console.log(data);
            
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "/api/admin/role ",
                        data: {
                            name: data.name,
                            permissions: data.permissions,
                        },
                        dataType: "json",
                        success: (response) => {
                            this.Errors = [];
                            resolve(response);
                        },
                        error: (error) => {
                            const errors =
                                error.responseJSON?.message ||
                                "An error occurred";
                            this.Errors = error.responseJSON.errors;
                            reject(errors);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error.responseJSON?.errors);
                throw error;
            }
        },
        async deleteRole(data){},
        async updateRole(data){},
        clearErrors() {
            this.Errors = [];
        },
    },
});
