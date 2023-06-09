<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import { reactive, watch, ref } from 'vue';
import VueMultiselect from 'vue-multiselect';
import FileImgInput from '@/Components/FileImgInput.vue';
import Select from '@/Components/Select.vue';

const props = defineProps({
    categories: {
        type: Object,
        required: true
    },
    products: {
        type: Object,
        required: true
    },
    suppliers: {
        type: Object,
        required: true
    }
});

const newSupplier = ref(false);

watch(newSupplier, () => {
    form.reset('supplierName', 'phone', 'supplier_id'),
        selectedSupplier.value = null;
    form.checkSupplier = newSupplier;
})

const newProduct = ref(false);

watch(newProduct, () => {
    form.reset('id', 'name', 'barcode', 'cost', 'price', 'stock', 'alert', 'status', 'image', 'category_id', 'product_id'),
        selectedProduct.value = null;
    selectedCategory.value = null;
    form.checkProduct = newProduct;
})

const form = useForm({
    checkProduct: false,
    checkSupplier: false,
    supplierName: '',
    phone: '',
    product_id: '',
    supplier_id: '',
    name: '',
    barcode: '',
    cost: '',
    price: '',
    stock: '',
    alert: '',
    status: 1,
    image: null,
    category_id: null,
    quantity: '',
    total: '',
    selectedCategory: '',
    selectedProduct: '',
    selectedSupplier: '',
});

watch(
    () => form.stock,
    (stock) => {
        if (form.checkProduct) {
            form.quantity = stock
        }
    }
)

watch(
    () => form.quantity,
    (quantity) => {
        if (form.checkProduct) {
            form.stock = quantity
        }
    }
)

// Supplier
const searchSupplier = reactive({
    searchSuppliers: null,
});

watch(searchSupplier, value => {
    router.get('/purchases/create', {
        searchSuppliers: value.searchSuppliers
    }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    })
})

const onSearchSupplierChange = (value) => {
    searchSupplier.searchSuppliers = value;
};

const selectedSupplier = ref();

const onSelectedSupplier = (selectedSupplier) => {
    form.supplier_id = selectedSupplier.id;
    form.selectedSupplier = selectedSupplier.id
}

watch(selectedSupplier, (setSupplier) => {
    if (setSupplier == null || setSupplier == undefined) {
        form.supplier_id = null;
        form.selectedSupplier = null;
    }
})
// end Supplier


// Product
const searchProduct = reactive({
    searchProducts: null,
});

watch(searchProduct, value => {
    router.get('/purchases/create', {
        searchProducts: value.searchProducts
    }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    })
})

const onSearchProductChange = (value) => {
    searchProduct.searchProducts = value;
};

const selectedProduct = ref();

watch(selectedProduct, (setProduct) => {
    if (setProduct != null || setProduct != undefined) {
        form.id = setProduct.id,
            form.name = setProduct.name,
            form.barcode = setProduct.barcode,
            form.cost = setProduct.cost,
            form.price = setProduct.price,
            form.stock = setProduct.stock,
            form.alert = setProduct.alert,
            form.status = setProduct.status,
            form.category_id = setProduct.category_id
        form.selectedCategory = setProduct.category_id
        selectedCategory.value = {
            id: setProduct.categoryId,
            name: setProduct.categoryName,
        }
    } else {
        form.reset('id', 'name', 'barcode', 'cost', 'price', 'stock', 'alert', 'status', 'image', 'category_id', 'product_id'),
            selectedCategory.value = null;
        form.selectedCategory = null;
        form.selectedProduct = null;
    }
})


const onSelectedProduct = (selectedProduct) => {
    form.product_id = selectedProduct.id;
    form.selectedProduct = selectedProduct.id
}

const productStatus = [
    { text: 'Activar', value: 1 },
    { text: 'Desactivar', value: 0 }
]
// end product


// Category
const searchCategory = reactive({
    searchCategories: null,
});

watch(searchCategory, value => {
    router.get('/purchases/create', {
        searchCategories: value.searchCategories
    }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    })
})

const onSearchCategoryChange = (value) => {
    searchCategory.searchCategories = value;
};

const selectedCategory = ref();

const onSelectedCategory = (selectedCategory) => {
    form.category_id = selectedCategory.id;
    form.selectedCategory = selectedCategory.id
}

watch(selectedCategory, (setCategory) => {
    if (setCategory == null || setCategory == undefined) {
        form.category_id = null;
        form.selectedCategory = null;
    }
})
// end Category

const updateTotal = (e) => {
    let inputTotal;
    if (form.cost != '') {
        inputTotal = (Math.round((form.cost * form.quantity) * 100) / 100).toFixed(2)
        form.total = inputTotal;
    }
}

const updateCost = (e) => {
    let inputCost;
    if (form.quantity != '') {
        inputCost = (Math.round((form.cost * form.quantity) * 100) / 100).toFixed(2)
        form.total = inputCost;
    }
}

const submit = () => {
    form.post(route('purchase.create'), {
        //onSuccess: () => form.reset('name', 'phone', 'cost', 'price', 'stock', 'alert', 'status', 'image', 'category_id'),
    });
};

const nameWithBarcode = ({ name, barcode }) => {
    return `${name} — [${barcode}]`
}

</script>

