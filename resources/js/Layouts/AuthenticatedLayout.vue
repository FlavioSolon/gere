<script setup>
import DesktopLayout from '@/Components/DesktopLayout.vue';
import MobileLayout from '@/Components/MobileLayout.vue';
</script>

<template>
    <!-- Mostra MobileLayout em telas menores que lg (1024px), DesktopLayout em telas maiores -->
    <div class="lg:hidden">
        <MobileLayout>
            <template #header v-if="$slots.header">
                <slot name="header" />
            </template>
            <slot />
        </MobileLayout>
    </div>
    <div class="hidden lg:block">
        <DesktopLayout>
            <template #header v-if="$slots.header">
                <slot name="header" />
            </template>
            <slot />
        </DesktopLayout>
    </div>
</template>
