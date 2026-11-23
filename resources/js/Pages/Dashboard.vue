<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BarChart from "@/Components/BarChart.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    statistik: Object,
    grafik: Object,
});

function formatRupiah(angka) {
    return "Rp" + Number(angka).toLocaleString("id-ID");
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="p-4">
            <!-- Tugas Mandiri J.2: Grid diubah menjadi xl:grid-cols-5 -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <!-- KARTU 1: Judul Buku -->
                <div
                    class="rounded-lg border-l-4 border-blue-500 bg-white p-5 shadow-sm"
                >
                    <div class="text-sm text-gray-500">Judul Buku</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ statistik.total_buku }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Total stok: {{ statistik.total_stok }} eksemplar
                    </div>
                </div>

                <!-- KARTU 2: Anggota -->
                <div
                    class="rounded-lg border-l-4 border-green-500 bg-white p-5 shadow-sm"
                >
                    <div class="text-sm text-gray-500">Anggota</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ statistik.total_anggota }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Terdaftar di perpustakaan
                    </div>
                </div>

                <!-- KARTU 3: Sedang Dipinjam -->
                <div
                    class="rounded-lg border-l-4 border-yellow-500 bg-white p-5 shadow-sm"
                >
                    <div class="text-sm text-gray-500">Sedang Dipinjam</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ statistik.sedang_dipinjam }}
                    </div>
                    <div
                        class="text-xs"
                        :class="
                            statistik.terlambat > 0
                                ? 'text-red-600'
                                : 'text-gray-500'
                        "
                    >
                        {{ statistik.terlambat }} terlambat
                    </div>
                </div>

                <!-- KARTU 4: Total Denda -->
                <div
                    class="rounded-lg border-l-4 border-red-500 bg-white p-5 shadow-sm"
                >
                    <div class="text-sm text-gray-500">Total Denda</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ formatRupiah(statistik.total_denda) }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Dari buku yang sudah dikembalikan
                    </div>
                </div>

                <!-- KARTU 5: Peminjaman Hari Ini (Tugas Mandiri J.2) -->
                <div
                    class="rounded-lg border-l-4 border-purple-500 bg-white p-5 shadow-sm"
                >
                    <div class="text-sm text-gray-500">Peminjaman Hari Ini</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ statistik.pinjam_hari_ini }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Transaksi tanggal hari ini
                    </div>
                </div>
            </div>

            <!-- Kotak Grafik 12 Bulan Terakhir (Tugas Mandiri J.3) -->
            <div class="mt-4 rounded-lg bg-white p-5 shadow-sm">
                <h3 class="mb-3 font-semibold text-gray-800">
                    Peminjaman 12 Bulan Terakhir
                </h3>
                <BarChart
                    :label="grafik.label"
                    :jumlah="grafik.jumlah"
                    judul="Jumlah peminjaman"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
