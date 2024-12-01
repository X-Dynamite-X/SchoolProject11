// import "@/bootstrap";
import "@/axios";
import { createApp } from "vue";
import { createPinia } from 'pinia';
import router from "@/router/router";


import App from "@/App.vue";
const pinia = createPinia()


createApp(App).use(pinia).use(router).mount("#app");
