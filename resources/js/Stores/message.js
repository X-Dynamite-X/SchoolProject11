import { defineStore } from "pinia";
import $, { error, get } from "jquery";

const csrf = () => $.get("/sanctum/csrf-cookie");

export const useMessageStore = defineStore("message", {
    state: () => ({
        AllConversation: [],
    }),
    getters: {
        conversations: (state) => state.AllConversation,
    },
    actions: {
        async getConversations() {
            if (this.AllConversation.length > 0) {
                return this.AllConversation;
            } else {
                try {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            type: "GET",
                            url: "/api/conversation",
                            dataType: "json",
                            success: (response) => {
                                console.log(response.conversation);

                                this.AllConversation = response.conversations;
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
    },
});
