<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    image: null,
    status: 1,
    rol_name: 'Ventas',
});

const submit = () => {
    form.post(route('user.create'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

</script>

<template>
    <Head title="Usuarios | Crear" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Usuarios</Link>
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

                                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name"
                                    required autofocus autocomplete="first_name" />

                                <InputError class="mt-2" :message="form.errors.first_name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="Apellido" value="Apellido" />

                                <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name"
                                    required autocomplete="last_name" />

                                <InputError class="mt-2" :message="form.errors.last_name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Correo" />

                                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                                    autocomplete="username" />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password" value="Contraseña" />

                                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                                    required autocomplete="new-password" />

                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password_confirmation" value="Confirmar contraseña" />

                                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                    v-model="form.password_confirmation" required autocomplete="new-password" />

                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="rol_name" value="Rol del usuario" />

                                <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                    v-model="form.rol_name">
                                    <option value="Administrador">Administrador</option>
                                    <option value="Encargado">Encargado</option>
                                    <option value="Ventas">Ventas</option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.rol_name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="image" value="Foto de perfil"/>

                                <input  type="file" @input="form.image = $event.target.files[0]" name="small-file-input" id="small-file-input" class="block mt-1 w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400
                                    file:bg-transparent file:border-0
                                    file:bg-gray-100 file:mr-4
                                    file:py-2 file:px-4
                                    dark:file:bg-gray-700 dark:file:text-gray-400" accept="image/*" >

                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="flex items-center justify-end mt-4">

                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Crear usuario
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>