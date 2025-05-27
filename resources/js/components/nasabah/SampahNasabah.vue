<template>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Sampah</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mt-2 font-weight-bold text-primary">
                        Tabel Data Jenis Sampah
                    </h6>
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
                            placeholder="Cari berdasarkan jenis atau harga..."
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
                            <th>Jenis</th>
                            <th>Harga Per Kg</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="filteredSampah.length > 0">
                            <tr
                                v-for="(sampahItem, index) in paginatedData"
                                :key="sampahItem.id_sampah"
                            >
                                <td>
                                    {{
                                        (currentPage - 1) * perPage + index + 1
                                    }}
                                </td>
                                <td>{{ sampahItem.jenis }}</td>
                                <td>
                                    {{
                                        formatCurrency(sampahItem.harga_per_kg)
                                    }}
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
                                            Tidak ada data sampah yang tersedia
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
                                filteredSampah.length
                            )
                        }}
                        dari {{ filteredSampah.length }} data
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
</template>

<script>
import axios from "axios";
import { Modal } from "bootstrap";

export default {
    data() {
        return {
            sampah: [],
            filteredSampah: [],
            newSampah: {
                jenis: "",
                harga_per_kg: "",
            },
            editSampahData: {
                id: null,
                jenis: "",
                harga_per_kg: "",
            },
            searchQuery: "",
            currentPage: 1,
            perPage: 10,
            maxVisiblePages: 5,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.filteredSampah.length / this.perPage);
        },
        paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredSampah.slice(start, end);
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
        this.fetchSampah();
    },
    methods: {
        async fetchSampah() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115/api/getSampah"
                );
                this.sampah = response.data;
                this.filteredSampah = [...this.sampah];
            } catch (error) {
                console.error("Error Fetching Sampah: ", error);
                this.$toast.error("Gagal memuat data sampah");
            }
        },
        filterData() {
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                this.filteredSampah = this.sampah.filter(
                    (item) =>
                        item.jenis.toLowerCase().includes(query) ||
                        item.harga_per_kg.toString().includes(query)
                );
            } else {
                this.filteredSampah = [...this.sampah];
            }
            this.currentPage = 1;
        },
        resetSearch() {
            this.searchQuery = "";
            this.filterData();
        },
        formatCurrency(value) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(value);
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
            this.newSampah = {
                jenis: "",
                harga_per_kg: "",
            };
            const modal = new Modal(document.getElementById("sampahModal"));
            modal.show();
        },
        async addSampah() {
            try {
                await axios.post(
                    "http://202.10.47.115/api/addDataSampah",
                    this.newSampah
                );
                this.$toast.success("Data sampah berhasil ditambahkan");
                this.closeModal();
                this.fetchSampah();
            } catch (error) {
                console.error("Error adding post:", error);
                this.$toast.error("Gagal menambahkan data sampah");
            }
        },
        async deleteSampah(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                try {
                    await axios.delete(
                        `http://202.10.47.115/api/deleteDataSampah/${id}`
                    );
                    this.$toast.success("Data sampah berhasil dihapus");
                    this.fetchSampah();
                } catch (error) {
                    console.error("Error deleting data:", error);
                    this.$toast.error("Gagal menghapus data sampah");
                }
            }
        },
        editSampah(sampah) {
            this.editSampahData = {
                id: sampah.id_sampah,
                jenis: sampah.jenis,
                harga_per_kg: sampah.harga_per_kg,
            };
            const modal = new Modal(document.getElementById("editSampahModal"));
            modal.show();
        },
        async updateSampah() {
            try {
                await axios.put(
                    `http://202.10.47.115/api/updateDataSampah/${this.editSampahData.id}`,
                    {
                        jenis: this.editSampahData.jenis,
                        harga_per_kg: this.editSampahData.harga_per_kg,
                    }
                );
                this.$toast.success("Data sampah berhasil diperbarui");
                this.closeEditModal();
                this.fetchSampah();
            } catch (error) {
                console.error("Error updating data:", error);
                this.$toast.error("Gagal memperbarui data sampah");
            }
        },
        closeModal() {
            const modalElement = document.getElementById("sampahModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
        },
        closeEditModal() {
            const modalElement = document.getElementById("editSampahModal");
            const modal =
                Modal.getInstance(modalElement) || new Modal(modalElement);
            modal.hide();
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
