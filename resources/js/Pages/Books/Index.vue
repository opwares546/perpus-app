<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    books: Object,
    search: String, // Tambahan Langkah 8: menerima kata kunci terakhir
});

// Tambahan Langkah 8: menyimpan ketikan pengguna
const keyword = ref(props.search);

// Tambahan Langkah 8: dijalankan saat tombol Enter ditekan
function cari() {
    router.get("/books", { search: keyword.value });
}

function hapus(id) {
    if (confirm("Yakin ingin menghapus buku ini?")) {
        router.delete(route("books.destroy", id));
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <h1>Data Buku</h1>

        <!-- Flash message Success -->
        <div
            v-if="$page.props.flash.success"
            style="color: green; margin-bottom: 10px"
        >
            {{ $page.props.flash.success }}
        </div>

        <!-- Flash message Error (Tugas Mandiri J.1) -->
        <div
            v-if="$page.props.flash.error"
            style="color: red; margin-bottom: 10px"
        >
            {{ $page.props.flash.error }}
        </div>

        <Link href="/books/create">+ Tambah Buku</Link>

        <!-- Tambahan Langkah 8: Kotak Pencarian -->
        <div style="margin-top: 15px; margin-bottom: 15px">
            <input
                v-model="keyword"
                @keyup.enter="cari"
                type="text"
                placeholder="Cari judul, pengarang, atau kode..."
                style="padding: 5px; width: 250px"
            />
        </div>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <!-- Tugas Mandiri Pertemuan 6 -->
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="book in books.data" :key="book.id">
                    <td>{{ book.kode_buku }}</td>
                    <td>{{ book.judul }}</td>
                    <td>{{ book.pengarang }}</td>
                    <td>{{ book.tahun }}</td>
                    <!-- Tugas Mandiri Pertemuan 6 -->
                    <td>{{ book.stok }}</td>
                    <td>
                        <!-- Tombol Edit yang sudah diperbaiki -->
                        <Link
                            :href="`/books/${book.id}/edit`"
                            style="margin-right: 10px; color: blue"
                            >Edit</Link
                        >

                        <button @click="hapus(book.id)" style="color: red">
                            Hapus
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>
