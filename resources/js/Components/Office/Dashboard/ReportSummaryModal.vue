<script setup>
import { ref } from 'vue';
import Modal from '../Common/Modal.vue';
import Badge from '../Common/Badge.vue';
import ActionButton from '../Common/ActionButton.vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    client: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['close']);

const selectedYear = ref('2025');
const years = ['2024', '2025', '2026'];
const months = [
    { name: 'Janeiro', key: '01' },
    { name: 'Fevereiro', key: '02' },
    { name: 'Março', key: '03' },
    { name: 'Abril', key: '04' },
    { name: 'Maio', key: '05' },
    { name: 'Junho', key: '06' },
    { name: 'Julho', key: '07' },
    { name: 'Agosto', key: '08' },
    { name: 'Setembro', key: '09' },
    { name: 'Outubro', key: '10' },
    { name: 'Novembro', key: '11' },
    { name: 'Dezembro', key: '12' },
];

const summaries = {
    '2025-01': 'Relatório gerado: Ativo, sem pendências fiscais.',
    '2025-02': 'Relatório não gerado.',
};
</script>

<template>
    <Modal
        :show="show"
        :title="`Resumo de Relatórios - ${props.client?.razao_social || 'Cliente'}`"
        @close="$emit('close')"
        class="animate__animated animate__fadeIn"
    >
        <div class="space-y-6">
            <div>
                <label for="year" class="block text-sm font-medium text-gray-900 dark:text-gray-200">Ano</label>
                <select
                    id="year"
                    v-model="selectedYear"
                    class="mt-1 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary transition-all duration-300"
                >
                    <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                </select>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-200">Resumo por Mês</h3>
                <ul class="mt-2 space-y-3">
                    <li v-for="month in months" :key="month.key" class="flex justify-between items-center">
                        <span class="text-gray-900 dark:text-white">{{ month.name }}</span>
                        <Badge
                            :type="props.client?.reports?.[`${selectedYear}-${month.key}`] ? 'success' : 'warning'"
                            :text="props.client?.reports?.[`${selectedYear}-${month.key}`] ? 'Gerado' : 'Não Gerado'"
                        />
                    </li>
                </ul>
            </div>
            <div v-if="summaries[`${selectedYear}-01`]" class="mt-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-200">Resumo AI</h3>
                <p class="text-gray-900 dark:text-white">{{ summaries[`${selectedYear}-01`] }}</p>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <ActionButton
                type="secondary"
                :icon="XMarkIcon"
                @click="$emit('close')"
            >
                Fechar
            </ActionButton>
        </div>
    </Modal>
</template>
