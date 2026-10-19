<script setup>
import Sidebar from "@/Components/Sidebar.vue";
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Memanggil komponen Sidebar -->
        <Sidebar />

        <!-- Area konten utama -->
        <main class="flex-1 p-8">
            <slot />
        </main>
    </div>
</template>
