<template>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Penimbangan</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mt-2 font-weight-bold text-primary">
                        Tabel Data Penimbangan
                    </h6>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <button
                        @click="showModal"
                        class="btn btn-sm btn-primary shadow-sm mx-2"
                    >
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
                        Data
                    </button>
                    <button
                        @click="exportToExcel"
                        class="btn btn-sm btn-success shadow-sm mr-2"
                    >
                        <i class="fas fa-file-excel fa-sm text-white-50"></i>
                        Export Excel
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
                            placeholder="Cari berdasarkan nama nasabah atau jenis sampah..."
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
                            <th>Tanggal Penimbangan</th>
                            <th>Nama Nasabah</th>
                            <th>Jenis Sampah</th>
                            <th>Berat (Kg)</th>
                            <th>Total (berat x harga/Kg)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="filteredPenimbangan.length > 0">
                            <tr
                                v-for="(item, index) in paginatedData"
                                :key="item.id_penimbangan"
                            >
                                <td>
                                    {{
                                        (currentPage - 1) * perPage + index + 1
                                    }}
                                </td>
                                <td>
                                    {{ formatDate(item.tanggal_penimbangan) }}
                                </td>
                                <td>{{ item.nasabah }}</td>
                                <td>{{ item.jenis }}</td>
                                <td>{{ item.berat }}</td>
                                <td>{{ formatCurrency(item.total) }}</td>
                                <td>
                                    <button
                                        @click="editPenimbangan(item)"
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="fas fa-edit fa-sm"></i>
                                    </button>
                                    &nbsp;
                                    <button
                                        @click="
                                            deletePenimbangan(
                                                item.id_penimbangan
                                            )
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
                                <td colspan="7" class="text-center py-4">
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
                                            Tidak ada data penimbangan yang
                                            tersedia
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
                                filteredPenimbangan.length
                            )
                        }}
                        dari {{ filteredPenimbangan.length }} data
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
        id="penimbanganModal"
        tabindex="-1"
        aria-labelledby="penimbanganModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="penimbanganModalLabel">
                        Tambah Data
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="closeModal"
                        aria-label="Close"
                    >
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addPenimbangan">
                        <div class="mb-3">
                            <label for="id_jadwal" class="form-label"
                                >Jadwal Penimbangan</label
                            >
                            <v-select
                                :options="jadwalOptions"
                                placeholder="Pilih Tanggal"
                                v-model="newPenimbangan.id_jadwal"
                                label="label"
                                :reduce="(option) => option.id_jadwal"
                                required
                            ></v-select>
                        </div>
                        <div class="mb-3">
                            <label for="id_sampah" class="form-label"
                                >Jenis Sampah</label
                            >
                            <v-select
                                :options="sampahOptions"
                                placeholder="Pilih Jenis Sampah"
                                v-model="newPenimbangan.id_sampah"
                                label="label"
                                :reduce="(option) => option.id_sampah"
                                required
                            ></v-select>
                        </div>
                        <div class="mb-3">
                            <label for="berat" class="form-label"
                                >Berat (Kg)</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="berat"
                                @keyup="hitungTotal"
                                v-model="newPenimbangan.berat"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input
                                type="number"
                                class="form-control"
                                id="total"
                                v-model="newPenimbangan.total"
                                readonly
                                required
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
                                Simpan
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
        id="editPenimbanganModal"
        tabindex="-1"
        aria-labelledby="editPenimbanganModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPenimbanganModalLabel">
                        Edit Data
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="closeEditModal"
                        aria-label="Close"
                    >
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updatePenimbangan">
                        <div class="mb-3">
                            <label for="editId_jadwal" class="form-label"
                                >Jadwal Penimbangan</label
                            >
                            <v-select
                                :options="jadwalOptions"
                                placeholder="Pilih Tanggal"
                                v-model="editPenimbanganData.id_jadwal"
                                label="label"
                                :reduce="(option) => option.id_jadwal"
                                required
                            ></v-select>
                        </div>
                        <div class="mb-3">
                            <label for="editId_sampah" class="form-label"
                                >Jenis Sampah</label
                            >
                            <v-select
                                :options="sampahOptions"
                                placeholder="Pilih Jenis Sampah"
                                v-model="editPenimbanganData.id_sampah"
                                label="label"
                                :reduce="(option) => option.id_sampah"
                                required
                            ></v-select>
                        </div>
                        <div class="mb-3">
                            <label for="editBerat" class="form-label"
                                >Berat (Kg)</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="editBerat"
                                @keyup="hitungTotalEdit"
                                v-model="editPenimbanganData.berat"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="editTotal" class="form-label"
                                >Total</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="editTotal"
                                v-model="editPenimbanganData.total"
                                readonly
                                required
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
                                Simpan
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
import vSelect from "vue-select";
import { format } from "date-fns";
import { Modal } from "bootstrap";
import "vue-select/dist/vue-select.css";
import * as XLSX from "xlsx";
import { saveAs } from "file-saver";

