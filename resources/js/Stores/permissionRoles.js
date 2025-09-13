import { defineStore } from "pinia";
import $, { error, get } from "jquery";



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
                            url: "/SchoolProject11/api/admin/permission ",
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
                        url: "/SchoolProject11/api/admin/permission ",
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
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "DELETE",
                        url: `/SchoolProject11/api/admin/permission/${data.id}`,
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
                        url: `/SchoolProject11/api/admin/permission/${data.id}`,
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
                            url: "/SchoolProject11/api/admin/role ",
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
                        url: "/SchoolProject11/api/admin/role ",
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
        async deleteRole(data){
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "DELETE",
                        url: `/SchoolProject11/api/admin/role/${data.id}`,
                        dataType: "json",
                        success: (response) => {
                            this.AllRole = this.AllRole.filter(
                                (role) => role.id !== data.id
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
        async updateRole(data){
            const roleData =data.role;
            const permissionData =data.permissions;

            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "Put",
                        url: `/SchoolProject11/api/admin/role/${roleData.id}`,
                        data: {
                            name: roleData.name,
                            permissions:permissionData
                        },
                        dataType: "json",
                        success: (response) => {
                            this.Errors = [];
                            console.log("333333333333333333333333333333333");

                            console.log(response);
                            console.log("333333333333333333333333333333333");

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
        clearErrors() {
            this.Errors = [];
        },
    },
});
