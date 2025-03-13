<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const { props } = usePage();
const user = props.auth.user;
const isAdmin = user && user.role === 'admin';

const isDarkMode = ref(false);

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        isDarkMode.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDarkMode.value = false;
        document.documentElement.classList.remove('dark');
    }
});

const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};
</script>

<template>
    <div :class="{ 'bg-gray-100': !isDarkMode, 'bg-gray-900 dark': isDarkMode }" class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-16 bg-white dark:bg-gray-800 shadow-lg">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center p-4">
                    <Link :href="isAdmin ? route('admin.index') : route('dashboard')">
                        <ApplicationLogo class="h-10 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </Link>
                </div>

                <!-- User Dropdown -->
                <div class="p-4">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <button
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </button>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 space-y-6 p-4">
                    <div class="relative group">
                        <Link
                            :href="isAdmin ? route('admin.index') : route('dashboard')"
                            class="flex items-center text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 transition-transform duration-200 group-hover:scale-110"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span
                                class="absolute left-16 opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 px-2 py-1 rounded shadow-md"
                            >
                                Dashboard
                            </span>
                        </Link>
                    </div>
                </nav>

                <!-- Theme Toggle -->
                <div class="p-4">
                    <button
                        @click="toggleTheme"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors group"
                    >
                        <svg
                            :class="{ 'h-6 w-6': true, 'text-yellow-500': isDarkMode, 'text-gray-500': !isDarkMode, 'group-hover:scale-110': true }"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path v-if="isDarkMode" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                            <path v-else d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-16">
            <header v-if="$slots.header" class="bg-white shadow dark:bg-gray-800">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            <main class="flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Ajuste para o texto ao lado do ícone */
.absolute {
    top: 50%;
    transform: translateY(-50%);
}
</style>
