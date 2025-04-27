<script setup>
import { CurrencyDollarIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    balance: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div class="relative bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl p-6 shadow-lg flex items-center space-x-4 transform hover:scale-105 transition-transform duration-300 animate__animated animate__fadeIn">
        <div class="bg-white/20 p-3 rounded-full">
            <CurrencyDollarIcon class="h-8 w-8 animate-pulse" />
        </div>
        <div>
            <p class="text-sm font-medium uppercase tracking-wide">Saldo Disponível</p>
            <p class="text-2xl font-bold">R$ {{ (props.balance / 100).toFixed(2) }}</p>
        </div>
    </div>
</template>
