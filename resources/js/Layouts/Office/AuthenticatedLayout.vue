<script setup>
import DesktopLayout from '@/Components/Office/DesktopLayout.vue';
import MobileLayout from '@/Components/Office/MobileLayout.vue';
</script>

<template>
    <!-- Mobile Layout -->
    <div class="lg:hidden">
        <MobileLayout>
            <template #header v-if="$slots.header">
                <slot name="header" />
            </template>
            <slot />
        </MobileLayout>
    </div>
    <!-- Desktop Layout -->
    <div class="hidden lg:block">
        <DesktopLayout>
            <template #header v-if="$slots.header">
                <slot name="header" />
            </template>
            <slot />
        </DesktopLayout>
    </div>
</template>
