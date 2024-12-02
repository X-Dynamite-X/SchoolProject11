import { defineStore } from "pinia";
import axios from "axios";

const csrf = () => axios.get('/sanctum/csrf-cookie');

export const useAdminStore = defineStore('admin', {
  state: () => ({
    userCache: {}, // تخزين بيانات المستخدمين حسب رابط الصفحة أو رقم الصفحة
  }),
  getters: {
    // استرجاع بيانات المستخدمين الحالية بناءً على الرابط أو الصفحة
    users: (state) => (pageUrl) => state.userCache[pageUrl] || null,
  },
  actions: {
    async getUsers(url = '/api/admin/user') {
      // التحقق إذا كانت البيانات موجودة بالفعل في التخزين المؤقت
      if (this.userCache[url]) {
        console.log("Fetching from cache:", url);
        return this.userCache[url];
      }

      // إذا لم تكن موجودة، يتم جلب البيانات من API
      await csrf();
      try {
        const response = await axios.get(url); // جلب البيانات من الـ API
        console.log("Fetching from API:", url);
        this.userCache[url] = response.data.users; // تخزين البيانات في التخزين المؤقت
        return response.data.users;
      } catch (error) {
        console.error("User Fetch Error:", error);
        throw error;
      }
    },
  },
});
