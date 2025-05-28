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
</template>

<script>
import axios from "axios";
import vSelect from "vue-select";
import { format } from "date-fns";
import { Modal } from "bootstrap";
import "vue-select/dist/vue-select.css";
import { useRoute } from "vue-router";

export default {
    components: { vSelect },
    setup() {
        const route = useRoute();

        // Mengambil data dari query parameters
        const { id_nasabah, id, name } = route.query;

        // Membuat objek user dari query
        const user = {
            id_nasabah: id_nasabah,
            id: id,
            name: name,
            token: localStorage.getItem("token") || null,
        };

        return { user };
    },
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
                    "http://202.10.47.115:8000/api/getPenimbanganNasabah",
                    {
                        params: {
                            id_nasabah: this.user.id_nasabah,
                        },
                        headers: {
                            Authorization: `Bearer ${this.user.token || ""}`,
                        },
                    }
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
