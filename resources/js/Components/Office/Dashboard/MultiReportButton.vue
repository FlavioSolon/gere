<script setup>
import ActionButton from '../Common/ActionButton.vue';
import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    selectedCount: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(['generate']);

function generateReports() {
    emit('generate');
}
</script>

<template>
    <ActionButton
        v-if="selectedCount"
        type="primary"
        :icon="ArrowDownTrayIcon"
        @click="generateReports"
        class="animate__animated animate__fadeIn"
    >
        Gerar {{ selectedCount }} Relatórios
    </ActionButton>
</template>
