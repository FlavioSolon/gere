<script setup>
import { ref, watch } from 'vue';
import Modal from '../Common/Modal.vue';
import ActionButton from '../Common/ActionButton.vue';
import { XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline';

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

const emit = defineEmits(['close', 'update']);

const form = ref({
    cnpj: '',
    razao_social: '',
});

watch(
    () => props.client,
    (newClient) => {
        form.value = {
            cnpj: newClient?.cnpj || '',
            razao_social: newClient?.razao_social || '',
        };
    },
    { immediate: true }
);

const errors = ref({});
const submitting = ref(false);

function updateClient() {
    submitting.value = true;
    setTimeout(() => {
        errors.value = {};
        submitting.value = false;
        emit('update', form.value);
        emit('close');
    }, 500); // Reduzido para 500ms para simulação mais rápida
}
</script>

<template>
    <Modal :show="show" title="Editar Cliente" @close="$emit('close')" class="animate__animated animate__fadeIn">
        <form @submit.prevent="updateClient" class="space-y-6">
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
                    :icon="CheckIcon"
                    :loading="submitting"
                    :disabled="submitting"
                    @click="updateClient"
                >
                    {{ submitting ? 'Salvando...' : 'Salvar Alterações' }}
                </ActionButton>
            </div>
        </form>
    </Modal>
</template>
