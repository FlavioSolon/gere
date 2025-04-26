<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    clientId: {
        type: [Number, String],
        required: true,
    },
});

const submitting = ref(false);

async function downloadReport() {
    submitting.value = true;

    try {
        const response = await axios.get(route('office.clients.report', { clientId: props.clientId }), {
            responseType: 'blob', // Para lidar com PDF
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
            alert('Erro ao baixar o relatório: ' + (errorData.error || 'Tente novamente.'));
        }
    } catch (error) {
        let errorMessage = 'Erro ao conectar com o servidor.';
        if (error.response) {
            const contentType = error.response.headers['content-type'];
            if (contentType && contentType.includes('application/json')) {
                const text = await error.response.data.text();
                const errorData = JSON.parse(text);
                errorMessage = errorData.error || 'Erro ao baixar o relatório.';
            }
        }
        alert(errorMessage);
        console.error('Erro ao baixar relatório:', error);
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <button
        @click="downloadReport"
        :disabled="submitting"
        class="inline-flex items-center px-3 py-1 bg-primary hover:bg-accent text-white rounded-md transition-colors disabled:opacity-50"
    >
        <svg
            v-if="submitting"
            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-16 0z"></path>
        </svg>
        {{ submitting ? 'Baixando...' : 'Baixar Relatório' }}
    </button>
</template>