export default {
    components: { vSelect },
    data() {
        return {
            penimbangan: [],
            filteredPenimbangan: [],
            newPenimbangan: {
                id_jadwal: "",
                id_sampah: "",
                berat: "",
                total: "",
            },
            editPenimbanganData: {
                id: null,
                id_jadwal: "",
                id_sampah: "",
                berat: "",
                total: "",
            },
            jadwalOptions: [],
            sampahOptions: [],
            sampahPerKg: [],
            searchQuery: "",
            currentPage: 1,
            perPage: 10,
            maxVisiblePages: 5,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.filteredPenimbangan.length / this.perPage);
        },
        paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredPenimbangan.slice(start, end);
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
        this.fetchPenimbangan();
        this.fetchJadwal();
        this.fetchSampah();
    },
    methods: {
        async fetchSampah() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/fetchSampah"
                );
                this.sampahOptions = response.data;
            } catch (error) {
                console.error("Error Fetching Sampah: ", error);
            }
        },
        async fetchJadwal() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/fetchJadwal"
                );
                this.jadwalOptions = response.data;
            } catch (error) {
                console.error("Error Fetching Jadwal: ", error);
            }
        },
        async fetchPenimbangan() {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/getPenimbangan"
                );
                this.penimbangan = response.data;
                this.filteredPenimbangan = [...this.penimbangan];
            } catch (error) {
                console.error("Error Fetching Penimbangan: ", error);
            }
        },
        filterData() {
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                this.filteredPenimbangan = this.penimbangan.filter(
                    (item) =>
                        item.nasabah.toLowerCase().includes(query) ||
                        item.jenis.toLowerCase().includes(query)
                );
            } else {
                this.filteredPenimbangan = [...this.penimbangan];
            }
            this.currentPage = 1;
        },
        resetSearch() {
            this.searchQuery = "";
            this.filterData();
        },
        formatDate(tanggal) {
            return format(new Date(tanggal), "dd-MM-yyyy");
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
            this.newPenimbangan = {
                id_jadwal: "",
                id_sampah: "",
                berat: "",
                total: "",
            };
            const modal = new Modal(
                document.getElementById("penimbanganModal")
            );
            modal.show();
        },
        closeModal() {
            const modal = Modal.getInstance(
                document.getElementById("penimbanganModal")
            );
            modal.hide();
        },
        closeEditModal() {
            const modal = Modal.getInstance(
                document.getElementById("editPenimbanganModal")
            );
            modal.hide();
        },
        async addPenimbangan() {
            try {
                await axios.post(
                    "http://202.10.47.115:8000/api/addDataPenimbangan",
                    this.newPenimbangan
                );
                this.closeModal();
                this.fetchPenimbangan();
            } catch (error) {
                console.error("Error adding post:", error);
            }
        },
        async deletePenimbangan(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                try {
                    await axios.delete(
                        `http://202.10.47.115:8000/api/deleteDataPenimbangan/${id}`
                    );
                    this.fetchPenimbangan();
                } catch (error) {
                    console.error("Error deleting data:", error);
                }
            }
        },
        editPenimbangan(penimbangan) {
            this.editPenimbanganData = {
                id: penimbangan.id_penimbangan,
                id_jadwal: penimbangan.id_jadwal,
                id_sampah: penimbangan.id_sampah,
                berat: penimbangan.berat,
                total: penimbangan.total,
            };
            const modal = new Modal(
                document.getElementById("editPenimbanganModal")
            );
            modal.show();
        },
        async updatePenimbangan() {
            try {
                await axios.put(
                    `http://202.10.47.115:8000/api/updateDataPenimbangan/${this.editPenimbanganData.id}`,
                    {
                        id_jadwal: this.editPenimbanganData.id_jadwal,
                        id_sampah: this.editPenimbanganData.id_sampah,
                        berat: this.editPenimbanganData.berat,
                        total: this.editPenimbanganData.total,
                    }
                );
                this.closeEditModal();
                this.fetchPenimbangan();
            } catch (error) {
                console.error("Error updating data:", error);
            }
        },
        async fetchSampahById() {
            try {
                const response = await axios.post(
                    "http://202.10.47.115:8000/api/fetchSampahById",
                    this.newPenimbangan
                );
                this.sampahPerKg = response.data[0];
            } catch (error) {
                console.error("Error adding post:", error);
            }
        },
        async fetchSampahByIdEdit() {
            try {
                const response = await axios.post(
                    "http://202.10.47.115:8000/api/fetchSampahById",
                    this.editPenimbanganData
                );
                this.sampahPerKg = response.data[0];
            } catch (error) {
                console.error("Error adding post:", error);
            }
        },
        hitungTotal() {
            this.fetchSampahById().then(() => {
                if (
                    this.newPenimbangan.berat &&
                    this.sampahPerKg.harga_per_kg
                ) {
                    this.newPenimbangan.total =
                        this.newPenimbangan.berat *
                        this.sampahPerKg.harga_per_kg;
                }
            });
        },
        hitungTotalEdit() {
            this.fetchSampahByIdEdit().then(() => {
                if (
                    this.editPenimbanganData.berat &&
                    this.sampahPerKg.harga_per_kg
                ) {
                    this.editPenimbanganData.total =
                        this.editPenimbanganData.berat *
                        this.sampahPerKg.harga_per_kg;
                }
            });
        },
        exportToExcel() {
            const exportData = this.filteredPenimbangan.map((item, index) => ({
                No: index + 1,
                "Tanggal Penimbangan": this.formatDate(
                    item.tanggal_penimbangan
                ),
                "Nama Nasabah": item.nasabah,
                "Jenis Sampah": item.jenis,
                "Berat (Kg)": item.berat,
                "Total (Rp)": item.total,
            }));

            const worksheet = XLSX.utils.json_to_sheet(exportData);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(
                workbook,
                worksheet,
                "Data Penimbangan"
            );

            const excelBuffer = XLSX.write(workbook, {
                bookType: "xlsx",
                type: "array",
            });

            const data = new Blob([excelBuffer], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            });

            // Tambahkan tanggal ke nama file
            const today = new Date();
            const dateString = today.toISOString().split("T")[0]; // format YYYY-MM-DD
            const fileName = `Data_Penimbangan_${dateString}.xlsx`;

            saveAs(data, fileName);
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
