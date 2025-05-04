import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../components/Dashboard.vue";
import Sampah from "../components/Sampah.vue";
import Nasabah from "../components/Nasabah.vue";
import JadwalPenimbangan from "../components/JadwalPenimbangan.vue";
import Penimbangan from "../components/Penimbangan.vue";

const routes = [
    {
        path: "/home",
        name: "dashboard",
        component: Dashboard,
        meta: {
            title: "Dashboard",
        },
        children: [
            {
                path: "sampah",
                component: Sampah,
                meta: {
                    title: "Sampah",
                },
            },
            {
                path: "nasabah",
                component: Nasabah,
                meta: {
                    title: "Nasabah",
                },
            },
            {
                path: "jadwalPenimbangan",
                component: JadwalPenimbangan,
                meta: {
                    title: "Jadwal Penimbangan",
                },
            },
            {
                path: "penimbangan",
                component: Penimbangan,
                meta: {
                    title: "Penimbangan",
                },
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Mengubah title halaman berdasarkan meta title
router.beforeEach((to, from, next) => {
    if (to.meta.title) {
        document.title = to.meta.title;
    } else {
        document.title = "BANK SAMPAH"; // Set default title
    }
    next();
});

export default router;
