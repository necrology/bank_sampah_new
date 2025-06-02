<template>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Jadwal Periode</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mt-2 font-weight-bold text-primary">
                        Tabel Data Jadwal Periode
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
                            placeholder="Cari berdasarkan tanggal periode..."
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
                            <th>ID Periode</th>
                            <th>Tanggal Periode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="filteredJadwal.length > 0">
                            <tr
                                v-for="(jadwal, index) in paginatedData"
                                :key="jadwal.id_periode"
                            >
                                <td>
                                    {{
                                        (currentPage - 1) * perPage + index + 1
                                    }}
                                </td>
                                <td>{{ jadwal.id_periode }}</td>
                                <td>
                                    {{ formatDate(jadwal.tanggal_periode) }}
                                </td>
                                <td>
                                    <button
                                        @click="editJadwal(jadwal)"
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="fas fa-edit fa-sm"></i>
                                    </button>
                                    &nbsp;
                                    <button
                                        @click="deleteJadwal(jadwal.id_periode)"
                                        class="btn btn-danger btn-sm"
                                    >
                                        <i class="fas fa-trash fa-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="4" class="text-center py-4">
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
                        Tambah Jadwal Periode
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
                            <label for="tanggal_periode" class="form-label"
                                >Tanggal Periode</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                id="tanggal_periode"
                                v-model="v$.newJadwal.tanggal_periode.$model"
                                :class="{
                                    'is-invalid':
                                        v$.newJadwal.tanggal_periode.$error,
                                }"
                                required
                            />
                            <div
                                v-if="v$.newJadwal.tanggal_periode.$error"
                                class="invalid-feedback"
                            >
                                Tanggal periode harus diisi
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
                                Simpan Periode
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
                        Edit Jadwal Periode
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
                            <label for="editTanggal_periode" class="form-label"
                                >Tanggal Periode</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                id="editTanggal_periode"
                                v-model="
                                    v$.editJadwalData.tanggal_periode.$model
                                "
                                :class="{
                                    'is-invalid':
                                        v$.editJadwalData.tanggal_periode
                                            .$error,
                                }"
                                required
                            />
                            <div
                                v-if="v$.editJadwalData.tanggal_periode.$error"
                                class="invalid-feedback"
                            >
                                Tanggal periode harus diisi
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
                                Update Periode
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
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";

export default {
    setup() {
        return { v$: useVuelidate() };
    },
    data() {
        return {
            jadwal: [],
            filteredJadwal: [],
            newJadwal: {
                tanggal_periode: "",
            },
            editJadwalData: {
                id_periode: null,
                tanggal_periode: "",
            },
            searchQuery: "",
            currentPage: 1,
            perPage: 10,
            maxVisiblePages: 5,
        };
    },
    validations() {
        return {
            newJadwal: {
                tanggal_periode: { required },
            },
            editJadwalData: {
                tanggal_periode: { required },
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
    },
    methods: {
        async fetchJadwal() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/getJadwalPeriode"
                );
                this.jadwal = response.data;
                this.filteredJadwal = [...this.jadwal];
                this.$toast.success("Data jadwal periode berhasil dimuat");
            } catch (error) {
                console.error("Error Fetching Jadwal periode: ", error);
                this.$toast.error("Gagal memuat data jadwal periode");
            }
        },
        filterData() {
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                this.filteredJadwal = this.jadwal.filter((item) => {
                    const formattedDate = this.formatDate(
                        item.tanggal_periode
                    ).toLowerCase();
                    return (
                        formattedDate.includes(query) ||
                        item.id_periode.toString().includes(query)
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
        formatDate(tanggal_periode) {
            return format(parseISO(tanggal_periode), "dd-MM-yyyy");
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
                tanggal_periode: "",
            };
            this.v$.$reset();
            const modal = new Modal(document.getElementById("jadwalModal"));
            modal.show();
        },
        async addJadwal() {
            try {
                await axios.post(
                    "http://202.10.47.115:8000/api/addDataJadwalPeriode",
                    this.newJadwal
                );
                this.$toast.success("Jadwal periode berhasil ditambahkan");
                this.closeModal();
                this.fetchJadwal();
            } catch (error) {
                console.error("Error adding jadwal:", error);
                this.$toast.error("Gagal menambahkan jadwal periode");
            }
        },
        async deleteJadwal(id) {
            if (confirm("Apakah Anda yakin ingin menghapus periode ini?")) {
                try {
                    await axios.delete(
                        `http://202.10.47.115:8000/api/deleteDataJadwalPeriode/${id}`
                    );
                    this.$toast.success("Jadwal periode berhasil dihapus");
                    this.fetchJadwal();
                } catch (error) {
                    console.error("Error deleting data:", error);
                    this.$toast.error("Gagal menghapus jadwal periode");
                }
            }
        },
        editJadwal(jadwal) {
            this.editJadwalData = {
                id_periode: jadwal.id_periode,
                tanggal_periode: jadwal.tanggal_periode.split(" ")[0],
            };
            this.v$.$reset();
            const modal = new Modal(document.getElementById("editJadwalModal"));
            modal.show();
        },
        async updateJadwal() {
            try {
                await axios.put(
                    `http://202.10.47.115:8000/api/updateDataJadwalPeriode/${this.editJadwalData.id_periode}`,
                    {
                        tanggal_periode: this.editJadwalData.tanggal_periode,
                    }
                );
                this.$toast.success("Jadwal periode berhasil diperbarui");
                this.closeEditModal();
                this.fetchJadwal();
            } catch (error) {
                console.error("Error updating data:", error);
                this.$toast.error("Gagal memperbarui jadwal periode");
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

/* Form Control Styles */
.form-control {
    height: auto;
}
</style>
