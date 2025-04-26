<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/Office/AuthenticatedLayout.vue';
import DownloadReportButton from '@/Components/DownloadReportButton.vue';

const { props } = usePage();
const office = props.office || null;
const clients = props.clients || [];
const error = props.error || null;
const flash = props.flash || {};

const form = ref({
    cnpj: '',
    razao_social: '',
});
const errors = ref({});
const submitting = ref(false);

function addClient() {
    if (!office) {
        alert('Nenhum escritório associado. Contate o suporte.');
        return;
    }
    submitting.value = true;
    router.post(route('office.clients.store'), form.value, {
        preserveState: true,
        onSuccess: () => {
            form.value = { cnpj: '', razao_social: '' };
            errors.value = {};
            submitting.value = false;
        },
        onError: (err) => {
            errors.value = err;
            submitting.value = false;
        },
    });
}
</script>

<template>
    <Head title="Painel do Escritório - GereHub" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Painel do Escritório
            </h2>
        </template>
        <div class="bg-white dark:bg-dark text-dark dark:text-white font-sans min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <!-- Mensagem de Erro do Flash -->
                <div v-if="flash.error" class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md">
                    {{ flash.error }}
                </div>

                <!-- Mensagem de Erro Interno -->
                <div v-if="error && !office" class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md">
                    {{ error }}
                </div>

                <!-- Mensagem de Sucesso -->
                <div v-if="flash.success" class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-md">
                    {{ flash.success }}
                </div>

                <!-- Informações do Escritório -->
                <section v-if="office" class="mb-12">
                    <div class="bg-primary/5 dark:bg-secondary/10 rounded-lg p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-dark dark:text-white mb-4">
                            {{ office.razao_social }}
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-dark/60 dark:text-white/60">CNPJ</p>
                                <p class="text-dark dark:text-white">{{ office.cnpj }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-dark/60 dark:text-white/60">Saldo</p>
                                <p class="text-dark dark:text-white">R$ {{ office.balance / 100 }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-dark/60 dark:text-white/60">CNPJs Cadastrados</p>
                                <p class="text-dark dark:text-white">{{ office.subscription_cnpjs }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Adicionar Cliente -->
                <section v-if="office" class="mb-12">
                    <h2 class="text-xl font-semibold text-dark dark:text-white mb-4">
                        Adicionar Novo Cliente
                    </h2>
                    <div class="bg-white dark:bg-dark rounded-lg p-6 shadow-sm">
                        <form @submit.prevent="addClient" class="space-y-4">
                            <div>
                                <label for="cnpj" class="block text-sm font-medium text-dark/80 dark:text-white/80">
                                    CNPJ
                                </label>
                                <input
                                    id="cnpj"
                                    v-model="form.cnpj"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border border-primary/20 dark:border-secondary/20 bg-white dark:bg-dark text-dark dark:text-white focus:ring-accent focus:border-accent"
                                    placeholder="00.000.000/0000-00"
                                />
                                <p v-if="errors.cnpj" class="mt-1 text-sm text-red-600">{{ errors.cnpj }}</p>
                            </div>
                            <div>
                                <label for="razao_social" class="block text-sm font-medium text-dark/80 dark:text-white/80">
                                    Razão Social
                                </label>
                                <input
                                    id="razao_social"
                                    v-model="form.razao_social"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border border-primary/20 dark:border-secondary/20 bg-white dark:bg-dark text-dark dark:text-white focus:ring-accent focus:border-accent"
                                    placeholder="Nome da Empresa"
                                />
                                <p v-if="errors.razao_social" class="mt-1 text-sm text-red-600">{{ errors.razao_social }}</p>
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
                                {{ submitting ? 'Adicionando...' : 'Adicionar Cliente' }}
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Lista de Clientes -->
                <section v-if="office">
                    <h2 class="text-xl font-semibold text-dark dark:text-white mb-4">
                        Clientes
                    </h2>
                    <div class="bg-white dark:bg-dark rounded-lg shadow-sm overflow-hidden">
                        <table class="min-w-full divide-y divide-primary/20 dark:divide-secondary/20">
                            <thead class="bg-primary/5 dark:bg-secondary/10">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-dark/80 dark:text-white/80">
                                    CNPJ
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-dark/80 dark:text-white/80">
                                    Razão Social
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-dark/80 dark:text-white/80">
                                    Ações
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-primary/20 dark:divide-secondary/20">
                            <tr v-for="client in clients" :key="client.id">
                                <td class="px-6 py-4 text-dark dark:text-white">{{ client.cnpj }}</td>
                                <td class="px-6 py-4 text-dark dark:text-white">{{ client.razao_social }}</td>
                                <td class="px-6 py-4">
                                    <DownloadReportButton :client-id="client.id" />
                                </td>
                            </tr>
                            <tr v-if="!clients.length">
                                <td colspan="3" class="px-6 py-4 text-center text-dark/60 dark:text-white/60">
                                    Nenhum cliente cadastrado.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
