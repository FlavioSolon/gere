<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'success', // success, warning, danger
    },
    text: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <span :class="[
        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-sm animate__animated animate__fadeIn',
        {
            'bg-success/20 text-success dark:bg-success/30 dark:text-success': type === 'success',
            'bg-warning/20 text-warning dark:bg-warning/30 dark:text-warning': type === 'warning',
            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': type === 'danger',
        }
    ]">
        {{ text }}
    </span>
</template>
