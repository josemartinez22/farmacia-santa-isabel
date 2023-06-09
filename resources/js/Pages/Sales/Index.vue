<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { reactive, watch, ref } from 'vue';
import SearchInput from '@/Components/SearchInput.vue';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    sales: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true
    }
});

const tableHeader = reactive([
    {
        name: 'ID',
    },
    {
        name: 'REGISTRÓ',
    },
    {
        name: 'TOTAL DE VENTA',
    },
    {
        name: 'CANTIDAD',
    },
    {
        name: 'FECHA',
    },
    {
        name: 'ACCIONES',
    }
]);

const searchFilter = reactive({
    search: props.filters.search
});

watch(searchFilter, value => {
    router.get('/sales', {
        search: value.search
    }, {
        preserveState: true,
        deep: true
    })
})

const reset = () => {
    router.get('/sales');
};

</script>

<template>
    <Head title="Ventas | Mostrar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/sales">Ventas</Link>
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <SearchInput
                            v-model="searchFilter.search" 
                            @reset="reset" 
                            :permission="'view-cart'"
                            :labelValue="'Realizar una venta'" 
                            :urlValue="route('cart')"
                        />
                        <Table :header="tableHeader" :items="sales.length">
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                    v-if="sales.data.length" v-for="sale in sales.data" :key="sale.id">
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ sale.id }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ sale.user }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        ${{ sale.total }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ sale.items }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ new Date(sale.created_at).toLocaleString('es-SV') }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <Link
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                            :href="`/sales/${sale.id}`"
                                            :only="['modal']"
                                            preserve-state
                                            preserve-scroll>
                                            Ver detalles
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td :colspan="tableHeader.length"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <div class="flex justify-center">
                                            <div
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <p class="text-center ">No se han encontrado ventas.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </Table>
                        <Pagination :links="sales"/>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>