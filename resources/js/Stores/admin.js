import { defineStore } from "pinia";
import $, { error, get } from "jquery";
import axios from "axios";

const csrf = () => axios.get("/sanctum/csrf-cookie");

export const useAdminStore = defineStore("admin", {
    state: () => ({
        userCache: {}, // تخزين بيانات المستخدمين حسب رابط الصفحة أو رقم
        subjectCache: {},
    }),
    getters: {
        users: (state) => (pageUrl) => state.userCache[pageUrl] || null,
        subjects: (state) => (pageUrl) => state.subjectCache[pageUrl] || null,
    },
    actions: {
        getCSRFToken() {
            return $.ajax({
                url: "/sanctum/csrf-cookie",
                method: "GET",
                success: () => {
                    console.log("CSRF token retrieved successfully.");
                },
                error: (xhr, status, error) => {
                    console.error("Failed to retrieve CSRF token:", error);
                },
            });
        },

        getUsers(url = "/api/admin/user", keyword = "") {
            const cacheKey = `${url}?keyword=${keyword}`; // إنشاء المفتاح مع الأخذ بعين الاعتبار كلمة البحث
            // التحقق من البيانات المخزنة في الذاكرة المؤقتة
            if (this.userCache[cacheKey]) {
                console.log("Fetching from cache:", cacheKey);
                return Promise.resolve(this.userCache[cacheKey]); // إرجاع البيانات من الذاكرة المؤقتة
            }
            // إذا لم تكن البيانات موجودة في الذاكرة المؤقتة، قم بجلبها باستخدام AJAX
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: cacheKey,
                    method: "GET",
                    dataType: "json",
                    beforeSend: (xhr) => {
                        xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));
                    },
                    success: (data) => {
                        console.log("Fetching from API:", cacheKey);
                        this.userCache[cacheKey] = data.users; // تخزين البيانات في الذاكرة المؤقتة
                        resolve(data.users); // إرجاع البيانات
                    },
                    error: (xhr, status, error) => {
                        console.error("User Fetch Error:", error);
                        reject(error); // إرجاع الخطأ إذا حدث
                    },
                });
            });
        },
        deleteUser(data) {
            console.log(data.id);

            $.ajax({
                url: `/api/admin/user/${data.id}`,
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                dataType: "json",
                success: (response) => {
                    console.log("User deleted successfully", response);

                    
                },
                error: (xhr, status, error) => {
                    console.error("User Fetch Error:", error);
                },
            });
        },

        getSubjects(url = "/api/admin/subject", keyword = "") {
            const cacheKey = `${url}?keyword=${keyword}`;
            if (this.subjectCache[cacheKey]) {
                console.log(cacheKey);
                return Promise.resolve(this.subjectCache[cacheKey]);
            }
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: cacheKey,
                    method: "GET",
                    dataType: "json",
                    success: (data) => {
                        console.log("Fetching from API:", cacheKey);
                        this.subjectCache[cacheKey] = data.subjects;
                        resolve(data.subjects);
                    },
                    error: (xhr, status, error) => {
                        console.error("User Fetch Error:", error);
                        reject(error); // إرجاع الخطأ إذا حدث
                    },
                });
            });
        },
    },
});
