<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    cnpj: '',
    razao_social: '',
    certificate: null,
    certificate_password: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admin.offices.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Criar Escritório" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl font-bold mb-4">Criar Novo Escritório</h2>
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="cnpj" value="CNPJ" />
                                    <TextInput id="cnpj" v-model="form.cnpj" type="text" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.cnpj" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="razao_social" value="Razão Social" />
                                    <TextInput id="razao_social" v-model="form.razao_social" type="text" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.razao_social" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="certificate" value="Certificado (.pfx)" />
                                    <input id="certificate" type="file" class="mt-1 block w-full" @change="form.certificate = $event.target.files[0]" required />
                                    <InputError :message="form.errors.certificate" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="certificate_password" value="Senha do Certificado" />
                                    <TextInput id="certificate_password" v-model="form.certificate_password" type="password" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.certificate_password" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="email" value="Email do Usuário" />
                                    <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.email" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="password" value="Senha" />
                                    <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.password" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="password_confirmation" value="Confirme a Senha" />
                                    <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.password_confirmation" class="mt-2" />
                                </div>
                            </div>
                            <div class="mt-6">
                                <PrimaryButton :disabled="form.processing">Criar Escritório</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
