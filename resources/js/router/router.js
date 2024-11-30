import { createRouter, createWebHistory } from "vue-router";
import Home from "../Pages/Home.vue";
import Login from "../Pages/Auth/Login.vue";
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
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
