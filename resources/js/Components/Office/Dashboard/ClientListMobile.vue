<script setup>
import { ref } from 'vue';
import { ArrowDownTrayIcon, EyeIcon, PencilIcon } from '@heroicons/vue/24/outline';
import ActionButton from '../Common/ActionButton.vue';
import Badge from '../Common/Badge.vue';

const props = defineProps({
    clients: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['edit-client', 'view-reports', 'generate-reports']);

const selectedClients = ref([]);
const currentMonth = '2025-01';

function toggleClientSelection(clientId) {
    if (selectedClients.value.includes(clientId)) {
        selectedClients.value = selectedClients.value.filter(id => id !== clientId);
    } else {
        selectedClients.value.push(clientId);
    }
}

function generateMultipleReports() {
    emit('generate-reports', selectedClients.value);
}
</script>

<template>
    <div class="animate__animated animate__fadeIn">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Clientes</h2>
            <ActionButton
                v-if="selectedClients.length"
                type="primary"
                @click="generateMultipleReports"
                :icon="ArrowDownTrayIcon"
            >
                Gerar {{ selectedClients.length }} Relatórios
            </ActionButton>
        </div>
        <div class="space-y-4">
            <div
                v-for="client in clients"
                :key="client.id"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300"
            >
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ client.razao_social }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ client.cnpj }}</p>
                    </div>
                    <input
                        type="checkbox"
                        :value="client.id"
                        v-model="selectedClients"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-6 w-6"
                        :aria-label="`Selecionar ${client.razao_social}`"
                    />
                </div>
                <div class="flex items-center space-x-2 mb-4">
                    <Badge
                        :type="client.reports?.[currentMonth] ? 'success' : 'warning'"
                        :text="client.reports?.[currentMonth] ? 'Gerado' : 'Não Gerado'"
                    />
                </div>
                <div class="flex flex-wrap gap-2">
                    <ActionButton type="primary" :icon="ArrowDownTrayIcon" @click="$emit('download-report', client.id)">
                        Baixar
                    </ActionButton>
                    <ActionButton type="info" :icon="EyeIcon" @click="$emit('view-reports', client)">
                        Resumo
                    </ActionButton>
                    <ActionButton type="warning" :icon="PencilIcon" @click="$emit('edit-client', client)">
                        Editar
                    </ActionButton>
                </div>
            </div>
            <div v-if="!clients.length" class="text-center text-gray-500 dark:text-gray-400 py-6">
                Nenhum cliente cadastrado.
            </div>
        </div>
    </div>
</template>
