<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    phone: ''
});

const submit = () => {
    form.post(route('supplier.create'), {
        onSuccess: () => form.reset('name', 'phone'),
    });
};

</script>

<template>
    <Head title="Proveedor | Crear" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/suppliers">Proveedores</Link>
                    <span class="text-indigo-400 font-medium"> /</span>
                    Crear
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="Nombre" value="Nombre" />

                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                                    required autofocus autocomplete="name" />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="Teléfono" value="Teléfono" />

                                <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone"
                                    required autocomplete="phone" pattern="\d{4}-\d{4}" />

                                <p class="mt-1 text-gray-500 whitespace-pre-line dark:text-gray-400">Formato, Ej. 7293-3210</p>

                                <InputError class="mt-2" :message="form.errors.phone" />
                            </div>

                            <div class="flex items-center justify-end mt-4">

                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Crear proveedor
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>