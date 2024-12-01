import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";

import Home from "@/Pages/Home.vue";
import Login from "@/Pages/Auth/Login.vue";
import Register from "@/Pages/Auth/Register.vue";
import ForgotPsasword from "@/Pages/Auth/ForgotPsasword.vue"
import ResetPassword from "@/Pages/Auth/ResetPassword.vue"
import NotFound from "@/Pages/Errors/NotFound.vue";  // استيراد صفحة 404

const routes = [
  // المسارات المسجلة
  {
    path: "/",
    component: Home,
    name: "home"
  },
  {
    path: "/login",
    component: Login,
    name: "login"
  },
  {
    path: "/register",
    component: Register,
    name: "register"
  },
  {
    path: "/forgot-psasword",
    component: ForgotPsasword,
    name: "ForgotPsasword"
  },
  {
    path: "/password-reset/:token",
    component: ResetPassword,
    name: "ResetPassword"
  },

  // المسار لمطابقة أي مسار غير مسجل - صفحة 404
  {
    path: "/:catchAll(.*)",  // يلتقط أي مسار غير مسجل
    component: NotFound,
    name: "NotFound",
  }
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

  // التحقق من الصلاحيات
  if (
    isAuthenticated &&
    isAuthenticated.roles[0].name !== 'admin' &&
    (to.name === 'adminHome' || to.name === 'login' || to.name === 'register' || to.name === 'ForgotPsasword' || to.name === 'ResetPassword')
  ) {
    next({ name: 'home' });
  } else if (
    isAuthenticated &&
    isAuthenticated.roles[0].name === 'admin' &&
    (to.name === 'login' || to.name === 'register' || to.name === 'ForgotPsasword' || to.name === 'ResetPassword')
  ) {
    next({ name: 'adminHome' });
  } else if (!isAuthenticated && to.name !== 'login' && to.name !== 'register' && to.name !== 'ForgotPsasword' && to.name !== 'ResetPassword') {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router;
