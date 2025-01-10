import { defineStore } from "pinia";
import $, { error, get } from "jquery";

const csrf = () => $.get("/sanctum/csrf-cookie");

export const useMessageStore = defineStore("message", {
    state: () => ({
        AllConversations: [],
    }),
    getters: {
        conversations: (state) => state.AllConversations,
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
    },
});
