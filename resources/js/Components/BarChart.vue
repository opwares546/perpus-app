<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import Chart from "chart.js/auto";

const props = defineProps({
    label: Array,
    jumlah: Array,
    judul: { type: String, default: "Jumlah" },
});

const kanvas = ref(null);
let grafik = null;

onMounted(() => {
    grafik = new Chart(kanvas.value, {
        type: "bar", // Untuk Tugas Mandiri J.3, sempatkan uji ganti ke 'line' lalu kembalikan ke 'bar'
        data: {
            labels: [...props.label],
            datasets: [
                {
                    label: props.judul,
                    data: [...props.jumlah],
                    backgroundColor: "#3b82f6",
                    borderColor: "#3b82f6",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                },
            },
        },
    });
});

onBeforeUnmount(() => {
    if (grafik) {
        grafik.destroy();
    }
});
</script>

<template>
    <div class="h-64">
        <canvas ref="kanvas"></canvas>
    </div>
</template>
