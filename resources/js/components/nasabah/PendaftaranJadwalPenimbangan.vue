<template>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pendaftaran Jadwal Penimbangan</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mt-2 font-weight-bold text-primary">
                        Tabel Data Pendaftaran Jadwal Penimbangan
                    </h6>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <button
                        @click="showModal"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                        :disabled="isLoading"
                    >
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
                        Data
                    </button>
                    <button
                        @click="refreshData"
                        class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2"
                        :disabled="isLoading"
                    >
                        <i class="fas fa-sync fa-sm text-white-50"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Loading Indicator -->
            <div v-if="isLoading" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>

            <!-- Content -->
            <template v-else>
                <!-- Search Field -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Cari berdasarkan lokasi atau tanggal..."
                                v-model="searchQuery"
                                @input="filterData"
                                :disabled="jadwal.length === 0"
                            />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="col-md-6 d-flex align-items-center justify-content-end"
                    >
                        <div class="form-group mb-0">
                            <label for="perPage" class="mr-2 mb-0"
                                >Items per page:</label
                            >
                            <select
                                id="perPage"
                                class="form-control form-control-sm"
                                style="width: 70px"
                                v-model="perPage"
                                @change="currentPage = 1"
                                :disabled="jadwal.length === 0"
                            >
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table
                        class="table table-bordered"
                        width="100%"
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal & Waktu</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="filteredJadwal.length > 0">
                                <tr
                                    v-for="(jadwal, index) in paginatedData"
                                    :key="jadwal.id_jadwal"
                                >
                                    <td>
                                        {{
                                            (currentPage - 1) * perPage +
                                            index +
                                            1
                                        }}
                                    </td>
                                    <td>
                                        {{ formatDateTime(jadwal.tanggal) }}
                                    </td>
                                    <td>{{ jadwal.lokasi }}</td>
                                    <td>
                                        <button
                                            @click="editJadwal(jadwal)"
                                            class="btn btn-warning btn-sm"
                                            title="Edit Jadwal"
                                        >
                                            <i class="fas fa-edit fa-sm"></i>
                                        </button>
                                        &nbsp;
                                        <button
                                            @click="
                                                deleteJadwal(jadwal.id_jadwal)
                                            "
                                            class="btn btn-danger btn-sm"
                                            title="Hapus Jadwal"
                                        >
                                            <i class="fas fa-trash fa-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <template v-if="searchQuery">
                                            <i
                                                class="fas fa-search fa-2x mb-2 text-muted"
                                            ></i>
                                            <p class="mb-0">
                                                Tidak ditemukan data dengan kata
                                                kunci "{{ searchQuery }}"
                                            </p>
                                            <button
                                                class="btn btn-link btn-sm mt-2"
                                                @click="resetSearch"
                                            >
                                                Reset pencarian
                                            </button>
                                        </template>
                                        <template v-else>
                                            <i
                                                class="fas fa-database fa-2x mb-2 text-muted"
                                            ></i>
                                            <p class="mb-0">
                                                Tidak ada data jadwal yang
                                                tersedia
                                            </p>
                                            <button
                                                class="btn btn-link btn-sm mt-2"
                                                @click="fetchJadwal"
                                            >
                                                Coba Muat Ulang
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-3" v-if="filteredJadwal.length > 0">
                    <div class="col-md-6">
                        <div class="dataTables_info">
                            Menampilkan
                            {{ (currentPage - 1) * perPage + 1 }} sampai
                            {{
                                Math.min(
                                    currentPage * perPage,
                                    filteredJadwal.length
                                )
                            }}
                            dari {{ filteredJadwal.length }} data
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation" class="float-right">
                            <ul class="pagination">
                                <li
                                    class="page-item"
                                    :class="{ disabled: currentPage === 1 }"
                                >
                                    <a
                                        class="page-link"
                                        href="#"
                                        @click.prevent="prevPage"
                                    >
                                        <i class="fas fa-angle-left"></i>
                                    </a>
                                </li>

                                <li
                                    class="page-item"
                                    v-for="page in visiblePages"
                                    :key="page"
                                    :class="{ active: currentPage === page }"
                                >
                                    <a
                                        class="page-link"
                                        href="#"
                                        @click.prevent="goToPage(page)"
                                        >{{ page }}</a
                                    >
                                </li>

                                <li
                                    class="page-item"
                                    :class="{
                                        disabled: currentPage === totalPages,
                                    }"
                                >
                                    <a
                                        class="page-link"
                                        href="#"
                                        @click.prevent="nextPage"
                                    >
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div
        class="modal fade"
        id="jadwalModal"
        tabindex="-1"
        aria-labelledby="jadwalModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jadwalModalLabel">
                        Tambah Jadwal Penimbangan
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click="closeModal"
                        :disabled="isSubmitting"
                    >
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitJadwal">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label"
                                >Tanggal & Waktu</label
                            >
                            <v-select
                                v-model="selectedJadwal"
                                :options="jadwalPeriodeOptions"
                                label="label"
                                :reduce="(jadwal) => jadwal.value"
                                placeholder="Pilih Tanggal Penimbangan"
                                :class="{
                                    'is-invalid': v$.selectedJadwal.$error,
                                }"
                            ></v-select>
                            <div
                                v-if="v$.selectedJadwal.$error"
                                class="invalid-feedback"
                            >
                                Harap pilih tanggal penimbangan
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label"
                                >Lokasi</label
                            >
                            <textarea
                                class="form-control"
                                id="lokasi"
                                v-model="form.lokasi"
                                :class="{ 'is-invalid': v$.form.lokasi.$error }"
                                required
                                placeholder="Masukkan lokasi penimbangan"
                                rows="3"
                                :disabled="isSubmitting"
                            ></textarea>
                            <div
                                v-if="v$.form.lokasi.$error"
                                class="invalid-feedback"
                            >
                                Lokasi harus diisi
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                @click="closeModal"
                                :disabled="isSubmitting"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="isSubmitting"
                            >
                                <span
                                    v-if="isSubmitting"
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                {{
                                    isSubmitting
                                        ? "Menyimpan..."
                                        : "Simpan Jadwal"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div
        class="modal fade"
        id="editJadwalModal"
        tabindex="-1"
        aria-labelledby="editJadwalModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJadwalModalLabel">
                        Edit Jadwal Penimbangan
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click="closeEditModal"
                        :disabled="isSubmitting"
                    >
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateJadwal">
                        <div class="mb-3">
                            <label for="editTanggal" class="form-label"
                                >Tanggal & Waktu</label
                            >
                            <v-select
                                v-model="editForm.selectedJadwal"
                                :options="jadwalPeriodeOptions"
                                label="label"
                                :reduce="(jadwal) => jadwal.value"
                                placeholder="Pilih Tanggal Penimbangan"
                                :class="{
                                    'is-invalid':
                                        v$.editForm.selectedJadwal.$error,
                                }"
                            ></v-select>
                            <div
                                v-if="v$.editForm.selectedJadwal.$error"
                                class="invalid-feedback"
                            >
                                Harap pilih tanggal penimbangan
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editLokasi" class="form-label"
                                >Lokasi</label
                            >
                            <textarea
                                class="form-control"
                                id="editLokasi"
                                v-model="editForm.lokasi"
                                :class="{
                                    'is-invalid': v$.editForm.lokasi.$error,
                                }"
                                required
                                placeholder="Masukkan lokasi penimbangan"
                                rows="3"
                                :disabled="isSubmitting"
                            ></textarea>
                            <div
                                v-if="v$.editForm.lokasi.$error"
                                class="invalid-feedback"
                            >
                                Lokasi harus diisi
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                @click="closeEditModal"
                                :disabled="isSubmitting"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="isSubmitting"
                            >
                                <span
                                    v-if="isSubmitting"
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                {{
                                    isSubmitting
                                        ? "Memperbarui..."
                                        : "Update Jadwal"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { Modal } from "bootstrap";
import { format, parseISO } from "date-fns";
import { id } from "date-fns/locale";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { useRoute } from "vue-router";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect,
    },
    setup() {
        const route = useRoute();
        const v$ = useVuelidate();

        // Mengambil data dari query parameters
        const { id_nasabah, id, name } = route.query;

        // Membuat objek user dari query
        const user = {
            id_nasabah: id_nasabah,
            id: id,
            name: name,
            token: localStorage.getItem("token") || null,
        };

        return { v$, user };
    },
    data() {
        return {
            jadwalPeriodeOptions: [],
            jadwal: [],
            filteredJadwal: [],
            selectedJadwal: null,
            form: {
                id_jadwal_periode: null,
                lokasi: "",
                id_nasabah: null,
            },
            editForm: {
                id_jadwal: null,
                selectedJadwal: null,
                lokasi: "",
                id_nasabah: null,
            },
            searchQuery: "",
            currentPage: 1,
            perPage: 10,
            maxVisiblePages: 5,
            isLoading: false,
            isSubmitting: false,
            lastFetchTime: null,
            cacheDuration: 300000, // 5 minutes cache
        };
    },
    validations() {
        return {
            selectedJadwal: { required },
            form: {
                lokasi: { required },
            },
            editForm: {
                selectedJadwal: { required },
                lokasi: { required },
            },
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.filteredJadwal.length / this.perPage);
        },
        paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredJadwal.slice(start, end);
        },
        visiblePages() {
            const range = [];
            const half = Math.floor(this.maxVisiblePages / 2);
            let start = Math.max(this.currentPage - half, 1);
            let end = Math.min(
                start + this.maxVisiblePages - 1,
                this.totalPages
            );

            if (end - start + 1 < this.maxVisiblePages) {
                start = Math.max(end - this.maxVisiblePages + 1, 1);
            }

            for (let i = start; i <= end; i++) {
                range.push(i);
            }

            return range;
        },
    },
    async mounted() {
        // Validasi user data sebelum fetch
        if (!this.user?.id_nasabah) {
            console.error("Data user tidak valid:", this.user);
            this.$toast.error("Data user tidak valid. Silahkan login kembali.");
            this.$router.push("/login");
            return;
        }

        this.form.id_nasabah = this.user.id_nasabah;
        this.editForm.id_nasabah = this.user.id_nasabah;

        await this.fetchJadwalPeriode();
        await this.fetchJadwal();
    },
    methods: {
        async fetchJadwalPeriode() {
            try {
                this.isLoading = true;
                const response = await axios.get(
                    "http://202.10.47.115/api/fetchJadwalPeriode"
                );

                if (response.data && Array.isArray(response.data)) {
                    this.jadwalPeriodeOptions = response.data.map((item) => ({
                        label: this.formatDateTime(item.tanggal_periode),
                        value: item.tanggal_periode,
                    }));
                } else {
                    throw new Error("Format data tidak valid");
                }
            } catch (error) {
                console.error("Error fetching jadwal periode:", error);
                this.$toast.error("Gagal memuat data periode penimbangan");
            } finally {
                this.isLoading = false;
            }
        },
        async fetchJadwal(forceRefresh = false) {
            // Check cache first
            if (
                !forceRefresh &&
                this.lastFetchTime &&
                Date.now() - this.lastFetchTime < this.cacheDuration
            ) {
                return;
            }

            this.isLoading = true;
            try {
                const response = await axios.get(
                    "http://202.10.47.115/api/getJadwalPendaftaran",
                    {
                        params: {
                            id_nasabah: this.user.id_nasabah,
                        },
                        headers: {
                            Authorization: `Bearer ${this.user.token || ""}`,
                        },
                    }
                );

                if (response.data && Array.isArray(response.data)) {
                    this.jadwal = response.data
                        .map((item) => {
                            if (
                                !item.id_jadwal ||
                                !item.tanggal ||
                                !item.lokasi
                            ) {
                                console.warn("Data tidak lengkap:", item);
                                return null;
                            }

                            return {
                                ...item,
                                id_jadwal: parseInt(item.id_jadwal),
                                formatted_date: this.formatDateTime(
                                    item.tanggal
                                ),
                            };
                        })
                        .filter((item) => item !== null);

                    this.filteredJadwal = [...this.jadwal];
                    this.lastFetchTime = Date.now();

                    if (this.jadwal.length === 0) {
                        this.$toast.info("Tidak ada data jadwal yang tersedia");
                    } else {
                        this.$toast.success(
                            `Berhasil memuat ${this.jadwal.length} jadwal`
                        );
                    }
                } else {
                    throw new Error("Format data tidak valid");
                }
            } catch (error) {
                console.error("Error in fetchJadwal:", error);
                let errorMessage = "Gagal memuat data jadwal";

                if (error.response) {
                    if (error.response.status === 401) {
                        errorMessage = "Anda perlu login kembali";
                        this.$emit("session-expired");
                    } else if (error.response.status === 403) {
                        errorMessage = "Akses ditolak untuk data ini";
                    } else if (error.response.data?.message) {
                        errorMessage = error.response.data.message;
                    }
                } else if (error.message) {
                    errorMessage = error.message;
                }

                this.$toast.error(errorMessage);
                this.jadwal = [];
                this.filteredJadwal = [];
            } finally {
                this.isLoading = false;
            }
        },
        async refreshData() {
            await this.fetchJadwalPeriode();
            await this.fetchJadwal(true);
            this.$toast.info("Data diperbarui");
        },
        formatDateTime(timestamp) {
            try {
                return format(parseISO(timestamp), "dd-MM-yyyy", {
                    locale: id,
                });
            } catch (e) {
                console.error("Error formatting date:", timestamp, e);
                return timestamp;
            }
        },
        filterData() {
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                this.filteredJadwal = this.jadwal.filter((item) => {
                    return (
                        item.lokasi.toLowerCase().includes(query) ||
                        this.formatDateTime(item.tanggal)
                            .toLowerCase()
                            .includes(query)
                    );
                });
            } else {
                this.filteredJadwal = [...this.jadwal];
            }
            this.currentPage = 1;
        },
        resetSearch() {
            this.searchQuery = "";
            this.filterData();
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        showModal() {
            this.selectedJadwal = null;
            this.form = {
                id_jadwal_periode: null,
                lokasi: "",
                id_nasabah: this.user.id_nasabah,
            };
            this.v$.$reset();
            const modal = new Modal(document.getElementById("jadwalModal"));
            modal.show();
        },
        async submitJadwal() {
            this.isSubmitting = true;
            try {
                const selectedPeriode = this.jadwalPeriodeOptions.find(
                    (item) => item.value === this.selectedJadwal
                );

                const dataToSend = {
                    tanggal: this.selectedJadwal,
                    lokasi: this.form.lokasi,
                    id_nasabah: this.user.id_nasabah,
                };

                const response = await axios.post(
                    "http://202.10.47.115/api/addDataJadwal",
                    dataToSend,
                    {
                        headers: {
                            Authorization: `Bearer ${this.user.token || ""}`,
                        },
                    }
                );
                this.$toast.success("Jadwal penimbangan berhasil ditambahkan");
                this.closeModal();
                await this.fetchJadwal(true);
            } catch (error) {
                console.error("Error adding jadwal:", error);
                this.$toast.error(
                    error.response?.data?.message ||
                        error.message ||
                        "Gagal menambahkan jadwal penimbangan"
                );
            } finally {
                this.isSubmitting = false;
            }
        },
        editJadwal(jadwal) {
            const selectedOption = this.jadwalPeriodeOptions.find(
                (option) => option.value === jadwal.tanggal
            );

            this.editForm = {
                id_jadwal: jadwal.id_jadwal,
                selectedJadwal: selectedOption ? selectedOption.value : null,
                lokasi: jadwal.lokasi,
                id_nasabah: this.user.id_nasabah,
            };

            this.v$.$reset();
            const modal = new Modal(document.getElementById("editJadwalModal"));
            modal.show();
        },
        async updateJadwal() {
            this.isSubmitting = true;
            try {
                const selectedPeriode = this.jadwalPeriodeOptions.find(
                    (item) => item.value === this.editForm.selectedJadwal
                );

                const dataToSend = {
                    tanggal: this.editForm.selectedJadwal,
                    lokasi: this.editForm.lokasi,
                    id_nasabah: this.user.id_nasabah,
                };

                const response = await axios.put(
                    `http://202.10.47.115/api/updateDataJadwal/${this.editForm.id_jadwal}`,
                    dataToSend,
                    {
                        headers: {
                            Authorization: `Bearer ${this.user.token || ""}`,
                        },
                    }
                );

                this.$toast.success("Jadwal berhasil diperbarui");
                this.closeEditModal();
                await this.fetchJadwal(true); // Refresh data setelah update
            } catch (error) {
                console.error("Error updating jadwal:", error);
                this.$toast.error(
                    error.response?.data?.message ||
                        error.message ||
                        "Gagal memperbarui jadwal"
                );
            } finally {
                this.isSubmitting = false;
            }
        },
        async deleteJadwal(id) {
            if (!confirm("Apakah Anda yakin ingin menghapus jadwal ini?"))
                return;

            try {
                const response = await axios.delete(
                    `http://202.10.47.115/api/deleteDataJadwal/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.user.token || ""}`,
                        },
                    }
                );
                this.$toast.success("Jadwal berhasil dihapus");
                await this.fetchJadwal(true);
            } catch (error) {
                console.error("Error deleting jadwal:", error);
                this.$toast.error(
                    error.response?.data?.message ||
                        error.message ||
                        "Gagal menghapus jadwal"
                );
            }
        },
        closeModal() {
            const modalElement = document.getElementById("jadwalModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
            this.v$.$reset();
        },
        closeEditModal() {
            const modalElement = document.getElementById("editJadwalModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
            this.v$.$reset();
        },
    },
};
</script>

<style scoped>
.page-item.active .page-link {
    background-color: #4e73df;
    border-color: #4e73df;
}
.page-link {
    color: #4e73df;
}
.page-item.disabled .page-link {
    color: #6c757d;
}
.table-responsive {
    min-height: 300px;
}
.text-muted {
    color: #6c757d !important;
}
.text-success {
    color: #1cc88a !important;
    font-weight: bold;
}
.text-danger {
    color: #e74a3b !important;
    font-weight: bold;
}
.form-control {
    height: auto;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
}
.ml-2 {
    margin-left: 0.5rem;
}
.v-select {
    width: 100%;
}
.is-invalid {
    border-color: #e74a3b !important;
}
.invalid-feedback {
    display: block;
    color: #e74a3b;
    font-size: 0.875rem;
}
</style>
