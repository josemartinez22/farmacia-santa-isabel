<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const manualAdmin = route().t.url + '/assets/pdf/ManualAdministrador.pdf';
const manualEncargado = route().t.url + '/assets/pdf/ManualEncargado.pdf';
const manualVentas = route().t.url + '/assets/pdf/ManualVentas.pdf';

</script>

<template>
    <Head title="Manual de Ayuda" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/manuals">Manual de ayuda</Link>
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2">

                        <div class="p-6">
                            <div v-if="is('Administrador')">
                                <div class="p-4">
                                <h1 class="font-semibold text-center text-xl text-gray-800 leading-tight"> Manual de ayuda
                                    Administrador</h1></div>
                                <object :data="manualAdmin" type="application/pdf" width="100%" height="500px">
                                    <p>No se puede mostrar el archivo PDF. <a class="font-bold text-blue-600 dark:text-blue-500 hover:underline"  :href="manualAdmin">Descargar</a></p>
                                </object>
                            </div>
                            <div v-if="is('Encargado')">
                                <div class="p-4">
                                    <h1 class="font-semibold text-center text-xl text-gray-800 leading-tight"> Manual de ayuda
                                    Encargado</h1>
                                </div>
                                <object :data="manualEncargado" type="application/pdf" width="100%" height="500px">
                                    <p>No se puede mostrar el archivo PDF. <a class="font-bold text-blue-600 dark:text-blue-500 hover:underline"  :href="manualEncargado">Descargar</a></p>
                                </object>
                            </div>
                            <div v-if="is('Ventas')">
                                <div class="p-4">
                                <h1 class="font-semibold text-center text-xl text-gray-800 leading-tight"> Manual de ayuda
                                    Ventas</h1></div>
                                <object :data="manualVentas" type="application/pdf" width="100%" height="500px">
                                    <p>No se puede mostrar el archivo PDF. <a class="font-bold text-blue-600 dark:text-blue-500 hover:underline" :href="manualVentas">Descargar</a></p>
                                </object>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>