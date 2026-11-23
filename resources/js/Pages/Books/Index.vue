<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, computed, watch, onBeforeUnmount } from "vue";
import axios from "axios";

const props = defineProps({
    books: Object,
    search: String,
});

const keyword = ref(props.search ?? "");
const hasilCari = ref(null);
const sedangMencari = ref(false);
const pesanError = ref("");

let timer = null;
let permintaan = null;

const daftarBuku = computed(() => hasilCari.value ?? props.books.data);

async function ambilHasil() {
    if (permintaan) {
        permintaan.abort();
    }
    permintaan = new AbortController();
    sedangMencari.value = true;
    pesanError.value = "";

    try {
        const respons = await axios.get(route("books.search"), {
            params: { q: keyword.value.trim() },
            signal: permintaan.signal,
        });
        hasilCari.value = respons.data;
        sedangMencari.value = false;
    } catch (error) {
        if (axios.isCancel(error)) {
            return;
        }
        sedangMencari.value = false;
        pesanError.value =
            "Pencarian gagal. Muat ulang halaman, lalu coba lagi.";
    }
}

watch(keyword, (nilai) => {
    clearTimeout(timer);

    if (nilai.trim() === "") {
        if (permintaan) {
            permintaan.abort();
        }
        hasilCari.value = null;
        sedangMencari.value = false;
        pesanError.value = "";
        return;
    }

    timer = setTimeout(ambilHasil, 800);
});

function hapus(id) {
    if (confirm("Yakin ingin menghapus buku ini?")) {
        router.delete(route("books.destroy", id), {
            onSuccess: () => {
                if (keyword.value.trim() !== "") {
                    ambilHasil();
                }
            },
        });
    }
}

onBeforeUnmount(() => {
    clearTimeout(timer);
    if (permintaan) {
        permintaan.abort();
    }
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-4">
            <h1 class="text-xl font-bold">Data Buku</h1>
            <div v-if="$page.props.flash.success" class="my-2 text-green-700">
                {{ $page.props.flash.success }}
            </div>

            <div class="my-3 flex items-center gap-3">
                <Link
                    href="/books/create"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                    >+ Tambah Buku</Link
                >
                <input
                    v-model="keyword"
                    type="text"
                    placeholder="Ketik judul, pengarang, atau penerbit..."
                    class="border p-2 rounded w-64"
                />
                <span v-if="sedangMencari" class="text-gray-500"
                    >Mencari...</span
                >
            </div>

            <div v-if="pesanError" class="my-2 text-red-600">
                {{ pesanError }}
            </div>

            <table class="border-collapse w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-3 py-2">Kode</th>
                        <th class="border px-3 py-2">Judul</th>
                        <th class="border px-3 py-2">Pengarang</th>
                        <th class="border px-3 py-2">Stok</th>
                        <th class="border px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="book in daftarBuku" :key="book.id">
                        <td class="border px-3 py-2">{{ book.kode_buku }}</td>
                        <td class="border px-3 py-2">{{ book.judul }}</td>
                        <td class="border px-3 py-2">{{ book.pengarang }}</td>
                        <td class="border px-3 py-2 text-center">
                            {{ book.stok }}
                        </td>
                        <td class="border px-3 py-2 text-center">
                            <Link
                                :href="`/books/${book.id}/edit`"
                                class="mr-3 text-blue-600"
                                >Edit</Link
                            >
                            <button
                                @click="hapus(book.id)"
                                class="text-red-600"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <tr v-if="daftarBuku.length === 0">
                        <td colspan="5" class="border px-3 py-2 text-center">
                            Tidak ada buku yang ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="hasilCari === null" class="mt-3 flex gap-3">
                <template v-for="link in books.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        v-html="link.label"
                        class="text-blue-600"
                    />
                    <span v-else v-html="link.label" class="text-gray-400" />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
