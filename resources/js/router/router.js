import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";

import Home from "@/Pages/Home.vue";
import Login from "@/Pages/Auth/Login.vue";
import Register from "@/Pages/Auth/Register.vue";
import ForgotPsasword from "@/Pages/Auth/ForgotPsasword.vue";
import ResetPassword from "@/Pages/Auth/ResetPassword.vue";
import NotFound from "@/Pages/Errors/NotFound.vue"; // استيراد صفحة 404
import UsersAdmin from "@/Pages/Admin/Users.vue";
import SubjectsAdmin from "@/Pages/Admin/Subjects.vue";

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
        path: "/admin/users",
        component: UsersAdmin,
        name: "adminUsers",
    },
    {
        path: "/admin/Subject",
        component: SubjectsAdmin,
        name: "adminSubjects",
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
            // محاولة جلب بيانات المستخدم
            await authStore.getUser();
        } catch (error) {
            // إذا فشل في جلب بيانات المستخدم (ربما لأنه لم يسجل الدخول)، يتم تحويله إلى صفحة تسجيل الدخول
            return next({ name: "login" });
        }
    }

    const isAuthenticated = authStore.user;
    const userRole = isAuthenticated?.roles?.[0]?.name || null;

    // قوائم الصفحات العامة (التي يمكن الوصول إليها بدون تسجيل الدخول)
    const publicPages = [
        "login",
        "register",
        "ForgotPassword",
        "ResetPassword",
    ];
    const adminOnlyPages = ["adminHome"];
    const userRestrictedPages = [...publicPages, "adminHome"];

    // إذا لم يكن المستخدم مصادقًا (غير مسجل الدخول) ويحاول الوصول إلى صفحات محمية
    if (!isAuthenticated && !publicPages.includes(to.name)) {
        return next({ name: "login" }); // التوجيه إلى صفحة تسجيل الدخول
    }

    // إذا كان المستخدم مديرًا ويحاول الوصول إلى صفحات غير مخصصة له
    if (userRole === "admin" && publicPages.includes(to.name)) {
        return next({ name: "adminHome" }); // التوجيه إلى الصفحة الرئيسية للمدير
    }

    // إذا كان المستخدم ليس مديرًا ويحاول الوصول إلى صفحات المدير
    if (userRole !== "admin" && adminOnlyPages.includes(to.name)) {
        return next({ name: "home" }); // التوجيه إلى الصفحة الرئيسية للمستخدم
    }

    // السماح بالتنقل إذا كانت جميع الشروط مستوفاة
    next();
});

export default router;
