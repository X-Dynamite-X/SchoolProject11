// import "@/bootstrap";
import "@/axios";
import { createApp, markRaw } from "vue";
import { createPinia } from "pinia";
import router from "@/router/router";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import App from "@/App.vue";

const app = createApp(App);
const pinia = createPinia(piniaPluginPersistedstate);
pinia.use(({ store }) => {
    store.router = markRaw(router);
});
app.use(pinia);
app.use(router);

app.mount("#app");
