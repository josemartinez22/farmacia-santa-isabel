<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import SearchInput from '@/Components/SearchInput.vue';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    purchases: {
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
        name: 'PROVEEDOR',
    },
    {
        name: 'PRODUCTO',
    },
    {
        name: 'REGISTRO',
    },
    {
        name: 'CANTIDAD',
    },
    {
        name: 'TOTAL',
    },
    {
        name: 'FECHA',
    }
]);

const searchFilter = reactive({
    search: props.filters.search
});

watch(searchFilter, value => {
    router.get('/purchases', {
        search: value.search
    }, {
        preserveState: true,
        deep: true
    })
})

const reset = () => {
    router.get('/purchases');
};

</script>

<template>
    <Head title="Compras | Mostrar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/purchases">Compras</Link>
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
                            :permission="'create-purchase'" 
                            :labelValue="'Realizar una compra'" 
                            :urlValue="route('purchase.create')"
                        />
                        <Table :header="tableHeader" :items="purchases.length">
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                    v-if="purchases.data.length" v-for="purchase in purchases.data" :key="purchase.id">
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ purchase.supplier }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ purchase.product }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ purchase.user }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ purchase.quantity }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        ${{ purchase.total }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ new Date(purchase.created_at).toLocaleString('es-SV') }}
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td :colspan="tableHeader.length"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <div class="flex justify-center">
                                            <div
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <p class="text-center ">No se han encontrado compras.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </Table>
                        <Pagination :links="purchases"/>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>