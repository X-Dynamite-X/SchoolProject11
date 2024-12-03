import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";

import Home from "@/Pages/Home.vue";
import Login from "@/Pages/Auth/Login.vue";
import Register from "@/Pages/Auth/Register.vue";
import ForgotPsasword from "@/Pages/Auth/ForgotPsasword.vue";
import ResetPassword from "@/Pages/Auth/ResetPassword.vue";
import NotFound from "@/Pages/Errors/NotFound.vue"; // استيراد صفحة 404
import Dashbord from "@/Pages/Admin/Dashbord.vue";
const routes = [
    // المسارات المسجلة
    {
        path: "/",
        component: Home,
        name: "home",
    },
    {
        path: "/login",
        component: Login,
        name: "login",
    },
    {
        path: "/register",
        component: Register,
        name: "register",
    },
    {
        path: "/forgot-psasword",
        component: ForgotPsasword,
        name: "ForgotPsasword",
    },
    {
        path: "/password-reset/:token",
        component: ResetPassword,
        name: "ResetPassword",
    },
    {
        path: "/admin",
        component: Dashbord,
        name: "adminHome",
    },
    {
        path: "/:catchAll(.*)",
        component: NotFound,
        name: "NotFound",
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // التحقق من وجود بيانات المستخدم
    if (!authStore.user) {
      try {
        await authStore.getUser();
      } catch (error) {
        return next({ name: 'login' });
      }
    }

    const isAuthenticated = authStore.user;


    const userRole = isAuthenticated?.roles?.[0]?.name || null;

    // قوائم الصفحات بناءً على الصلاحيات
    const publicPages = ["login", "register", "ForgotPassword", "ResetPassword"];
    const adminOnlyPages = ["adminHome"];
    const userRestrictedPages = [...publicPages, "adminHome"];

    // إذا لم يكن المستخدم مصادقًا
    if (!isAuthenticated && !publicPages.includes(to.name)) {
      return next({ name: "login" });
    }

    // إذا كان المستخدم مديرًا ويحاول الوصول إلى صفحات المصادقة
    if (userRole === "admin" && publicPages.includes(to.name)) {
      return next({ name: "adminHome" });
    }

    // إذا كان المستخدم ليس مديرًا ويحاول الوصول إلى صفحات المدير
    if (userRole !== "admin" && adminOnlyPages.includes(to.name)) {
      return next({ name: "home" });
    }

    // السماح بالتنقل
    next();
  });


export default router;
