import { defineStore } from "pinia";
import $, { error, get } from "jquery";

const csrf = () => $.get("/sanctum/csrf-cookie");

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
                                console.log(response.data);

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
            await csrf();
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
            await csrf();
            console.log(data);

            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "/api/message",
                        data: {
                            conversation_id: data["conversationId"],
                            text: data["text"],
                            created_at:data['created_at']
                        },
                        success: (response) => {
                            console.log(response);

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
    },
});
