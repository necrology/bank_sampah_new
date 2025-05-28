import { createApp } from "vue";
import App from "./components/App.vue";
import router from "./router";
import VueToast from "vue-toast-notification";

// Import CSS dan JS
import "http://202.10.47.115:8000/sbadmin2/css/sb-admin-2.min.css";
import "http://202.10.47.115:8000/sbadmin2/css/sb-admin-2.min.csssbadmin2/vendor/fontawesome-free/css/all.min.css";
import "http://202.10.47.115:8000/sbadmin2/css/sb-admin-2.min.csssbadmin2/vendor/jquery/jquery.min.js";
import "http://202.10.47.115:8000/sbadmin2/css/sb-admin-2.min.csssbadmin2/vendor/jquery/jquery.min.js";
import "http://202.10.47.115:8000/sbadmin2/css/sb-admin-2.min.csssbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js";
import "vue-toast-notification/dist/theme-sugar.css";

const appElement = document.getElementById("app");
if (appElement) {
    const userData = JSON.parse(appElement.dataset.user || "{}");
    const app = createApp(App, { user: userData });

    // Konfigurasi Vue Toast
    app.use(VueToast, {
        position: "top-right",
        duration: 5000,
        dismissible: true,
    });

    app.use(router);
    app.mount("#app");
}
