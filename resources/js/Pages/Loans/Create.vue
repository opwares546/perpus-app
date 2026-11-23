<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from "@inertiajs/vue3";

defineProps({
    members: Array,
    books: Array,
});

const form = useForm({
    member_id: "",
    book_id: "",
});

function submit() {
    form.post(route("loans.store"));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-4">
            <h1 class="text-xl font-bold">Pinjam Buku</h1>
            <form @submit.prevent="submit" class="mt-3 space-y-3 max-w-md">
                <div>
                    <label class="block">Anggota</label>
                    <select v-model="form.member_id" class="w-full border p-2">
                        <option value="" disabled>-- Pilih anggota --</option>
                        <option
                            v-for="member in members"
                            :key="member.id"
                            :value="member.id"
                        >
                            {{ member.nama }} ({{ member.nis_nim }})
                        </option>
                    </select>
                    <div v-if="form.errors.member_id" class="text-red-600">
                        {{ form.errors.member_id }}
                    </div>
                </div>

                <div>
                    <label class="block">Buku</label>
                    <select v-model="form.book_id" class="w-full border p-2">
                        <option value="" disabled>-- Pilih buku --</option>
                        <option
                            v-for="book in books"
                            :key="book.id"
                            :value="book.id"
                        >
                            {{ book.judul }} (stok: {{ book.stok }})
                        </option>
                    </select>
                    <div v-if="form.errors.book_id" class="text-red-600">
                        {{ form.errors.book_id }}
                    </div>
                    <div v-if="books.length === 0" class="text-yellow-700 mt-1">
                        Belum ada buku dengan stok tersedia.
                    </div>
                </div>

                <p class="text-gray-500 text-sm">
                    Tanggal pinjam adalah hari ini. Jatuh tempo otomatis 14 hari
                    setelah tanggal pinjam.
                </p>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Simpan Peminjaman
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
