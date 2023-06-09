<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    _method: 'put',
    id: props.user.id,
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    password: '',
    status: props.user.status,
    rol_name: props.user.rol_name,
    image: null,
})

const userStatus = [
    { text: 'Activar', value: 1 },
    { text: 'Desactivar', value: 0 }
]

const updateUser = () => {
    form.post(route('user.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => { form.reset('password', 'image') },
        onError: () => form.reset('password',)
    });
};

</script>

<template>
    <Head title="Usuarios | Editar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Usuarios</Link>
                    <span class="text-indigo-400 font-medium"> /</span>
                    {{ form.first_name }} {{ form.last_name }}
                </h1>
                <img v-if="user.image" class="block ml-4 w-8 h-8 rounded-full" :src="user.image" />
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="updateUser">
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

                                <TextInput id="password" type="password" class="mt-1 block w-full"
                                    v-model="form.password" />

                                <InputError class="mt-2" :message="form.errors.password" />
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

                            <div class="mt-4 flex items-center space-x-4">
                                <div class="flex-auto w-64">
                                    <InputLabel for="image" value="Foto de perfil" />

                                    <input type="file" @input="form.image = $event.target.files[0]" name="small-file-input"
                                        id="small-file-input"
                                        class="block mt-1 w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 file:bg-transparent file:border-0 file:bg-gray-100 file:py-2 file:px-4 dark:file:bg-gray-700 dark:file:text-gray-400"
                                        accept="image/*">

                                    <InputError class="mt-2" :message="form.errors.image" />
                                </div>

                                <div class="sm:col-span-6 flex-auto w-32">
                                    <InputLabel for="estado" value="Estado del usuario" />

                                    <Select v-model="form.status" :options="userStatus" />

                                    <InputError class="mt-2" :message="form.errors.status" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">

                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Actualizar usuario
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>