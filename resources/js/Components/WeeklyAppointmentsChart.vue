<script setup>
import { onMounted, ref, watch } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    labels: Array,
    data: Array,
})

const canvas = ref(null)
let chartInstance = null

const renderChart = () => {
    if (chartInstance) {
        chartInstance.destroy()
    }

    chartInstance = new Chart(canvas.value, {
        type: 'bar',
        data: {
            labels: props.labels,
            datasets: [
                {
                    label: 'Rendez-vous',
                    data: props.data,
                    backgroundColor: '#6366f1',
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: {
                    ticks: { color: '#9ca3af' },
                },
                y: {
                    ticks: { color: '#9ca3af' },
                },
            },
        },
    })
}

onMounted(renderChart)

watch(() => props.data, renderChart)
</script>

<template>
    <canvas ref="canvas"></canvas>
</template>
