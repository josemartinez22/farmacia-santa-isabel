<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import SearchInput from '@/Components/SearchInput.vue';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    users: {
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
        name: 'APELLIDO',
    },
    {
        name: 'CORREO',
    },
    {
        name: 'ESTADO',
    },
    {
        name: 'ACCIONES',
    }
]);

const searchFilter = reactive({
    search: props.filters.search
});

watch(searchFilter, value => {
    router.get('/users', {
        search: value.search
    }, {
        preserveState: true,
        deep: true
    })
})

const reset = () => {
    router.get('/users');
};

</script>

<template>
    <Head title="Usuarios | Mostrar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Usuarios</Link>
                </h1>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <SearchInput v-model="searchFilter.search" 
                            @reset="reset" 
                            :permission="'create-user'" 
                            :labelValue="'Crear un nuevo usuario'"
                            :urlValue="route('user.create')" />
                        <Table :header="tableHeader" :items="users.length">
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                    v-if="users.data.length" v-for="user in users.data" :key="user.id">
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ user.id }}
                                    </td>
                                    <td scope="row"
                                        class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <img v-if="user.image" :src="user.image" class="w-10 h-10 rounded-full">
                                        <img v-else
                                            src="img/userimg.png"
                                            class="w-10 h-10 rounded-full">
                                        <div class="pl-3">
                                            <div class="text-base font-semibold">
                                                {{ user.first_name }}</div>
                                        </div>
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ user.last_name }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ user.email }}
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div v-if="user.status == 1">
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-green-200 px-2 py-1 text-xs font-semibold text-green-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                                Activo
                                            </span>
                                        </div>
                                        <div v-else>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-red-200 px-2 py-1 text-xs font-semibold text-red-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                                Desactivo
                                            </span>
                                        </div>
                                    </td>
                                    <td scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <Link v-if="can('update-user')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                            :href="`/users/${user.id}/edit`">Editar</Link>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td :colspan="tableHeader.length"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <div class="flex justify-center">
                                            <div
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <p class="text-center ">No se han encontrado usuarios.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </Table>
                        <Pagination :links="users" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>