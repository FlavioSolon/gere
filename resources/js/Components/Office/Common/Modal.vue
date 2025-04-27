<script setup>
import { ref, watch } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['close']);

const isOpen = ref(props.show);

watch(() => props.show, (newValue) => {
    isOpen.value = newValue;
});

function closeModal() {
    console.log('Close modal triggered:', new Date().toISOString()); // Log para depuração
    isOpen.value = false;
    emit('close');
}
</script>

<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4 animate__animated animate__fadeIn animate__faster"
    >
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg sm:max-w-md lg:max-w-xl p-6"
        >
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ title }}</h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                    aria-label="Fechar modal"
                >
                    <XMarkIcon class="w-6 h-6" />
                </button>
            </div>
            <slot />
        </div>
    </div>
</template>
