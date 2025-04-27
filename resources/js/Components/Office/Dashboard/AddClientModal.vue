<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Modal from '../Common/Modal.vue';
import ActionButton from '../Common/ActionButton.vue';
import { XMarkIcon, PlusIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

const form = ref({
    cnpj: '',
    razao_social: '',
});
const errors = ref({});
const submitting = ref(false);

function addClient() {
    submitting.value = true;
    router.post(route('office.clients.store'), form.value, {
        preserveState: true,
        onSuccess: () => {
            form.value = { cnpj: '', razao_social: '' };
            errors.value = {};
            submitting.value = false;
            emit('close');
        },
        onError: (err) => {
            errors.value = err;
            submitting.value = false;
        },
    });
}
</script>

<template>
    <Modal :show="show" title="Adicionar Novo Cliente" @close="$emit('close')" class="animate__animated animate__fadeIn">
        <form @submit.prevent="addClient" class="space-y-6">
            <div>
                <label for="cnpj" class="block text-sm font-medium text-gray-900 dark:text-gray-200">CNPJ</label>
                <input
                    id="cnpj"
                    v-model="form.cnpj"
                    v-mask="'##.###.###/####-##'"
                    type="text"
                    class="mt-1 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary transition-all duration-300"
                    placeholder="00.000.000/0000-00"
                    aria-describedby="cnpj-error"
                />
                <p v-if="errors.cnpj" id="cnpj-error" class="mt-1 text-sm text-red-600">{{ errors.cnpj }}</p>
            </div>
            <div>
                <label for="razao_social" class="block text-sm font-medium text-gray-900 dark:text-gray-200">Razão Social</label>
                <input
                    id="razao_social"
                    v-model="form.razao_social"
                    type="text"
                    class="mt-1 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary transition-all duration-300"
                    placeholder="Nome da Empresa"
                    aria-describedby="razao_social-error"
                />
                <p v-if="errors.razao_social" id="razao_social-error" class="mt-1 text-sm text-red-600">{{ errors.razao_social }}</p>
            </div>
            <div class="flex justify-end space-x-3">
                <ActionButton
                    type="secondary"
                    :icon="XMarkIcon"
                    @click="$emit('close')"
                    :disabled="submitting"
                >
                    Cancelar
                </ActionButton>
                <ActionButton
                    type="primary"
                    :icon="PlusIcon"
                    :loading="submitting"
                    :disabled="submitting"
                    @click="addClient"
                >
                    {{ submitting ? 'Adicionando...' : 'Adicionar Cliente' }}
                </ActionButton>
            </div>
        </form>
    </Modal>
</template>
