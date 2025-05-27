<template>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Nasabah</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mt-2 font-weight-bold text-primary">
                        Tabel Data Nasabah
                    </h6>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <button
                        @click="showModal"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
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
                            placeholder="Cari berdasarkan nama, alamat, atau nomor telepon..."
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
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Telp</th>
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="filteredNasabah.length > 0">
                            <tr
                                v-for="(nasabah, index) in paginatedData"
                                :key="nasabah.id_nasabah"
                            >
                                <td>
                                    {{
                                        (currentPage - 1) * perPage + index + 1
                                    }}
                                </td>
                                <td>{{ nasabah.nama || "-" }}</td>
                                <td>{{ nasabah.alamat || "-" }}</td>
                                <td>{{ nasabah.nomor_telepon || "-" }}</td>
                                <td>
                                    {{ formatCurrency(nasabah.saldo) || "-" }}
                                </td>
                                <td>
                                    <button
                                        @click="editNasabah(nasabah)"
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="fas fa-edit fa-sm"></i>
                                    </button>
                                    &nbsp;
                                    <button
                                        @click="
                                            deleteNasabah(nasabah.id_nasabah)
                                        "
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
                                            Tidak ada data nasabah yang tersedia
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
                                filteredNasabah.length
                            )
                        }}
                        dari {{ filteredNasabah.length }} data
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
        id="nasabahModal"
        tabindex="-1"
        aria-labelledby="nasabahModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nasabahModalLabel">
                        Tambah Data Nasabah
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
                    <form @submit.prevent="addNasabah">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                v-model="newNasabah.nama"
                                required
                                placeholder="Masukkan nama nasabah"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label"
                                >Alamat</label
                            >
                            <textarea
                                class="form-control"
                                id="alamat"
                                v-model="newNasabah.alamat"
                                required
                                placeholder="Masukkan alamat nasabah"
                                rows="3"
                            ></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label"
                                >No Telp</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="nomor_telepon"
                                v-model="newNasabah.nomor_telepon"
                                required
                                placeholder="Masukkan nomor telepon"
                            />
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
                                Simpan Data
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
        id="editNasabahModal"
        tabindex="-1"
        aria-labelledby="editNasabahModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNasabahModalLabel">
                        Edit Data Nasabah
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
                    <form @submit.prevent="updateNasabah">
                        <div class="mb-3">
                            <label for="editNama" class="form-label"
                                >Nama</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="editNama"
                                v-model="editNasabahData.nama"
                                required
                                placeholder="Masukkan nama nasabah"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="editAlamat" class="form-label"
                                >Alamat</label
                            >
                            <textarea
                                class="form-control"
                                id="editAlamat"
                                v-model="editNasabahData.alamat"
                                required
                                placeholder="Masukkan alamat nasabah"
                                rows="3"
                            ></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="EditNomor_telepon" class="form-label"
                                >No Telp</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="EditNomor_telepon"
                                v-model="editNasabahData.nomor_telepon"
                                required
                                placeholder="Masukkan nomor telepon"
                            />
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
                                Update Data
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

export default {
    data() {
        return {
            nasabah: [],
            filteredNasabah: [],
            newNasabah: {
                nama: "",
                alamat: "",
                nomor_telepon: "",
            },
            editNasabahData: {
                id: null,
                nama: "",
                alamat: "",
                nomor_telepon: "",
            },
            searchQuery: "",
            currentPage: 1,
            perPage: 10,
            maxVisiblePages: 5,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.filteredNasabah.length / this.perPage);
        },
        paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredNasabah.slice(start, end);
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
        this.fetchNasabah();
    },
    methods: {
        async fetchNasabah() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115/api/getNasabah"
                );
                this.nasabah = response.data;
                this.filteredNasabah = [...this.nasabah];
                this.$toast.success("Data nasabah berhasil dimuat");
            } catch (error) {
                console.error("Error Fetching Nasabah: ", error);
                this.$toast.error("Gagal memuat data nasabah");
            }
        },
        filterData() {
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                this.filteredNasabah = this.nasabah.filter(
                    (item) =>
                        item.nama.toLowerCase().includes(query) ||
                        item.alamat.toLowerCase().includes(query) ||
                        item.nomor_telepon.toLowerCase().includes(query)
                );
            } else {
                this.filteredNasabah = [...this.nasabah];
            }
            this.currentPage = 1;
        },
        resetSearch() {
            this.searchQuery = "";
            this.filterData();
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
            this.newNasabah = {
                nama: "",
                alamat: "",
                nomor_telepon: "",
            };
            const modal = new Modal(document.getElementById("nasabahModal"));
            modal.show();
        },
        async addNasabah() {
            try {
                await axios.post(
                    "http://202.10.47.115/api/addDataNasabah",
                    this.newNasabah
                );
                this.$toast.success("Data nasabah berhasil ditambahkan");
                this.closeModal();
                this.fetchNasabah();
            } catch (error) {
                console.error("Error adding nasabah:", error);
                this.$toast.error("Gagal menambahkan data nasabah");
            }
        },
        async deleteNasabah(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                try {
                    await axios.delete(
                        `http://202.10.47.115/api/deleteDataNasabah/${id}`
                    );
                    this.$toast.success("Data nasabah berhasil dihapus");
                    this.fetchNasabah();
                } catch (error) {
                    console.error("Error deleting data:", error);
                    this.$toast.error("Gagal menghapus data nasabah");
                }
            }
        },
        editNasabah(nasabah) {
            this.editNasabahData = {
                id: nasabah.id_nasabah,
                nama: nasabah.nama,
                alamat: nasabah.alamat,
                nomor_telepon: nasabah.nomor_telepon,
            };
            const modal = new Modal(
                document.getElementById("editNasabahModal")
            );
            modal.show();
        },
        async updateNasabah() {
            try {
                await axios.put(
                    `http://202.10.47.115/api/updateDataNasabah/${this.editNasabahData.id}`,
                    {
                        nama: this.editNasabahData.nama,
                        alamat: this.editNasabahData.alamat,
                        nomor_telepon: this.editNasabahData.nomor_telepon,
                    }
                );
                this.$toast.success("Data nasabah berhasil diperbarui");
                this.closeEditModal();
                this.fetchNasabah();
            } catch (error) {
                console.error("Error updating data:", error);
                this.$toast.error("Gagal memperbarui data nasabah");
            }
        },
        closeModal() {
            const modalElement = document.getElementById("nasabahModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
        },
        closeEditModal() {
            const modalElement = document.getElementById("editNasabahModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
        },
        formatCurrency(value) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(value);
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
</style>
