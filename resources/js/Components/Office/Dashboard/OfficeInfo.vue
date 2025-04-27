<script setup>
const props = defineProps({
    office: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl mb-8 transform hover:scale-105 transition-transform duration-300 animate__animated animate__fadeIn">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ office.razao_social }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">CNPJ</p>
                <p class="text-lg text-gray-900 dark:text-white">{{ office.cnpj }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo</p>
                <p class="text-lg text-gray-900 dark:text-white">R$ {{ (office.balance / 100).toFixed(2) }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">CNPJs Cadastrados</p>
                <p class="text-lg text-gray-900 dark:text-white">{{ office.subscription_cnpjs }}</p>
            </div>
        </div>
    </div>
</template>
