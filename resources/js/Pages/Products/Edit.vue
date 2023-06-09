<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import FileImgInput from '@/Components/FileImgInput.vue';
import Select from '@/Components/Select.vue';
import { reactive, watch, ref } from 'vue';
import VueMultiselect from 'vue-multiselect'

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    categories: {
        type: Object,
        required: true,
    },
    selected: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    _method: 'put',
    id: props.product.id,
    name: props.product.name,
    barcode: props.product.barcode,
    cost: props.product.cost,
    price: props.product.price,
    stock: props.product.stock,
    alert: props.product.alert,
    status: props.product.status,
    image: null,
    category_id: props.product.category_id,
})

const updateProduct = () => {
    form.post(route('product.update', props.product.id), {
        preserveScroll: true
    });
};

const productStatus = [
    { text: 'Activar', value: 1 },
    { text: 'Desactivar', value: 0 }
]

const onSearchCategoryChange = (value) => {
    searchFilter.search = value;
};

const searchFilter = reactive({
    search: null,
});

watch(searchFilter, value => {
    router.get(`/products/${props.product.id}/edit`, {
        search: value.search
    }, {
        replace: false,
        preserveState: true,
        preserveScroll: true,
    })
})

const selected = ref(props.selected);

const onSelectedCategory = (selected) => {
    form.category_id = selected.id;
}
</script>


<template>
    <Head title="Producto | Actualizar" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/products">Productos</Link>
                    <span class="text-indigo-400 font-medium"> /</span>
                    {{ form.name }}
                </h1>
                <img v-if="product.image" class="block ml-4 w-8 h-8 rounded-full" :src="product.image" />
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="updateProduct">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="mt-4">
                                    <InputLabel for="Nombre" value="Nombre" />

                                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                                        required autofocus autocomplete="name" />

                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="barcode" value="Código de barra" />

                                    <TextInput id="barcode" type="text" class="mt-1 block w-full" v-model="form.barcode"
                                        autocomplete="barcode" />

                                    <InputError class="mt-2" :message="form.errors.barcode" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="cost" value="Precio de compra" />

                                    <TextInput id="cost" type="number" step="0.01" class="mt-1 block w-full" v-model="form.cost"
                                        required autocomplete="cost" />

                                    <InputError class="mt-2" :message="form.errors.cost" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="category_id" value="Categoría del producto" class="mb-2" />
                                
                                    <VueMultiselect 
                                        v-model="selected" id="category_id" 
                                        placeholder="Buscar Categoría" :options="categories.length ? categories : []"
                                        label="name" track-by="id" 
                                        @search-change="onSearchCategoryChange" 
                                        @select="onSelectedCategory" 
                                    />
                                    
                                    <InputError class="mt-2" :message="form.errors.category_id" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="price" value="Precio de venta" />

                                    <TextInput id="price" type="number" step="0.01" class="mt-1 block w-full" v-model="form.price"
                                        required autocomplete="price" />

                                    <InputError class="mt-2" :message="form.errors.price" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="stock" value="Existencia en inventario" />

                                    <TextInput id="stock" type="number" class="mt-1 block w-full" v-model="form.stock"
                                        required autocomplete="stock" readonly />

                                    <InputError class="mt-2" :message="form.errors.stock" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="alert" value="Alerta de inventario" />

                                    <TextInput id="alert" type="number" class="mt-1 block w-full" v-model="form.alert"
                                        required autocomplete="alert" />

                                    <InputError class="mt-2" :message="form.errors.alert" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="status" value="Estado del producto" />
                                    
                                    <Select required v-model="form.status" :options="productStatus"/>

                                    <InputError class="mt-2" :message="form.errors.status" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="image" value="Imagen del producto"/>

                                    <FileImgInput v-model="form.image"/>

                                    <InputError class="mt-2" :message="form.errors.image" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Actualizar producto
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>