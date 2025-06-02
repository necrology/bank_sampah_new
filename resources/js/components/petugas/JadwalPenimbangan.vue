<template>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Jadwal Penimbangan</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mt-2 font-weight-bold text-primary">
                        Tabel Data Jadwal Penimbangan
                    </h6>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <button
                        @click="showModal"
                        class="btn btn-sm btn-primary shadow-sm"
                    >
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
                        Data
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Search Field -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Cari berdasarkan nasabah, lokasi, atau tanggal..."
                            v-model="searchQuery"
                            @input="filterData"
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
                            <th>Tanggal</th>
                            <th>Nasabah</th>
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
                                        (currentPage - 1) * perPage + index + 1
                                    }}
                                </td>
                                <td>{{ formatDate(jadwal.tanggal) }}</td>
                                <td>{{ getNasabahName(jadwal.id_nasabah) }}</td>
                                <td>{{ jadwal.lokasi }}</td>
                                <td>
                                    <button
                                        @click="editJadwal(jadwal)"
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="fas fa-edit fa-sm"></i>
                                    </button>
                                    &nbsp;
                                    <button
                                        @click="deleteJadwal(jadwal.id_jadwal)"
                                        class="btn btn-danger btn-sm"
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
                                            Tidak ada data jadwal yang tersedia
                                        </p>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="dataTables_info">
                        Menampilkan {{ (currentPage - 1) * perPage + 1 }} sampai
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
                    >
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addJadwal">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label"
                                >Tanggal</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                id="tanggal"
                                v-model="v$.newJadwal.tanggal.$model"
                                :class="{
                                    'is-invalid': v$.newJadwal.tanggal.$error,
                                }"
                                required
                            />
                            <div
                                v-if="v$.newJadwal.tanggal.$error"
                                class="invalid-feedback"
                            >
                                Tanggal harus diisi
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="nasabah" class="form-label"
                                >Nasabah</label
                            >
                            <v-select
                                v-model="v$.newJadwal.id_nasabah.$model"
                                :options="nasabahOptions"
                                label="nama"
                                :reduce="(nasabah) => nasabah.id_nasabah"
                                placeholder="Pilih Nasabah"
                                :clearable="false"
                                :searchable="true"
                                class="form-control v-select-custom"
                                :class="{
                                    'is-invalid':
                                        v$.newJadwal.id_nasabah.$error,
                                }"
                            >
                                <template #no-options="{ search }">
                                    <div class="text-muted py-2">
                                        Tidak ditemukan nasabah dengan nama "{{
                                            search
                                        }}"
                                    </div>
                                </template>
                            </v-select>
                            <div
                                v-if="v$.newJadwal.id_nasabah.$error"
                                class="invalid-feedback"
                            >
                                Nasabah harus dipilih
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label"
                                >Lokasi</label
                            >
                            <textarea
                                class="form-control"
                                id="lokasi"
                                v-model="v$.newJadwal.lokasi.$model"
                                :class="{
                                    'is-invalid': v$.newJadwal.lokasi.$error,
                                }"
                                required
                                placeholder="Masukkan lokasi penimbangan"
                                rows="3"
                            ></textarea>
                            <div
                                v-if="v$.newJadwal.lokasi.$error"
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
                            >
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Jadwal
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
                    >
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateJadwal">
                        <div class="mb-3">
                            <label for="editTanggal" class="form-label"
                                >Tanggal</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                id="editTanggal"
                                v-model="v$.editJadwalData.tanggal.$model"
                                :class="{
                                    'is-invalid':
                                        v$.editJadwalData.tanggal.$error,
                                }"
                                required
                            />
                            <div
                                v-if="v$.editJadwalData.tanggal.$error"
                                class="invalid-feedback"
                            >
                                Tanggal harus diisi
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editNasabah" class="form-label"
                                >Nasabah</label
                            >
                            <v-select
                                v-model="v$.editJadwalData.id_nasabah.$model"
                                :options="nasabahOptions"
                                label="nama"
                                :reduce="(nasabah) => nasabah.id_nasabah"
                                placeholder="Pilih Nasabah"
                                :clearable="false"
                                :searchable="true"
                                class="form-control v-select-custom"
                                :class="{
                                    'is-invalid':
                                        v$.editJadwalData.id_nasabah.$error,
                                }"
                            >
                                <template #no-options="{ search }">
                                    <div class="text-muted py-2">
                                        Tidak ditemukan nasabah dengan nama "{{
                                            search
                                        }}"
                                    </div>
                                </template>
                            </v-select>
                            <div
                                v-if="v$.editJadwalData.id_nasabah.$error"
                                class="invalid-feedback"
                            >
                                Nasabah harus dipilih
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editLokasi" class="form-label"
                                >Lokasi</label
                            >
                            <textarea
                                class="form-control"
                                id="editLokasi"
                                v-model="v$.editJadwalData.lokasi.$model"
                                :class="{
                                    'is-invalid':
                                        v$.editJadwalData.lokasi.$error,
                                }"
                                required
                                placeholder="Masukkan lokasi penimbangan"
                                rows="3"
                            ></textarea>
                            <div
                                v-if="v$.editJadwalData.lokasi.$error"
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
                            >
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Update Jadwal
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
import vSelect from "vue-select";
import { format, parseISO } from "date-fns";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import "vue-select/dist/vue-select.css";

