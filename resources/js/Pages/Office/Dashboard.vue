<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { PlusIcon } from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/Office/AuthenticatedLayout.vue';
import OfficeInfo from '@/Components/Office/Dashboard/OfficeInfo.vue';
import BalanceCard from '@/Components/Office/Dashboard/BalanceCard.vue';
import ClientListDesktop from '@/Components/Office/Dashboard/ClientListDesktop.vue';
import ClientListMobile from '@/Components/Office/Dashboard/ClientListMobile.vue';
import AddClientModal from '@/Components/Office/Dashboard/AddClientModal.vue';
import EditClientModal from '@/Components/Office/Dashboard/EditClientModal.vue';
import ReportSummaryModal from '@/Components/Office/Dashboard/ReportSummaryModal.vue';
import ActionButton from '@/Components/Office/Common/ActionButton.vue';

// Dados estáticos
const staticOffice = {
    razao_social: 'Escritório Contábil XYZ',
    cnpj: '55.025.422/0001-03',
    balance: 150000, // R$ 1500,00
    subscription_cnpjs: 2,
};

const staticClients = [
    {
        id: 1,
        cnpj: '12.345.678/0001-99',
        razao_social: 'Cliente Teste LTDA',
        reports: {
            '2025-01': true,
            '2025-02': false,
        },
    },
    {
        id: 2,
        cnpj: '98.765.432/0001-00',
        razao_social: 'Empresa Exemplo S/A',
        reports: {
            '2025-01': false,
            '2025-02': true,
        },
    },
];

const { props } = usePage();
const flash = props.flash || {};

const showAddModal = ref(false);
const showEditModal = ref(false);
const showReportModal = ref(false);
const selectedClient = ref(null);

function openEditModal(client) {
    selectedClient.value = client;
    showEditModal.value = true;
}

function openReportModal(client) {
    selectedClient.value = client;
    showReportModal.value = true;
}

function updateClient(updatedData) {
    console.log('Cliente atualizado:', updatedData); // Simulação
}

function generateMultipleReports(clientIds) {
    console.log('Gerando relatórios para clientes:', clientIds); // Simulação
}
</script>

<template>
    <Head title="Painel do Escritório - GereHub" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white animate__animated animate__fadeIn">
                Painel do Escritório
            </h2>
        </template>
        <div class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white font-sans min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <!-- Mensagens -->
                <div v-if="flash.error" class="mb-6 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-xl shadow-md animate__animated animate__shakeX">
                    {{ flash.error }}
                </div>
                <div v-if="flash.success" class="mb-6 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-xl shadow-md animate__animated animate__bounceIn">
                    {{ flash.success }}
                </div>

                <!-- Informações do Escritório -->
                <OfficeInfo :office="staticOffice" class="mb-8" />

                <!-- Cabeçalho com Saldo e Botão Adicionar -->
                <div class="flex justify-between items-center mb-6">
                    <div></div> <!-- Espaço vazio para alinhamento -->
                    <div class="flex items-center space-x-4">
                        <BalanceCard :balance="staticOffice.balance" />
                        <ActionButton
                            type="primary"
                            :icon="PlusIcon"
                            @click="showAddModal = true"
                            class="md:hidden rounded-full h-14 w-14 p-0 flex items-center justify-center"
                        >
                            <span class="sr-only">Adicionar Cliente</span>
                        </ActionButton>
                        <ActionButton
                            type="primary"
                            :icon="PlusIcon"
                            @click="showAddModal = true"
                            class="hidden md:flex"
                        >
                            Adicionar Cliente
                        </ActionButton>
                    </div>
                </div>

                <!-- Lista de Clientes -->
                <div class="md:hidden">
                    <ClientListMobile
                        :clients="staticClients"
                        @edit-client="openEditModal"
                        @view-reports="openReportModal"
                        @generate-reports="generateMultipleReports"
                        @download-report="console.log('Baixar relatório:', $event)"
                    />
                </div>
                <div class="hidden md:block">
                    <ClientListDesktop
                        :clients="staticClients"
                        @edit-client="openEditModal"
                        @view-reports="openReportModal"
                        @generate-reports="generateMultipleReports"
                        @download-report="console.log('Baixar relatório:', $event)"
                    />
                </div>

                <!-- Modais -->
                <AddClientModal :show="showAddModal" @close="showAddModal = false" />
                <EditClientModal
                    :show="showEditModal"
                    :client="selectedClient"
                    @close="showEditModal = false"
                    @update="updateClient"
                />
                <ReportSummaryModal
                    :show="showReportModal"
                    :client="selectedClient"
                    @close="showReportModal = false"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
