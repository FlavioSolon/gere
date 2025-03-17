<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Bem-vindo ao GereHub" />
    <div class="bg-white dark:bg-dark text-dark/50 dark:text-white/50 font-amonos">
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-primary selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <img
                            src="/assets/logos/logo.png"
                            alt="Logo GereHub"
                            class="h-12 w-auto lg:h-16"
                        />
                    </div>
                </header>

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <!-- Card Principal -->
                        <div
                            id="docs-card"
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-primary/10 transition duration-300 hover:text-dark/70 hover:ring-primary/20 focus:outline-none focus-visible:ring-accent md:row-span-3 lg:p-10 lg:pb-10 dark:bg-dark dark:ring-secondary/10 dark:hover:text-white/70 dark:hover:ring-secondary/20 dark:focus-visible:ring-accent"
                        >
                            <div
                                id="screenshot-container"
                                class="relative flex w-full flex-1 items-stretch"
                            >
                                <img
                                    src="https://via.placeholder.com/600x300?text=GereHub+Preview"
                                    alt="Prévia do GereHub"
                                    class="aspect-video h-full w-full flex-1 rounded-[10px] object-cover object-top drop-shadow-[0px_4px_34px_rgba(0,0,0,0.06)] dark:hidden"
                                    @error="handleImageError"
                                />
                                <img
                                    src="https://via.placeholder.com/600x300?text=GereHub+Preview+Dark"
                                    alt="Prévia do GereHub"
                                    class="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-cover object-top drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block"
                                />
                                <div
                                    class="absolute -bottom-16 -left-16 h-40 w-[calc(100%+8rem)] bg-gradient-to-b from-transparent via-white to-white dark:via-dark dark:to-dark"
                                ></div>
                            </div>

                            <div
                                class="relative flex items-center gap-6 lg:items-end"
                            >
                                <div
                                    id="docs-card-content"
                                    class="flex items-start gap-6 lg:flex-col"
                                >
                                    <div
                                        class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary/10 sm:size-16"
                                    >
                                        <svg
                                            class="size-5 sm:size-6"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                fill="#0083ff"
                                                d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10zm-1-15h2v6h-2zm0 8h2v2h-2z"
                                            />
                                        </svg>
                                    </div>

                                    <div class="pt-3 sm:pt-5 lg:pt-0">
                                        <h2
                                            class="text-xl font-semibold text-dark dark:text-white"
                                        >
                                            GereHub - Em Breve!
                                        </h2>
                                        <p class="mt-4 text-sm/relaxed text-dark/80 dark:text-white/80">
                                            GereHub é uma solução simples e
                                            poderosa para escritórios de
                                            contabilidade. Gerencie os CNPJs dos
                                            seus clientes e gere relatórios
                                            fiscais diretamente pela API da
                                            Serpro, tudo em um só lugar.
                                            Lançamento em breve!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Secundário 1: O que é -->
                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-primary/10 transition duration-300 hover:text-dark/70 hover:ring-primary/20 focus:outline-none focus-visible:ring-accent lg:pb-10 dark:bg-dark dark:ring-secondary/10 dark:hover:text-white/70 dark:hover:ring-secondary/20 dark:focus-visible:ring-accent"
                        >
                            <div
                                class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary/10 sm:size-16"
                            >
                                <svg
                                    class="size-5 sm:size-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        fill="#0083ff"
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"
                                    />
                                </svg>
                            </div>
                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-dark dark:text-white"
                                >
                                    O que é o GereHub?
                                </h2>
                                <p class="mt-4 text-sm/relaxed text-dark/80 dark:text-white/80">
                                    Uma plataforma para escritórios de
                                    contabilidade cadastrarem CNPJs, fazerem
                                    upload de certificados digitais e gerarem
                                    relatórios fiscais com integração à API
                                    Integra Contador da Serpro.
                                </p>
                            </div>
                        </div>

                        <!-- Card Secundário 2: Benefícios -->
                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-primary/10 transition duration-300 hover:text-dark/70 hover:ring-primary/20 focus:outline-none focus-visible:ring-accent lg:pb-10 dark:bg-dark dark:ring-secondary/10 dark:hover:text-white/70 dark:hover:ring-secondary/20 dark:focus-visible:ring-accent"
                        >
                            <div
                                class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary/10 sm:size-16"
                            >
                                <svg
                                    class="size-5 sm:size-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        fill="#0083ff"
                                        d="M9 21h6v-2H9v2zm-6-4h18v-2H3v2zm2-4h14v-2H5v2zm4-4h6V7H9v2zm-6-4h18V3H3v2z"
                                    />
                                </svg>
                            </div>
                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-dark dark:text-white"
                                >
                                    Benefícios
                                </h2>
                                <p class="mt-4 text-sm/relaxed text-dark/80 dark:text-white/80">
                                    Gestão simplificada de clientes, relatórios
                                    fiscais em poucos cliques e um modelo de
                                    assinatura acessível: R$ 2 por CNPJ
                                    cadastrado. Fique pronto para o lançamento!
                                </p>
                            </div>
                        </div>

                        <!-- Card Secundário 3: Saiba Mais -->
                        <a
                            href="https://apicenter.estaleiro.serpro.gov.br/documentacao/api-integra-contador/pt/cenarios_trial/cenarios_sitfis/"
                            target="_blank"
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-primary/10 transition duration-300 hover:text-dark/70 hover:ring-primary/20 focus:outline-none focus-visible:ring-accent lg:pb-10 dark:bg-dark dark:ring-secondary/10 dark:hover:text-white/70 dark:hover:ring-secondary/20 dark:focus-visible:ring-accent"
                        >
                            <div
                                class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary/10 sm:size-16"
                            >
                                <svg
                                    class="size-5 sm:size-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        fill="#0083ff"
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                                    />
                                </svg>
                            </div>
                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-dark dark:text-white"
                                >
                                    Saiba Mais
                                </h2>
                                <p class="mt-4 text-sm/relaxed text-dark/80 dark:text-white/80">
                                    Confira a documentação da API Integra
                                    Contador da Serpro para entender como o
                                    GereHub vai simplificar sua rotina contábil.
                                </p>
                            </div>
                            <svg
                                class="size-6 shrink-0 self-center stroke-primary"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                />
                            </svg>
                        </a>
                    </div>
                </main>

                <footer
                    class="py-16 text-center text-sm text-dark/70 dark:text-white/70"
                >
                    GereHub - Powered by Zênite
                </footer>
            </div>
        </div>
    </div>
</template>
