<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Table from '@/Components/Table.vue';
import { GChart } from "vue-google-charts";

const props = defineProps({
    byCategories: {
        type: Object,
        required: true
    },
    stockProducts: {
        type: Object,
        required: true
    },
    topProducts: {
        type: Object,
        required: true
    }
});

if (route().has('dashboard')) {
    if (!localStorage.justOnce) {
        localStorage.setItem("justOnce", "true");
        window.location.reload();
    }
}

const tableHeader = reactive([
    {
        name: 'PRODUCTO',
    },
    {
        name: 'PRECIO',
    },
    {
        name: 'DESCUENTO',
    },
    {
        name: 'VENDIDOS',
    }
]);

const chartData = reactive(props.byCategories);

const chartOptions = {
    title: "Ventas por Categorías",
    pieHole: 0.4,
    width: 450,
    height: 400
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/dashboard">Dashboard</Link>
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2">

                        <div v-if="props.stockProducts.length" v-for="product in stockProducts" :key="product.id">
                            <div v-if="product.stock == 0"
                                class="flex p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
                                role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Info</span>
                                <div>
                                    <span class="font-black">{{ product.name }}</span>
                                    <br>
                                    <span class="font-medium">Sin existencias!</span>
                                    <p style="color: gray;">Sin existencias | <strong>total: {{ product.stock }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div v-else
                                class="flex p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800"
                                role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Info</span>
                                <div>
                                    <span class="font-black">{{ product.name }}</span>
                                    <br>
                                    <span class="font-medium">Pocas existencias!</span>
                                    <p style="color: gray;">Se cuenta con pocas existencias | <strong>total: {{
                                        product.stock }}</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="container grid m-auto sm:grid-cols-12 md:grid-cols-8 lg:grid-cols-12 gap-1">
                            <div v-if="props.byCategories.length"
                                class="bg-white overflow-hidden shadow-xl sm:rounded-lg col-span-4">
                                <h1 class="mb-4 font-semibold text-center text-xl text-gray-800 leading-tight">
                                    Top 3 ventas por Categorías
                                </h1>
                                <GChart type="PieChart" :options="chartOptions" :data="chartData" />
                            </div>
                            <div v-if="props.topProducts.length"
                                class="justify-center bg-white overflow-hidden shadow-xl sm:rounded-lg md:col-span-8">
                                <h1 class="mb-4 font-semibold text-center text-xl text-gray-800 leading-tight">
                                    Top 10 productos más vendidos
                                </h1>
                                <Table :header="tableHeader">
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                            v-if="topProducts.length > 0" v-for="item in topProducts" :key="item.id">
                                            <td scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ item.name }}
                                            </td>
                                            <td scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                ${{ item.price }}
                                            </td>
                                            <td scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ item.discount }}%
                                            </td>
                                            <td scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ item.sold }}
                                            </td>
                                        </tr>
                                        <tr v-else>
                                            <td :colspan="tableHeader.length"
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                                <div class="flex justify-center">
                                                    <div
                                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <p class="text-center ">No se han registrados ventas para generar la
                                                            gráfica.
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </Table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

<!--         <footer class="fixed bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 Sistema elaborado por estudiantes de la Universidad Luteran Salvadoreña.
            </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <p class="mr-4 hover:underline md:mr-6">Arevalo Viera, Eduardo Alexander - AV01135084</p>
                </li>
                <li>
                    <p class="mr-4 hover:underline md:mr-6">Funes Ventura, Iván Steven - FV01145561</p>
                </li>
                <li>
                    <p class="mr-4 hover:underline md:mr-6">Hernandez Sánchez, Margarita del Carmen	- HS01135129</p>
                </li>
                <li>
                    <p class="mr-4 hover:underline md:mr-6">Ramirez Rivera, José Antonio - RR01135358</p>
                </li>
                <li>
                    <p class="mr-4 hover:underline md:mr-6">Sánchez Palacios, Williams Ernesto - SP01132338</p>
                </li>
            </ul>
        </footer> -->

        <footer
            class="bg-white text-center shadow-inner dark:bg-gray-800 lg:text-left">
            <div class="container p-6 text-white-800 dark:bg-gray-800">
                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="mb-6 text-center md:mb-0">
                        <h5 class="mb-2 text-gray-900 dark:text-gray-200 font-medium uppercase">Universidad Luterana Salvadoreña</h5>

                        <p class="mb-1 text-gray-900 dark:text-gray-200">
                            Facultad de Ciencias del Hombre y la Naturaleza
                        </p>
                        <p class="mb-1 text-gray-900 dark:text-gray-200">
                            Licenciatura en Ciencias de la Computación
                        </p>
                    </div>

                    <div class="mb-6 md:mb-0">
                        <ul class="flex flex-wrap items-center mt-3 text-md font-medium text-gray-900 dark:text-gray-200 sm:mt-0">
                            <li>
                                <p class="mr-4 hover:underline md:mr-6">Arevalo Viera, Eduardo Alexander - AV01135084</p>
                            </li>
                            <li>
                                <p class="mr-4 hover:underline md:mr-6">Funes Ventura, Iván Steven - FV01145561</p>
                            </li>
                            <li>
                                <p class="mr-4 hover:underline md:mr-6">Hernandez Sánchez, Margarita del Carmen	- HS01135129</p>
                            </li>
                            <li>
                                <p class="mr-4 hover:underline md:mr-6">Ramirez Rivera, José Antonio - RR01135358</p>
                            </li>
                            <li>
                                <p class="mr-4 hover:underline md:mr-6">Sánchez Palacios, Williams Ernesto - SP01132338</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-4 text-center text-gray-700 dark:bg-gray-800 dark:text-neutral-200">
                © 2023 Sistema elaborado por estudiantes de la Universidad Luteran Salvadoreña.
            </div>
        </footer>

    </AuthenticatedLayout>
</template>