<template>
    <Head title="Compra | Crear" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/purchases">Compras</Link>
                    <span class="text-indigo-400 font-medium"> /</span>
                    Crear
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-2 gap-4 text-center">

                                <div class="block mt-4">
                                    <label class="items-center">
                                        <Checkbox name="newSupplier" v-model:checked="newSupplier" />
                                        <span class="ml-2 text-sm text-gray-600">Agregar un proveedor nuevo</span>
                                    </label>
                                </div>

                                <div class="block mt-4">
                                    <label class="items-center">
                                        <Checkbox name="newProduct" v-model:checked="newProduct" />
                                        <span class="ml-2 text-sm text-gray-600">Agregar un producto nuevo</span>
                                    </label>
                                </div>
                            </div>

                            <div v-if="newSupplier">
                                <h2 class="font-semibold mt-4 text-xl text-gray-800 leading-tight">Agregar nuevo proveedor
                                </h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="mt-4">
                                        <InputLabel for="Nombre" value="Nombre" />

                                        <TextInput id="supplierName" type="text" class="mt-1 block w-full"
                                            v-model="form.supplierName" required autofocus autocomplete="supplierName" />

                                        <InputError class="mt-2" :message="form.errors.supplierName" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="Teléfono" value="Teléfono" />

                                        <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone"
                                            required autocomplete="phone" pattern="\d{4}-\d{4}" />

                                        <p class="mt-1 text-gray-500 whitespace-pre-line dark:text-gray-400">Formato, Ej.
                                            7293-3210</p>

                                        <InputError class="mt-2" :message="form.errors.phone" />
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="mt-4">
                                    <InputLabel for="supplier_id" value="Seleccinar proveedor" class="mb-2" />

                                    <VueMultiselect required v-model="selectedSupplier" id="supplier_id"
                                        placeholder="Buscar proveedor" :options="suppliers.length ? suppliers : []"
                                        label="name" track-by="id" @search-change="onSearchSupplierChange"
                                        @select="onSelectedSupplier" />

                                    <InputError class="mt-2" :message="form.errors.selectedSupplier" />
                                </div>
                            </div>

                            <div v-if="!newProduct">
                                <div class="mt-4">
                                    <InputLabel for="product_id" value="Seleccinar producto" class="mb-2" />

                                    <VueMultiselect required v-model="selectedProduct" id="product_id"
                                        placeholder="Buscar producto" :options="products.length ? products : []"
                                        :custom-label="nameWithBarcode" track-by="id" @search-change="onSearchProductChange"
                                        @select="onSelectedProduct" />

                                    <InputError class="mt-2" :message="form.errors.selectedProduct" />
                                </div>
                            </div>
                            <div v-if="newProduct || selectedProduct != null">
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

                                        <TextInput id="cost" @keyup="updateCost($event)" type="number" step="0.01"
                                            class="mt-1 block w-full" v-model="form.cost" required autocomplete="cost" />

                                        <InputError class="mt-2" :message="form.errors.cost" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="category_id" value="Categoría del producto" class="mb-2" />

                                        <VueMultiselect v-model="selectedCategory" id="category_id"
                                            placeholder="Buscar Categoría" :options="categories.length ? categories : []"
                                            label="name" track-by="id" @search-change="onSearchCategoryChange"
                                            @select="onSelectedCategory" />

                                        <InputError class="mt-2" :message="form.errors.selectedCategory" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="price" value="Precio de venta" />

                                        <TextInput id="price" type="number" step="0.01" class="mt-1 block w-full"
                                            v-model="form.price" required autocomplete="price" />

                                        <InputError class="mt-2" :message="form.errors.price" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="stock" value="Existencia en inventario" />

                                        <TextInput v-if="newProduct" id="stock" type="number" step="1"
                                            class="mt-1 block w-full" v-model="form.stock" required autocomplete="stock" />
                                        <TextInput v-else id="stock" type="number" step="1" class="mt-1 block w-full"
                                            v-model="form.stock" required autocomplete="stock" readonly />


                                        <InputError class="mt-2" :message="form.errors.stock" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="alert" value="Alerta de inventario" />

                                        <TextInput id="alert" type="number" step="1" class="mt-1 block w-full"
                                            v-model="form.alert" required autocomplete="alert" />

                                        <InputError class="mt-2" :message="form.errors.alert" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="status" value="Estado del producto" />

                                        <Select required v-model="form.status" :options="productStatus" />

                                        <InputError class="mt-2" :message="form.errors.status" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="image" value="Imagen del producto" />

                                        <FileImgInput v-model="form.image" />

                                        <InputError class="mt-2" :message="form.errors.image" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="mt-4">
                                    <InputLabel for="quantity" value="Cantidad de productos" />

                                    <TextInput id="quantity" @keyup="updateTotal($event)" type="number" step="1"
                                        class="mt-1 block w-full" v-model="form.quantity" required
                                        autocomplete="quantity" />

                                    <InputError class="mt-2" :message="form.errors.quantity" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="total" value="Total de la compra" />

                                    <TextInput id="total" type="number" step="0.01" class="mt-1 block w-full"
                                        v-model="form.total" required autocomplete="total" readonly />

                                    <InputError class="mt-2" :message="form.errors.total" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Registrar compra
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>