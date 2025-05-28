<template>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Profile & Saldo</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mt-2 font-weight-bold text-primary">
                        Data Nasabah {{ user.name || "Guest" }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="card-body row" v-if="dataUser">
            <div class="col-md">
                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input
                        class="form-control"
                        id="name"
                        :value="dataUser.nama || '-'"
                        readonly
                    />
                </div>
                <div class="mb-2">
                    <label class="form-label">Alamat</label>
                    <textarea
                        class="form-control"
                        id="alamat"
                        :value="dataUser.alamat || '-'"
                        readonly
                    ></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Nomor Telepon</label>
                    <input
                        class="form-control"
                        id="nomor_telepon"
                        :value="dataUser.nomor_telepon || '-'"
                        readonly
                    />
                </div>
            </div>
            <div class="col-md">
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input
                        class="form-control"
                        id="email"
                        :value="dataUser.email || '-'"
                        readonly
                    />
                </div>
                <div class="mb-2">
                    <label class="form-label">Saldo</label>
                    <input
                        class="form-control"
                        :value="formatCurrency(dataUser.saldo) || '-'"
                        readonly
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { useRoute } from "vue-router";
import { ref, onMounted } from "vue";

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

        const dataUser = ref(null); // akan diisi object user dari API

        const fetchProfile = async () => {
            try {
                const response = await axios.get(
                    "http://202.10.47.115:8000/api/getProfile",
                    {
                        params: {
                            id_nasabah: user.id_nasabah,
                        },
                        headers: {
                            Authorization: `Bearer ${user.token || ""}`,
                        },
                    }
                );

                // Jika response berupa array, ambil item pertama
                dataUser.value = Array.isArray(response.data)
                    ? response.data[0]
                    : response.data;

                console.log("Data user fetched:", dataUser.value);
            } catch (error) {
                console.error("Error Fetching dataUser: ", error);
            }
        };

        onMounted(() => {
            fetchProfile();
        });

        const formatCurrency = (value) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(value || 0);
        };

        return {
            user,
            dataUser,
            fetchProfile,
            formatCurrency,
        };
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
