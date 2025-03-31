<template>
    <v-card v-if="showChart" color="background">
        <template #title>
            {{ title }}
        </template>
        <template #subtitle>
            {{ subtitle }}
        </template>
        <template #append>
            <v-icon :icon color="primary" />
        </template>
        <template #text>
            <doughnut :data="chartData" />
        </template>
    </v-card>
    <v-card v-else :title subtitle="Não há dados para mostrar" color="background">
        <template #append>
            <v-icon icon="mdi-currency-usd-off" color="primary" />
        </template>
    </v-card>
</template>

<script setup lang="ts">
import type { ChartData } from 'chart.js'

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
    ArcElement,
} from 'chart.js'

import { Doughnut } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, ArcElement, Title, Tooltip, Legend)

ChartJS.overrides['doughnut'].plugins.legend.labels.color = '#FFFFFF'
ChartJS.overrides['doughnut'].plugins.legend.labels.boxWidth = 12
ChartJS.overrides['doughnut'].plugins.legend.labels.boxHeight = 12

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
    chartData: {
        type: Object as () => ChartData<'doughnut'>,
        required: true,
    },
})

const showChart = computed(() => {
    return props.chartData.datasets[0].data.reduce((acc, value) => {
        return acc + value
    }, 0) > 0
})


</script>