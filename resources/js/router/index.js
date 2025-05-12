import { createRouter, createWebHistory } from "vue-router";
//Petugas
import Dashboard from "../components/petugas/Dashboard.vue";
import Sampah from "../components/petugas/Sampah.vue";
import Nasabah from "../components/petugas/Nasabah.vue";
import JadwalPenimbangan from "../components/petugas/JadwalPenimbangan.vue";
import JadwalPeriode from "../components/petugas/JadwalPeriode.vue";
import Penimbangan from "../components/petugas/Penimbangan.vue";

//Nasabah
import DashboardNasabah from "../components/nasabah/DashboardNasabah.vue";
import PendaftaranJadwalPenimbangan from "../components/nasabah/PendaftaranJadwalPenimbangan.vue";
import SampahNasabah from "../components/nasabah/SampahNasabah.vue";
import PenimbanganNasabah from "../components/nasabah/PenimbanganNasabah.vue";
import ProfileNasabah from "../components/nasabah/ProfileNasabah.vue";

const routes = [
    {
        path: "/home",
        name: "dashboardPetugas",
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
                path: "jadwalPeriode",
                component: JadwalPeriode,
                meta: {
                    title: "Jadwal Periode",
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
    {
        path: "/homeNasabah",
        name: "dashboardNasabah",
        component: DashboardNasabah,
        meta: {
            title: "Dashboard Nasabah",
        },
        children: [
            {
                path: "pendaftaranJadwalPenimbangan",
                component: PendaftaranJadwalPenimbangan,
                meta: {
                    title: "Pendaftaran Jadwal Penimbangan",
                },
            },
            {
                path: "sampahNasabah",
                component: SampahNasabah,
                meta: {
                    title: "Data Sampah",
                },
            },
            {
                path: "penimbanganNasabah",
                component: PenimbanganNasabah,
                meta: {
                    title: "Data Penimbangan",
                },
            },
            {
                path: "profileNasabah",
                component: ProfileNasabah,
                meta: {
                    title: "Data Profile & Saldo Nasabah",
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
