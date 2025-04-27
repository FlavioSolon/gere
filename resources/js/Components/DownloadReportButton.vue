<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline';
import ActionButton from './Office/Common/ActionButton.vue';

const props = defineProps({
    clientId: {
        type: [Number, String],
        required: true,
    },
});

const submitting = ref(false);
const errorMessage = ref(null);

async function downloadReport() {
    submitting.value = true;
    errorMessage.value = null;

    try {
        const response = await axios.get(route('office.clients.report', { clientId: props.clientId }), {
            responseType: 'blob',
            headers: {
                'Accept': 'application/pdf, application/json',
            },
        });

        const contentType = response.headers['content-type'];
        if (contentType && contentType.includes('application/pdf')) {
            const blob = response.data;
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = response.headers['content-disposition']?.match(/filename="([^"]+)"/)?.[1] || 'relatorio_sitfis.pdf';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            a.remove();
        } else {
            const text = await response.data.text();
            const errorData = JSON.parse(text);
            errorMessage.value = errorData.error || 'Resposta inesperada do servidor.';
        }
    } catch (error) {
        if (error.response) {
            const contentType = error.response.headers['content-type'];
            if (contentType && contentType.includes('application/json')) {
                const text = await error.response.data.text();
                const errorData = JSON.parse(text);
                errorMessage.value = errorData.error || 'Erro ao baixar o relatório.';
            } else {
                errorMessage.value = 'Erro desconhecido do servidor.';
            }
        } else {
            errorMessage.value = 'Erro ao conectar com o servidor: ' + error.message;
        }
        console.error('Erro ao baixar relatório:', error);
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <div>
        <ActionButton
            type="primary"
            :icon="ArrowDownTrayIcon"
            :loading="submitting"
            @click="downloadReport"
        >
            Baixar
        </ActionButton>
        <p v-if="errorMessage" class="mt-2 text-sm text-red-600">{{ errorMessage }}</p>
    </div>
</template>
