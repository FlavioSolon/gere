<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/Office/AuthenticatedLayout.vue';
import axios from 'axios';

const { props } = usePage();
const office = props.office || null;
const defaultCnpj = props.default_cnpj || '99999999999';
const flash = props.flash || {};

const form = ref({
    cnpj: defaultCnpj,
});
const errors = ref({});
const submitting = ref(false);
const protocol = ref(null);

async function runTest() {
    if (!office) {
        alert('Nenhum escritório associado. Contate o suporte.');
        return;
    }
    submitting.value = true;
    protocol.value = null;
    errors.value = {};

    try {
        const response = await axios.post(route('office.test-api'), form.value, {
            responseType: 'blob', // Para lidar com respostas binárias (PDF)
            headers: {
                'Accept': 'application/pdf, application/json', // Aceita PDF ou JSON (para erros)
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
            // Tentar ler como JSON se não for PDF
            const text = await response.data.text();
            const data = JSON.parse(text);
            if (data.flash && data.flash.success) {
                protocol.value = data.flash.success;
            } else {
                errors.value = { api: 'Resposta inesperada da API.' };
            }
        }
    } catch (error) {
        if (error.response) {
            // Erro retornado pelo servidor
            const contentType = error.response.headers['content-type'];
            if (contentType && contentType.includes('application/json')) {
                const text = await error.response.data.text();
                const errorData = JSON.parse(text);
                errors.value = errorData.errors || { api: 'Erro ao executar teste.' };
            } else {
                errors.value = { api: 'Erro desconhecido do servidor.' };
            }
        } else {
            // Erro de rede ou configuração
            errors.value = { api: 'Erro ao conectar com o servidor: ' + error.message };
            console.error('Erro na requisição do frontend:', error);
        }
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <Head title="Teste de API SITFIS - GereHub" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Teste de API SITFIS
            </h2>
        </template>
        <div class="bg-white dark:bg-dark text-dark dark:text-white font-sans min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <!-- Mensagem de Erro -->
                <div v-if="flash.error || errors.api" class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md">
                    {{ flash.error || errors.api }}
                </div>

                <!-- Mensagem de Sucesso -->
                <div v-if="flash.success" class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-md">
                    {{ flash.success }}
                </div>

                <!-- Protocolo Retornado -->
                <div v-if="protocol" class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-md">
                    {{ protocol }}
                </div>

                <!-- Informações do Escritório -->
                <section v-if="office" class="mb-12">
                    <div class="bg-primary/5 dark:bg-secondary/10 rounded-lg p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-dark dark:text-white mb-4">
                            {{ office.razao_social }}
                        </h2>
                        <div>
                            <p class="text-sm text-dark/60 dark:text-white/60">CNPJ</p>
                            <p class="text-dark dark:text-white">{{ office.cnpj }}</p>
                        </div>
                    </div>
                </section>

                <!-- Formulário de Teste -->
                <section v-if="office" class="mb-12">
                    <h2 class="text-xl font-semibold text-dark dark:text-white mb-4">
                        Testar API SITFIS
                    </h2>
                    <div class="bg-white dark:bg-dark rounded-lg p-6 shadow-sm">
                        <form @submit.prevent="runTest" class="space-y-4">
                            <div>
                                <label for="cnpj" class="block text-sm font-medium text-dark/80 dark:text-white/80">
                                    CNPJ/CPF (Teste: 99999999999)
                                </label>
                                <input
                                    id="cnpj"
                                    v-model="form.cnpj"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border border-primary/20 dark:border-secondary/20 bg-white dark:bg-dark text-dark dark:text-white focus:ring-accent focus:border-accent"
                                    placeholder="00.000.000/0000-00 ou 00000000000"
                                />
                                <p v-if="errors.cnpj" class="mt-1 text-sm text-red-600">{{ errors.cnpj }}</p>
                            </div>
                            <button
                                type="submit"
                                :disabled="submitting"
                                class="inline-flex items-center px-4 py-2 bg-primary hover:bg-accent text-white rounded-md transition-colors disabled:opacity-50"
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
                                {{ submitting ? 'Testando...' : 'Executar Teste' }}
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
