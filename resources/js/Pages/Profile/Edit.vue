<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Perfil" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/profile">Perfil</Link>
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div v-if="is('Administrador')" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Descargar copia de seguridad de la base de datos</h2>

                            <p class="mt-1 text-sm text-gray-600 text-justify">
                                A continuación se descargará una copia de seguridad de la base de datos, una vez se genere el archivo
                                se recomienda guardarlo en un lugar seguro y no compartirlo con nadie.
                            </p>
                        </header>
                    </section>
                    <form :action="route('download.backup')" method="get">
                        <div class="mt-4 flex items-center gap-4">
                            <PrimaryButton>Descargar</PrimaryButton>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
