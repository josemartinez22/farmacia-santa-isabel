<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FileImgInput from '@/Components/FileImgInput.vue';

const form = useForm({
    name: '',
    description: '',
    image: null
});

const submit = () => {
    form.post(route('category.create'), {
        onSuccess: () => form.reset('name', 'description', 'image'),
    });
};

</script>

<template>
    <Head title="Categorías | Crear" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/categories">Categorías</Link>
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
                                <InputLabel for="Descripción" value="Descripción" />

                                <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description"
                                    required autocomplete="description" />

                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="image" value="Imagen Categoría"/>

                                <FileImgInput v-model="form.image"/>

                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="flex items-center justify-end mt-4">

                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Crear Categoríaa
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>