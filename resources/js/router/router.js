import { createRouter, createWebHistory } from "vue-router";
import Home from "@/Pages/Home.vue";
import Login from "@/Pages/Auth/Login.vue";
import Register from "@/Pages/Auth/Register.vue";

const routes = [
    {
        path: "/login",
        component: Login,
        name: "login",
    },
    {
        path: "/",
        component: Home,
        name: "home",
    },
    {
        path: "/register",
        component: Register,
        name: "register",
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