export default {
    components: { vSelect },
    setup() {
        return { v$: useVuelidate() };
    },
    data() {
        return {
            jadwal: [],
            filteredJadwal: [],
            newJadwal: {
                tanggal: "",
                lokasi: "",
                id_nasabah: null,
            },
            editJadwalData: {
                id: null,
                tanggal: "",
                lokasi: "",
                id_nasabah: null,
            },
            nasabahOptions: [],
            searchQuery: "",
            currentPage: 1,
            perPage: 10,
            maxVisiblePages: 5,
        };
    },
    validations() {
        return {
            newJadwal: {
                tanggal: { required },
                lokasi: { required },
                id_nasabah: { required },
            },
            editJadwalData: {
                tanggal: { required },
                lokasi: { required },
                id_nasabah: { required },
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
    mounted() {
        this.fetchJadwal();
        this.fetchNasabah();
    },
    methods: {
        async fetchNasabah() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/getNasabah"
                );
                this.nasabahOptions = response.data;
            } catch (error) {
                console.error("Error Fetching Nasabah: ", error);
                this.$toast.error("Gagal memuat data nasabah");
            }
        },
        async fetchJadwal() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/getJadwal"
                );
                this.jadwal = response.data;
                this.filteredJadwal = [...this.jadwal];
                this.$toast.success("Data jadwal berhasil dimuat");
            } catch (error) {
                console.error("Error Fetching Jadwal: ", error);
                this.$toast.error("Gagal memuat data jadwal");
            }
        },
        getNasabahName(id) {
            const nasabah = this.nasabahOptions.find(
                (n) => n.id_nasabah === id
            );
            return nasabah ? nasabah.nama : "Nasabah tidak ditemukan";
        },
        filterData() {
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                this.filteredJadwal = this.jadwal.filter((item) => {
                    const nasabahName = this.getNasabahName(
                        item.id_nasabah
                    ).toLowerCase();
                    const formattedDate = this.formatDate(
                        item.tanggal
                    ).toLowerCase();

                    return (
                        nasabahName.includes(query) ||
                        item.lokasi.toLowerCase().includes(query) ||
                        formattedDate.includes(query)
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
        formatDate(tanggal) {
            return format(parseISO(tanggal), "dd-MM-yyyy");
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        showModal() {
            this.newJadwal = {
                tanggal: "",
                lokasi: "",
                id_nasabah: null,
            };
            this.v$.$reset();
            const modal = new Modal(document.getElementById("jadwalModal"));
            modal.show();
        },
        async addJadwal() {
            try {
                await axios.post(
                    "http://202.10.47.115:8000/api/addDataJadwal",
                    this.newJadwal
                );
                this.$toast.success("Jadwal penimbangan berhasil ditambahkan");
                this.closeModal();
                this.fetchJadwal();
            } catch (error) {
                console.error("Error adding jadwal:", error);
                this.$toast.error("Gagal menambahkan jadwal penimbangan");
            }
        },
        async deleteJadwal(id) {
            if (confirm("Apakah Anda yakin ingin menghapus jadwal ini?")) {
                try {
                    await axios.delete(
                        `http://202.10.47.115:8000/api/deleteDataJadwal/${id}`
                    );
                    this.$toast.success("Jadwal penimbangan berhasil dihapus");
                    this.fetchJadwal();
                } catch (error) {
                    console.error("Error deleting data:", error);
                    this.$toast.error("Gagal menghapus jadwal penimbangan");
                }
            }
        },
        editJadwal(jadwal) {
            this.editJadwalData = {
                id: jadwal.id_jadwal,
                tanggal: jadwal.tanggal.split(" ")[0],
                lokasi: jadwal.lokasi,
                id_nasabah: jadwal.id_nasabah,
            };
            console.log(this.editJadwalData);
            this.v$.$reset();
            const modal = new Modal(document.getElementById("editJadwalModal"));
            modal.show();
        },
        async updateJadwal() {
            try {
                await axios.put(
                    `http://202.10.47.115:8000/api/updateDataJadwal/${this.editJadwalData.id}`,
                    {
                        tanggal: this.editJadwalData.tanggal,
                        lokasi: this.editJadwalData.lokasi,
                        id_nasabah: this.editJadwalData.id_nasabah,
                    }
                );
                this.$toast.success("Jadwal penimbangan berhasil diperbarui");
                this.closeEditModal();
                this.fetchJadwal();
            } catch (error) {
                console.error("Error updating data:", error);
                this.$toast.error("Gagal memperbarui jadwal penimbangan");
            }
        },
        closeModal() {
            const modalElement = document.getElementById("jadwalModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
        },
        closeEditModal() {
            const modalElement = document.getElementById("editJadwalModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
        },
    },
};
</script>

<style scoped>
/* Pagination Styles */
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

/* Table Styles */
.table-responsive {
    min-height: 300px;
}
.text-muted {
    color: #6c757d !important;
}

/* v-select Custom Styles */
.v-select-custom {
    padding: 0;
    border: none;
}

.v-select-custom :deep(.vs__dropdown-toggle) {
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    padding: 0.375rem 0.75rem;
    height: calc(1.5em + 0.75rem + 2px);
}

.v-select-custom :deep(.vs__search) {
    padding: 0;
    margin: 0;
}

.v-select-custom :deep(.vs__dropdown-menu) {
    border-radius: 0 0 0.35rem 0.35rem;
}

.v-select-custom.is-invalid :deep(.vs__dropdown-toggle) {
    border-color: #e74a3b;
}

/* Form Control Styles */
.form-control {
    height: auto;
}
</style>
