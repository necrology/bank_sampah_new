<template>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul
            class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
            id="accordionSidebar"
        >
            <!-- Sidebar - Brand -->
            <a
                class="sidebar-brand d-flex align-items-center justify-content-center"
            >
                <div class="sidebar-brand-icon">
                    <img
                        src="//landing/assets/img/logo.png"
                        style="width: 50px; height: 50px"
                    />
                </div>
                <div class="sidebar-brand-text mx-3">Bank Sampah</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item" :class="{ active: isActive('/homeNasabah') }">
                <router-link to="/homeNasabah" class="nav-link">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Nasbah</span></router-link
                >
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li
                class="nav-item"
                :class="{
                    active: isActive(
                        '/homeNasabah/pendaftaranJadwalPenimbangan'
                    ),
                }"
            >
                <router-link
                    :to="{
                        path: '/homeNasabah/pendaftaranJadwalPenimbangan',
                        query: {
                            id_nasabah: user.id_nasabah,
                            id: user.id,
                            name: user.name,
                        },
                    }"
                    class="nav-link"
                >
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Pendaftaran Jadwal</span>
                </router-link>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li
                class="nav-item"
                :class="{ active: isActive('/homeNasabah/sampahNasabah') }"
            >
                <router-link to="/homeNasabah/sampahNasabah" class="nav-link">
                    <i class="fas fa-fw fa-trash"></i>
                    <span>Data Sampah</span></router-link
                >
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li
                class="nav-item"
                :class="{
                    active: isActive('/homeNasabah/penimbanganNasabah'),
                }"
            >
                <router-link
                    :to="{
                        path: '/homeNasabah/penimbanganNasabah',
                        query: {
                            id_nasabah: user.id_nasabah,
                            id: user.id,
                            name: user.name,
                        },
                    }"
                    class="nav-link"
                >
                    <i class="fas fa-fw fa-balance-scale"></i>
                    <span>Data Penimbangan</span>
                </router-link>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li
                class="nav-item"
                :class="{
                    active: isActive('/homeNasabah/profileNasabah'),
                }"
            >
                <router-link
                    :to="{
                        path: '/homeNasabah/profileNasabah',
                        query: {
                            id_nasabah: user.id_nasabah,
                            id: user.id,
                            name: user.name,
                        },
                    }"
                    class="nav-link"
                >
                    <i class="fas fa-fw fa-user"></i>
                    <span>Data Profile & Saldo</span>
                </router-link>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button
                    class="rounded-circle border-0"
                    id="sidebarToggle"
                ></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav
                    class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
                >
                    <!-- Sidebar Toggle (Topbar) -->
                    <button
                        id="sidebarToggleTop"
                        class="btn btn-link d-md-none rounded-circle mr-3"
                    >
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                id="userDropdown"
                                role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small"
                                    >{{ user.name || "Guest" }}</span
                                >
                                <img
                                    class="img-profile rounded-circle"
                                    src="//sbadmin2/img/undraw_profile.svg"
                                />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div
                                class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown"
                            >
                                <router-link
                                    :to="{
                                        path: '/homeNasabah/profileNasabah',
                                        query: {
                                            id_nasabah: user.id_nasabah,
                                            id: user.id,
                                            name: user.name,
                                        },
                                    }"
                                    class="dropdown-item"
                                >
                                    <i
                                        class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"
                                    ></i>
                                    Profile
                                </router-link>
                                <div class="dropdown-divider"></div>
                                <a
                                    class="dropdown-item"
                                    href="#"
                                    data-toggle="modal"
                                    data-target="#logoutModal"
                                >
                                    <i
                                        class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"
                                    ></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div v-if="isActive('/homeNasabah')">
                        <!-- Page Heading -->
                        <div
                            class="d-sm-flex align-items-center justify-content-between mb-4"
                        >
                            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        </div>

                        <!-- Content Row -->
                        <div class="row">
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div
                                    class="card border-left-primary shadow h-100 py-2"
                                >
                                    <div class="card-body">
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                                                >
                                                    Nasabah
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    {{
                                                        dashboardData.nasabah ||
                                                        0
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="fas fa-users fa-2x text-gray-300"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div
                                    class="card border-left-success shadow h-100 py-2"
                                >
                                    <div class="card-body">
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-success text-uppercase mb-1"
                                                >
                                                    Sampah
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    {{
                                                        formatNumber(
                                                            dashboardData.totalSampah
                                                        ) || 0
                                                    }}
                                                    Kg
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="fas fa-trash fa-2x text-gray-300"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div
                                    class="card border-left-info shadow h-100 py-2"
                                >
                                    <div class="card-body">
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-info text-uppercase mb-1"
                                                >
                                                    Saldo
                                                </div>
                                                <div
                                                    class="row no-gutters align-items-center"
                                                >
                                                    <div class="col-auto">
                                                        <div
                                                            class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                                                        >
                                                            Rp
                                                            {{
                                                                formatNumber(
                                                                    dashboardData.totalSaldo
                                                                ) || 0
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="fas fa-dollar-sign fa-2x text-gray-300"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div
                                    class="card border-left-warning shadow h-100 py-2"
                                >
                                    <div class="card-body">
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                                                >
                                                    Kategori
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    {{
                                                        dashboardData.kategori ||
                                                        0
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="fas fa-layer-group fa-2x text-gray-300"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Area Chart -->
                            <div class="col-xl-8 col-lg-7">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                                    >
                                        <h6
                                            class="m-0 font-weight-bold text-primary"
                                        >
                                            Grafik Penimbangan
                                        </h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas
                                                id="chartPenimbangan"
                                                ref="chartPenimbangan"
                                            ></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pie Chart -->
                            <div class="col-xl-4 col-lg-5">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                                    >
                                        <h6
                                            class="m-0 font-weight-bold text-primary"
                                        >
                                            Kategori Sampah yang ditimbang
                                        </h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-pie pt-4 pb-2">
                                            <canvas
                                                id="chartKategori"
                                                ref="chartKategori"
                                            ></canvas>
                                        </div>
                                        <div
                                            v-if="loadingKategori"
                                            class="text-center py-4"
                                        >
                                            <div
                                                class="spinner-border text-primary"
                                                role="status"
                                            >
                                                <span class="sr-only"
                                                    >Loading...</span
                                                >
                                            </div>
                                        </div>
                                        <div
                                            v-if="errorKategori"
                                            class="alert alert-danger"
                                        >
                                            {{ errorKategori }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">
                            <!-- Content Column -->
                            <div class="col-md">
                                <!-- Project Card Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6
                                            class="m-0 font-weight-bold text-primary"
                                        >
                                            Nasabah Terbanyak Menimbang
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div
                                            v-if="loadingLeaderboard"
                                            class="text-center py-4"
                                        >
                                            <div
                                                class="spinner-border text-primary"
                                                role="status"
                                            >
                                                <span class="sr-only"
                                                    >Loading...</span
                                                >
                                            </div>
                                        </div>
                                        <div
                                            v-if="errorLeaderboard"
                                            class="alert alert-danger"
                                        >
                                            {{ errorLeaderboard }}
                                        </div>
                                        <div
                                            v-for="(
                                                nasabah, index
                                            ) in leaderboardData"
                                            :key="nasabah.id_nasabah"
                                        >
                                            <h4 class="small font-weight-bold">
                                                {{ nasabah.nama }}
                                                <span class="float-right"
                                                    >{{
                                                        nasabah.total_berat
                                                    }}
                                                    Kg</span
                                                >
                                            </h4>
                                            <div class="progress mb-4">
                                                <div
                                                    class="progress-bar"
                                                    :class="
                                                        getProgressBarClass(
                                                            index
                                                        )
                                                    "
                                                    role="progressbar"
                                                    :style="
                                                        'width: ' +
                                                        calculatePercentage(
                                                            nasabah.total_berat
                                                        ) +
                                                        '%'
                                                    "
                                                    :aria-valuenow="
                                                        calculatePercentage(
                                                            nasabah.total_berat
                                                        )
                                                    "
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <router-view v-else />
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Bank Sampah Bersinar 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div
        class="modal fade"
        id="logoutModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Apakah Anda yakin ingin logout?
                    </h5>
                    <button
                        class="close"
                        type="button"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Tekan "Logout" dibawah ini jika benar ingin logout.
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-secondary"
                        type="button"
                        data-dismiss="modal"
                    >
                        Cancel
                    </button>
                    <a class="btn btn-primary" href="#" @click.prevent="logout"
                        >Logout</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useRoute } from "vue-router";
import axios from "axios";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default {
    props: {
        user: Object,
    },
    data() {
        return {
            dashboardData: {
                nasabah: 0,
                totalSampah: 0,
                totalSaldo: 0,
                kategori: 0,
            },
            chartData: [],
            kategoriData: [],
            leaderboardData: [],
            loading: true,
            loadingKategori: false,
            loadingLeaderboard: false,
            error: null,
            errorKategori: null,
            errorLeaderboard: null,
            maxBerat: 0,
            penimbanganChart: null,
            kategoriChart: null,
        };
    },
    async mounted() {
        // Initialize sidebar toggle
        $("#sidebarToggle, #sidebarToggleTop").on("click", function (e) {
            $("body").toggleClass("sidebar-toggled");
            $(".sidebar").toggleClass("toggled");
            if ($(".sidebar").hasClass("toggled")) {
                $(".sidebar .collapse").collapse("hide");
            }
        });

        // Initial dashboard load
        await this.initializeDashboard();
    },
    setup() {
        const route = useRoute();

        const isActive = (path) => {
            return route.path === path;
        };

        return { isActive };
    },
    watch: {
        "$route.path": {
            immediate: true,
            async handler(newPath, oldPath) {
                if (newPath === "/homeNasabah") {
                    await this.initializeDashboard();
                } else if (
                    oldPath === "/homeNasabah" &&
                    newPath !== "/homeNasabah"
                ) {
                    this.destroyCharts();
                }
            },
        },
    },
    methods: {
        async initializeDashboard() {
            this.destroyCharts();

            try {
                // Load all data in parallel
                await Promise.all([
                    this.fetchDashboardData(),
                    this.fetchChartData(),
                    this.fetchKategoriData(),
                    this.fetchLeaderboardData(),
                ]);

                // Initialize charts after data is loaded
                this.$nextTick(() => {
                    this.initCharts();
                });
            } catch (error) {
                console.error("Error initializing dashboard:", error);
            }
        },

        destroyCharts() {
            if (this.penimbanganChart) {
                this.penimbanganChart.destroy();
                this.penimbanganChart = null;
            }
            if (this.kategoriChart) {
                this.kategoriChart.destroy();
                this.kategoriChart = null;
            }
        },

        async fetchDashboardData() {
            try {
                this.loading = true;
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/dashboard1"
                );
                this.dashboardData = response.data;
            } catch (error) {
                console.error("Error fetching dashboard data:", error);
                this.error = "Gagal memuat data dashboard";
            } finally {
                this.loading = false;
            }
        },

        async fetchChartData() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/chart1"
                );
                this.chartData = response.data;
            } catch (error) {
                console.error("Error fetching chart data:", error);
                this.error = "Gagal memuat data chart";
            }
        },

        async fetchKategoriData() {
            try {
                this.loadingKategori = true;
                this.errorKategori = null;
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/chart2"
                );
                this.kategoriData = response.data;
            } catch (error) {
                console.error("Error fetching kategori data:", error);
                this.errorKategori = "Gagal memuat data kategori sampah";
            } finally {
                this.loadingKategori = false;
            }
        },

        async fetchLeaderboardData() {
            try {
                this.loadingLeaderboard = true;
                this.errorLeaderboard = null;
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/leaderboard"
                );
                this.leaderboardData = response.data;

                if (this.leaderboardData.length > 0) {
                    this.maxBerat = Math.max(
                        ...this.leaderboardData.map((item) => item.total_berat)
                    );
                }
            } catch (error) {
                console.error("Error fetching leaderboard data:", error);
                this.errorLeaderboard = "Gagal memuat data leaderboard nasabah";
            } finally {
                this.loadingLeaderboard = false;
            }
        },

        initCharts() {
            // Check if canvas elements exist
            if (!this.$refs.chartPenimbangan || !this.$refs.chartKategori) {
                console.warn("Chart canvas elements not found");
                return;
            }

            // Initialize Penimbangan Chart (Line Chart)
            const penimbanganCtx = this.$refs.chartPenimbangan.getContext("2d");
            this.penimbanganChart = new Chart(penimbanganCtx, {
                type: "line",
                data: {
                    labels: this.chartData.map((item) => item.bulan),
                    datasets: [
                        {
                            label: "Total Penimbangan (Kg)",
                            data: this.chartData.map(
                                (item) => item.total_berat
                            ),
                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            fill: "origin",
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                maxTicksLimit: 7,
                            },
                        },
                        y: {
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                callback: function (value) {
                                    return value + " Kg";
                                },
                            },
                            grid: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2],
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleMarginBottom: 10,
                            titleColor: "#6e707e",
                            titleFontSize: 14,
                            borderColor: "#dddfeb",
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: "index",
                            caretPadding: 10,
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || "";
                                    if (label) {
                                        label += ": ";
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y + " Kg";
                                    }
                                    return label;
                                },
                            },
                        },
                    },
                },
            });

            // Initialize Kategori Chart (Doughnut Chart)
            const kategoriCtx = this.$refs.chartKategori.getContext("2d");
            this.kategoriChart = new Chart(kategoriCtx, {
                type: "doughnut",
                data: {
                    labels: this.kategoriData.map((item) => item.kategori),
                    datasets: [
                        {
                            data: this.kategoriData.map(
                                (item) => item.total_berat
                            ),
                            backgroundColor: [
                                "#4e73df",
                                "#1cc88a",
                                "#36b9cc",
                                "#f6c23e",
                                "#e74a3b",
                                "#858796",
                                "#5a5c69",
                                "#2e59d9",
                                "#17a673",
                                "#2c9faf",
                            ],
                            hoverBackgroundColor: [
                                "#2e59d9",
                                "#17a673",
                                "#2c9faf",
                                "#dda20a",
                                "#be2617",
                                "#6c757d",
                                "#4a4c55",
                                "#1c3fd1",
                                "#0e8c5a",
                                "#1c8a9a",
                            ],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            borderColor: "#dddfeb",
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function (context) {
                                    var label = context.label || "";
                                    var value = context.raw || 0;
                                    var total = context.dataset.data.reduce(
                                        (a, b) => a + b,
                                        0
                                    );
                                    var percentage = Math.round(
                                        (value / total) * 100
                                    );
                                    return (
                                        label +
                                        ": " +
                                        value +
                                        " Kg (" +
                                        percentage +
                                        "%)"
                                    );
                                },
                            },
                        },
                        legend: {
                            position: "bottom",
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                            },
                        },
                    },
                    cutout: "70%",
                },
            });
        },

        calculatePercentage(berat) {
            if (this.maxBerat === 0) return 0;
            return Math.round((berat / this.maxBerat) * 100);
        },

        getProgressBarClass(index) {
            const classes = [
                "bg-danger",
                "bg-warning",
                "",
                "bg-info",
                "bg-success",
            ];
            return classes[index % classes.length];
        },

        formatNumber(num) {
            return num
                ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                : "0";
        },

        async logout() {
            try {
                await axios.post("/logout");
                window.location.href = "/login";
            } catch (error) {
                console.error("Logout failed", error);
            }
        },
    },

    beforeUnmount() {
        this.destroyCharts();
    },
};
</script>

<style scoped>
.chart-area {
    position: relative;
    height: 20rem;
    width: 100%;
}

.chart-pie {
    position: relative;
    height: 15rem;
    width: 100%;
}

@media (min-width: 768px) {
    .chart-pie {
        height: 20rem;
    }
}
</style>
