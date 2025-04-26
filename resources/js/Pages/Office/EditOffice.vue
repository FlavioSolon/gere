<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/Office/AuthenticatedLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    office: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    cnpj: props.office.cnpj ? props.office.cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5') : '',
    razao_social: props.office.razao_social || '',
    certificate: null,
    certificate_password: props.office.certificate_password || '',
});

const submitting = ref(false);
const showPassword = ref(false);

function submit() {
    submitting.value = true;
    form.post(route('office.update'), {
        forceFormData: true,
        preserveState: true, // Preserva o estado para evitar reset
        onSuccess: () => {
            submitting.value = false;
            // Apenas resetar o campo de arquivo, manter a senha
            form.reset('certificate');
        },
        onError: () => {
            submitting.value = false;
        },
    });
}
</script>

<template>
    <Head title="Editar Escritório - GereHub" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Editar Escritório
            </h2>
        </template>
        <div class="bg-white dark:bg-dark text-dark dark:text-white font-sans min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <!-- Mensagem de Sucesso -->
                <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-md">
                    {{ $page.props.flash.success }}
                </div>

                <!-- Mensagem de Erro -->
                <div v-if="$page.props.flash.error" class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md">
                    {{ $page.props.flash.error }}
                </div>

                <!-- Formulário de Edição -->
                <section class="mb-12">
                    <div class="bg-white dark:bg-dark rounded-lg p-6 shadow-sm">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Razão Social -->
                            <div>
                                <label for="razao_social" class="block text-sm font-medium text-dark/80 dark:text-white/80">
                                    Razão Social
                                </label>
                                <input
                                    id="razao_social"
                                    v-model="form.razao_social"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border border-primary/20 dark:border-secondary/20 bg-white dark:bg-dark text-dark dark:text-white focus:ring-accent focus:border-accent"
                                    placeholder="Ex: Contabilidade Teste LTDA"
                                />
                                <p v-if="form.errors.razao_social" class="mt-1 text-sm text-red-600">{{ form.errors.razao_social }}</p>
                            </div>

                            <!-- CNPJ -->
                            <div>
                                <label for="cnpj" class="block text-sm font-medium text-dark/80 dark:text-white/80">
                                    CNPJ
                                </label>
                                <input
                                    id="cnpj"
                                    v-model="form.cnpj"
                                    v-mask="'##.###.###/####-##'"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border border-primary/20 dark:border-secondary/20 bg-white dark:bg-dark text-dark dark:text-white focus:ring-accent focus:border-accent"
                                    placeholder="00.000.000/0000-00"
                                />
                                <p v-if="form.errors.cnpj" class="mt-1 text-sm text-red-600">{{ form.errors.cnpj }}</p>
                            </div>

                            <!-- Certificado Digital -->
                            <div>
                                <label for="certificate" class="block text-sm font-medium text-dark/80 dark:text-white/80">
                                    Certificado Digital (.pfx ou .p12)
                                </label>
                                <input
                                    id="certificate"
                                    type="file"
                                    accept=".pfx,.p12"
                                    class="mt-1 block w-full text-dark dark:text-white"
                                    @change="form.certificate = $event.target.files[0]"
                                />
                                <p v-if="form.errors.certificate" class="mt-1 text-sm text-red-600">{{ form.errors.certificate }}</p>
                                <p v-if="office.certificate_path" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Certificado atual: {{ office.certificate_path.split('/').pop() }}
                                </p>
                            </div>

                            <!-- Senha do Certificado -->
                            <div class="relative">
                                <label for="certificate_password" class="block text-sm font-medium text-dark/80 dark:text-white/80">
                                    Senha do Certificado
                                </label>
                                <input
                                    id="certificate_password"
                                    v-model="form.certificate_password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="mt-1 block w-full rounded-md border border-primary/20 dark:border-secondary/20 bg-white dark:bg-dark text-dark dark:text-white focus:ring-accent focus:border-accent pr-10"
                                    placeholder="Digite a senha do certificado"
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 mt-6"
                                    @click="showPassword = !showPassword"
                                >
                                    <svg
                                        v-if="showPassword"
                                        class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                                <p v-if="form.errors.certificate_password" class="mt-1 text-sm text-red-600">{{ form.errors.certificate_password }}</p>
                            </div>

                            <!-- Botão de Envio -->
                            <div>
                                <button
                                    type="submit"
                                    :disabled="submitting || form.processing"
                                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-accent text-white rounded-md transition-colors disabled:opacity-50"
                                >
                                    <svg
                                        v-if="submitting || form.processing"
                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-16 0z"></path>
                                    </svg>
                                    {{ submitting || form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
