<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    member: Object,
});

const form = useForm({
    _method: "put",
    nama: props.member.nama,
    nis_nim: props.member.nis_nim,
    no_hp: props.member.no_hp,
    foto: null,
});

const pratinjau = ref(null);

function pilihFoto(event) {
    const berkas = event.target.files[0];
    form.foto = berkas ?? null;
    pratinjau.value = berkas ? URL.createObjectURL(berkas) : null;
}

function submit() {
    form.post(route("members.update", props.member.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-4">
            <h1 class="text-xl font-bold">Edit Anggota</h1>
            <form @submit.prevent="submit" class="mt-3 space-y-3 max-w-md">
                <div>
                    <label class="block">Nama</label>
                    <input
                        v-model="form.nama"
                        type="text"
                        class="w-full border p-2"
                    />
                    <div v-if="form.errors.nama" class="text-red-600">
                        {{ form.errors.nama }}
                    </div>
                </div>
                <div>
                    <label class="block">NIS/NIM</label>
                    <input
                        v-model="form.nis_nim"
                        type="text"
                        class="w-full border p-2"
                    />
                    <div v-if="form.errors.nis_nim" class="text-red-600">
                        {{ form.errors.nis_nim }}
                    </div>
                </div>
                <div>
                    <label class="block">No. HP</label>
                    <input
                        v-model="form.no_hp"
                        type="text"
                        class="w-full border p-2"
                    />
                    <div v-if="form.errors.no_hp" class="text-red-600">
                        {{ form.errors.no_hp }}
                    </div>
                </div>
                <div>
                    <label class="block font-bold">Foto Saat Ini</label>
                    <img
                        v-if="member.foto"
                        :src="`/storage/${member.foto}`"
                        alt="Foto saat ini"
                        class="h-32 w-32 object-cover mb-2"
                    />
                    <span v-else class="text-gray-500 block mb-2"
                        >Belum ada foto</span
                    >
                </div>
                <div>
                    <label class="block"
                        >Ganti Foto (kosongkan jika tidak ingin mengganti, maks
                        1 MB)</label
                    >
                    <input
                        type="file"
                        accept="image/png, image/jpeg"
                        @change="pilihFoto"
                        class="mt-1"
                    />
                    <div v-if="form.errors.foto" class="text-red-600">
                        {{ form.errors.foto }}
                    </div>
                    <img
                        v-if="pratinjau"
                        :src="pratinjau"
                        alt="Pratinjau foto baru"
                        class="mt-2 h-32 w-32 object-cover"
                    />
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-blue-600 text-white px-4 py-2 mt-4 rounded"
                >
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
