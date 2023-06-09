<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

if (route().has('password.request')) {
    if (localStorage.justOnce) {
        localStorage.removeItem('justOnce');
    }
}

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Has olvidado tu contraseña" />

        <div class="mb-4 text-sm text-gray-600 text-justify">
            <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-2">¿Olvidaste tu contraseña?</h2>
            <p>Ningún problema. Simplemente háganos saber su dirección de correo electrónico y le enviaremos un correo electrónico para restablecer la contraseña.
            enlace que le permitirá elegir uno nuevo.</p>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Restablecer contraseña
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
