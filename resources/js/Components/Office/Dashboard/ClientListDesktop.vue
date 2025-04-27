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
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">
                        <input
                            type="checkbox"
                            @change="selectedClients = $event.target.checked ? clients.map(c => c.id) : []"
                            :checked="selectedClients.length === clients.length && clients.length > 0"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            aria-label="Selecionar todos os clientes"
                        />
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">CNPJ</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Razão Social</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Status Relatório</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Ações</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr
                    v-for="client in clients"
                    :key="client.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                >
                    <td class="px-6 py-4">
                        <input
                            type="checkbox"
                            :value="client.id"
                            v-model="selectedClients"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            :aria-label="`Selecionar ${client.razao_social}`"
                        />
                    </td>
                    <td class="px-6 py-4 text-gray-900 dark:text-gray-200">{{ client.cnpj }}</td>
                    <td class="px-6 py-4 text-gray-900 dark:text-gray-200">{{ client.razao_social }}</td>
                    <td class="px-6 py-4">
                        <Badge
                            :type="client.reports?.[currentMonth] ? 'success' : 'warning'"
                            :text="client.reports?.[currentMonth] ? 'Gerado' : 'Não Gerado'"
                        />
                    </td>
                    <td class="px-6 py-4 flex space-x-2">
                        <ActionButton type="primary" :icon="ArrowDownTrayIcon" @click="$emit('download-report', client.id)">
                            Baixar
                        </ActionButton>
                        <ActionButton type="info" :icon="EyeIcon" @click="$emit('view-reports', client)">
                            Resumo
                        </ActionButton>
                        <ActionButton type="warning" :icon="PencilIcon" @click="$emit('edit-client', client)">
                            Editar
                        </ActionButton>
                    </td>
                </tr>
                <tr v-if="!clients.length">
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        Nenhum cliente cadastrado.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
