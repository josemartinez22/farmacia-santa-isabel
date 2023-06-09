<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { reactive, watch, ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import VueMultiselect from 'vue-multiselect';
import Select from '@/Components/Select.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    }
});

const form = useForm({
    reportType: 0,
    userId: 0,
    reportByDate: 0,
    reportFrom: '',
    reportTo: '',
});

const reportTypeOptions = [
    { text: 'Reporte de venta', value: 0 },
    { text: 'Reporte de compra', value: 1 }
]

const reportByDateOptions = [
    { text: 'Diario', value: 0 },
    { text: 'Entre fechas', value: 1 }
]

const searchUser = reactive({
    search: null,
});

watch(searchUser, value => {
    router.post('/reports', {
        search: value.search
    }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    })
})

const onSearchUserChange = (value) => {
    searchUser.search = value;
};

const userId = ref();

const onSelectedUser = (userId) => {
    form.userId = userId.id
}

watch(userId, (setUser) => {
    if (setUser == null || setUser == undefined) {
        form.userId = 0;
    }
})

const reportURL = reactive(form);


const reportPDF = () => {
    let url = window.location.href;

    if (reportURL.reportByDate > 0) {
        if (reportURL.reportFrom == '' || reportURL.reportTo == '') {
            alert('Debe seleccionar un rango de fechas');
            return;
        }
        window.open(url + `/pdf/${reportURL.userId}/${reportURL.reportType}/${reportURL.reportFrom}/${reportURL.reportTo}`, '_blank');
    } else {
        window.open(url + `/pdf/${reportURL.userId}/${reportURL.reportType}/`, '_blank');
    }
};

const reportExcel = () => {
    let url = window.location.href;

    if (reportURL.reportByDate > 0) {
        if (reportURL.reportFrom == '' || reportURL.reportTo == '') {
            alert('Debe seleccionar un rango de fechas');
            return;
        }
        window.open(url + `/excel/${reportURL.userId}/${reportURL.reportType}/${reportURL.reportFrom}/${reportURL.reportTo}`, '_blank');
    } else {
        window.open(url + `/excel/${reportURL.userId}/${reportURL.reportType}/`, '_blank');
    }
};

</script>

<template>
    <Head title="Reportes | Mostrar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/reports">Generar reportes</Link>
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-3"></div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg col-span-6">
                        <div class="p-6">
                            <h1 class="font-semibold text-center text-xl text-gray-800 leading-tight">
                                Generar Reportes
                            </h1>
                            <form @submit.prevent="onSubmit">
                                <div class="mt-4">
                                    <InputLabel for="reportType" value="Tipo de reporte:" />

                                    <Select autofocus v-model="form.reportType" :options="reportTypeOptions" />

                                    <InputError class="mt-2" :message="form.errors.reportType" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="userId" value="Seleccionar un usuario:" class="mb-2" />

                                    <VueMultiselect v-model="userId" id="userId"
                                        placeholder="Buscar Usuario" :options="users.length ? users : []"
                                        label="first_name" track-by="id" @search-change="onSearchUserChange"
                                        @select="onSelectedUser" />

                                    <p class="mt-1 text-gray-500 whitespace-pre-line text-justify dark:text-gray-400">
                                        Tip: Para generar un reporte general de todos los usuarios, no debe seleccionar uno.
                                    </p>
                                    
                                    <InputError class="mt-2" :message="form.errors.userId" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="reportByDate" value="Tipo por fechas:" />

                                    <Select v-model="form.reportByDate" :options="reportByDateOptions" />

                                    <InputError class="mt-2" :message="form.errors.reportByDate" />
                                </div>

                                <div v-if="form.reportByDate">
                                    <div class="mt-4">
                                        <InputLabel for="reportFrom" value="Fecha de inicio:" />

                                        <TextInput required id="reportFrom" type="date" class="mt-1 block w-full" v-model="form.reportFrom"/>

                                        <InputError class="mt-2" :message="form.errors.reportFrom" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="reportTo" value="Fecha de fin:" />

                                        <TextInput required id="reportTo" type="date" class="mt-1 block w-full" v-model="form.reportTo"/>

                                        <InputError class="mt-2" :message="form.errors.reportTo" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-center mt-4">
                                    <button class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                        :class="{ 'opacity-25': form.processing }" 
                                        @click="reportPDF" 
                                        :disabled="form.processing">
                                        Generar PDF
                                    </button>
                                </div>

                                <div class="flex items-center justify-center mt-4">
                                    <button class="ml-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                        :class="{ 'opacity-25': form.processing }" 
                                        @click="reportExcel" 
                                        :disabled="form.processing">
                                        Generar Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-span-3"></div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>