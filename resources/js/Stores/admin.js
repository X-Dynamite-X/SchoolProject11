import { defineStore } from "pinia";
import axios from "axios";

const csrf = () => axios.get('/sanctum/csrf-cookie');

export const useAdminStore = defineStore('admin', {
    state: () => ({
      userCache: {}, // تخزين بيانات المستخدمين حسب رابط الصفحة أو رقم الصفحة
    }),
    getters: {
      users: (state) => (pageUrl) => state.userCache[pageUrl] || null,
    },
    actions: {
      async getUsers(url = '/api/admin/user', keyword = '') {
        const cacheKey = `${url}?keyword=${keyword}`; // المفتاح مع الأخذ بعين الاعتبار كلمة البحث
        if (this.userCache[cacheKey]) {
          console.log("Fetching from cache:", cacheKey);
          return this.userCache[cacheKey];
        }

        await csrf();
        try {
          const response = await axios.get(url, { params: { keyword } }); // تمرير الكلمة كمعلومة
          console.log("Fetching from API:", cacheKey);
          this.userCache[cacheKey] = response.data.users;
          return response.data.users;
        } catch (error) {
          console.error("User Fetch Error:", error);
          throw error;
        }
      },
    },
  });
