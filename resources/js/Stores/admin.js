import { defineStore } from "pinia";
import $, { error, get } from "jquery";

// const csrf = () => axios.get("/sanctum/csrf-cookie");
const csrf = () => $.get("/sanctum/csrf-cookie");

export const useAdminStore = defineStore("admin", {
    state: () => ({
        userCache: {}, // تخزين بيانات المستخدمين حسب رابط الصفحة أو رقم
        subjectCache: {},
        AllUsers: [],
        nextPageUrl: null,
        prevPageUrl: null,
        countUser: 0,
    }),
    getters: {
        users: (state) => state.AllUsers,
        // users(state) {
        //     // تحويل الكاش إلى مصفوفة (للاستخدام في الجدول)
        //     return Object.values(state.userCache);
        // },
        // subjects: (state) => (pageUrl) => state.subjectCache[pageUrl] || null,
        subjects(state) {
            // تحويل الكاش إلى مصفوفة (للاستخدام في الجدول)
            return Object.values(state.subjectCache);
        },
        nextPage(state) {
            return state.nextPageUrl;
        },
        prevPage(state) {
            return state.prevPageUrl;
        },
        totalUsers: (state) => state.countUser,
    },
    actions: {
        async getUsers(url = "/api/admin/user", keyword = "", limit = 10) {
            const cacheKey = `${url}?keyword=${keyword}&limit=${limit}`; // مفتاح فريد بناءً على معلمات الطلب
            if (this.userCache[cacheKey]) {
                const cachedData = this.userCache[cacheKey];
                this.AllUsers = cachedData.users;
                this.countUser = cachedData.count;
                this.nextPageUrl = cachedData.nextPageUrl;
                this.prevPageUrl = cachedData.prevPageUrl;

                return Promise.resolve(cachedData);
            }
            console.log(this.userCache[cacheKey]);

            await csrf(); // استدعاء وظيفة CSRF
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "get",
                        url: url,
                        data: { keyword, limit },
                        dataType: "json",
                        success: (data) => {
                            console.log("Fetching data from server...");
                            this.userCache[cacheKey] = {
                                users: data.users,
                                count: data.count,
                                nextPageUrl: data.next_page_url,
                                prevPageUrl: data.prev_page_url,
                            };

                            // تحديث الحالة
                            this.AllUsers = data.users;
                            this.countUser = data.count;
                            this.nextPageUrl = data.next_page_url;
                            this.prevPageUrl = data.prev_page_url;

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
        async deleteUser(data) {
            await csrf();
            try {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/admin/user/${data.id}`,
                        success: async (response) => {
                            console.log("User deleted successfully:", response);
                            console.log(response)
                            // حذف المستخدم من الكاش لجميع الصفحات
                            Object.keys(this.userCache).forEach((key) => {
                                const cachedData = this.userCache[key];
                                cachedData.users = cachedData.users.filter(
                                    (user) => user.id !== response.user.id
                                );
                                if (cachedData.users.length === 0) {
                                    delete this.userCache[key];
                                }
                            });
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
                            console.log("User updated successfully:", response);
                            // تحديث المستخدم في الكاش
                            this.userCache[data.id] = response;
                            console.log(response);
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

        async getSubjects(url = "/api/admin/subject", keyword = "") {
            const cacheKey = `${url}?keyword=${keyword}`; // المفتاح مع الأخذ بعين الاعتبار كلمة البحث
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
