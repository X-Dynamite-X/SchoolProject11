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
export const useMessageStore = defineStore("message", {
    state: () => ({
        AllConversations: [],
        Errors: [],
    }),
    getters: {
        conversations: (state) => state.AllConversations,
        quryConversations: (state) => state.QuryConversations,
        errors: (state) => state.Errors,
    },
    actions: {
        async getConversations() {
            if (this.AllConversations.length > 0) {
                return this.AllConversations;
            } else {
                try {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            type: "GET",
                            url: "/api/conversation",
                            dataType: "json",
                            success: (response) => {
                                this.AllConversations = response.data;
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
        async searchConversations(serch) {
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "GET",
                        url: "/api/conversation/search",
                        dataType: "json",
                        data: {
                            serch: serch,
                        },
                        success: (response) => {
                            resolve(response);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error);
                throw error;
            }
        },
        async createConversation(userId) {
            // await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "/api/conversation",
                        data: {
                            user_two_id: userId,
                        },
                        success: (response) => {
                            // this.AllConversations.push(response.conversation);
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("Error:", error);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error);
                throw error;
            }
        },
        async createMessage(data) {
            // await csrf();

            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "/api/message",
                        data: {
                            conversation_id: data["conversationId"],
                            text: data["text"],
                            created_at: data["created_at"],
                        },
                        success: (response) => {
                            resolve(response);
                        },
                        error: (error) => {
                            console.error("Error:", error.responseJSON.message);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error);
                throw error;
            }
        },
        async editCheckValueInMessage(conversationId) {
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "put",
                        url: `/api/conversation/${conversationId}/isRead`,
                        dataType: "json",
                        success: (response) => {
                            resolve(response);
                        },
                    });
                });
            } catch (error) {
                console.error("User Fetch Error:", error);
                throw error;
            }
        },
    },
});
