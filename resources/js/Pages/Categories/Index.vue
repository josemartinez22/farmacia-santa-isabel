<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import SearchInput from '@/Components/SearchInput.vue';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    categories: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true
    },
});

const tableHeader = reactive([
    {
        name: 'ID',
    },
    {
        name: 'NOMBRE',
    },
    {
        name: 'DESCRIPCIÓN',
    },
    {
        name: 'ACCIONES',
    }
]);

const searchFilter = reactive({
    search: props.filters.search
});

watch(searchFilter, value => {
    router.get('/categories', {
        search: value.search
    }, {
        preserveState: true,
        deep: true
    })
})

const reset = () => {
    router.get('/categories');
};

</script>

<template>
    <Head title="Categorías | Mostrar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/categories">Categorías</Link>
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
                            :permission="'create-category'"
                            :labelValue="'Crear nueva Categoría'" 
                            :urlValue="route('category.create')"
                        />
                        <Table :header="tableHeader" :items="categories.length">
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                    v-if="categories.data.length" v-for="category in categories.data" :key="category.id">
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ category.id }}
                                    </td>
                                    <td scope="row" class="flex items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap text-justify max-w-md dark:text-white">
                                        <img v-if="category.image" :src="category.image" class="w-10 h-10 rounded-full">
                                        <img v-else src="img/defaultimg.png" class="w-10 h-10 rounded-full">
                                        <div class="pl-3 text-ellipsis overflow-hidden">
                                            <div class="text-base font-semibold">
                                                <p class="text-ellipsis overflow-hidden">{{ category.name }}</p>
                                            </div>
                                        </div>  
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-justify max-w-md dark:text-white">
                                        <p class="text-ellipsis overflow-hidden">{{ category.description }}</p>
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <Link v-if="can('update-category')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" :href="`/categories/${category.id}/edit`">Editar</Link>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td :colspan="tableHeader.length"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <div class="flex justify-center">
                                            <div
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <p class="text-center ">No se han encontrado Categorías.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </Table>
                        <Pagination :links="categories"/>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>