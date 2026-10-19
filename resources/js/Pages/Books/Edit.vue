<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from "@inertiajs/vue3";

// 'book' diterima dari BookController@edit (Langkah 2), berisi data lama buku ini[cite: 8]
const props = defineProps({
    book: Object,
});

// Berbeda dari Create.vue: nilai awal form diisi dari props.book, bukan string/angka kosong
const form = useForm({
    kode_buku: props.book.kode_buku,
    judul: props.book.judul,
    pengarang: props.book.pengarang,
    penerbit: props.book.penerbit,
    tahun: props.book.tahun,
    stok: props.book.stok,
});

function submit() {
    // form.put() karena ini operasi Update[cite: 8]
    form.put(route("books.update", props.book.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <div style="padding: 20px">
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px">
                Edit Buku
            </h1>
            <form
                @submit.prevent="submit"
                style="
                    display: flex;
                    flex-direction: column;
                    gap: 15px;
                    max-width: 400px;
                "
            >
                <div>
                    <label>Kode Buku</label>
                    <input
                        v-model="form.kode_buku"
                        type="text"
                        style="width: 100%"
                    />
                    <span
                        v-if="form.errors.kode_buku"
                        style="color: red; font-size: 14px"
                        >{{ form.errors.kode_buku }}</span
                    >
                </div>

                <div>
                    <label>Judul</label>
                    <input
                        v-model="form.judul"
                        type="text"
                        style="width: 100%"
                    />
                </div>

                <div>
                    <label>Pengarang</label>
                    <input
                        v-model="form.pengarang"
                        type="text"
                        style="width: 100%"
                    />
                </div>

                <div>
                    <label>Penerbit</label>
                    <input
                        v-model="form.penerbit"
                        type="text"
                        style="width: 100%"
                    />
                </div>

                <div>
                    <label>Tahun</label>
                    <input
                        v-model="form.tahun"
                        type="text"
                        style="width: 100%"
                    />
                </div>

                <div>
                    <label>Stok</label>
                    <input
                        v-model="form.stok"
                        type="number"
                        style="width: 100%"
                    />
                </div>

                <button
                    type="submit"
                    style="
                        padding: 10px;
                        background-color: #4caf50;
                        color: white;
                        border: none;
                        cursor: pointer;
                        border-radius: 5px;
                    "
                >
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
