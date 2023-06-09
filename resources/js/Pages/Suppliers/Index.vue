<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import SearchInput from '@/Components/SearchInput.vue';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    suppliers: {
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
        name: 'NOMBRE',
    },
    {
        name: 'TELÉFONO',
    },
    {
        name: 'ACCIONES',
    }
]);

const searchFilter = reactive({
    search: props.filters.search
});

watch(searchFilter, value => {
    router.get('/suppliers', {
        search: value.search
    }, {
        preserveState: true,
        deep: true
    })
})

const reset = () => {
    router.get('/suppliers');
};

</script>

<template>
    <Head title="Proveedores | Mostrar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/suppliers">Proveedores</Link>
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
                            :permission="'create-supplier'" 
                            :labelValue="'Crear un nuevo proveedor'" 
                            :urlValue="route('supplier.create')"
                        />
                        <Table :header="tableHeader" :items="suppliers.length">
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                    v-if="suppliers.data.length" v-for="supplier in suppliers.data" :key="supplier.id">
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ supplier.id }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ supplier.name }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ supplier.phone }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <Link v-if="can('update-supplier')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" :href="`/suppliers/${supplier.id}/edit`">Editar</Link>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td :colspan="tableHeader.length"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <div class="flex justify-center">
                                            <div
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <p class="text-center ">No se han encontrado proveedores.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </Table>
                        <Pagination :links="suppliers"/>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>