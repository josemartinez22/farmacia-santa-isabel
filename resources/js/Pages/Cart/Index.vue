<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link, usePage } from '@inertiajs/vue3';
import { reactive, watch, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import Table from '@/Components/Table.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    cart: {
        type: Object,
        required: true
    },
    cart_id: {
        type: Number,
        required: true
    },
    total_items: {
        type: Number,
        required: true
    },
    total_price: {
        type: Number,
        required: true
    },
    filters: {
        type: Object,
        required: true
    }
});

const tableHeader = reactive([
    {
        name: 'DESCRIPCIÓN',
    },
    {
        name: 'PRECIO',
    },
    {
        name: 'CANTIDAD',
    },
    {
        name: 'DESCUENTO %',
    },
    {
        name: 'SUBTOTAL',
    },
    {
        name: 'ACCIONES',
    }
]);

const tableHeaderModal = reactive([
    {
        name: 'IMAGEN',
    },
    {
        name: 'NOMBRE',
    },
    {
        name: 'PRECIO',
    },
    {
        name: 'CATEGORÍA',
    },
    {
        name: 'ACCIONES',
    }
]);

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const product = urlParams.get('checkout');

if (urlParams.has('checkout')) {
    router.visit('cart/')
}

watch(
  () => props.cart,
  (cart) => {
    form.cart = cart    
  }
)

const discountedPrice = (price, discount, quantity) => {
    const discAmount = price * discount / 100;
    const discPrice = price - discAmount;
    const discQuantity = discPrice * quantity

    return parseFloat(discQuantity).toFixed(2);
}

const updateQuantity = (quantity, productId) => {
    let qty = parseInt(quantity.target.value);

    let newCartQuantity = {
        id: productId,
        quantity: qty,
    }

    if (typeof qty === 'number' && !Number.isNaN(qty)) {
        router.post(`cart/${props.cart_id}`, {
            newCartQuantity
        }, {
            preserveState: true,
            deep: true
        })
    }
}

const updateDiscount = (discount, productId) => {
    let qty = parseInt(discount.target.value);

    let newCartDiscount = {
        id: productId,
        discount: qty,
    }

    if (typeof qty === 'number' && !Number.isNaN(qty)) {
        router.post(`cart/${props.cart_id}`, {
            newCartDiscount
        }, {
            preserveState: true,
            deep: true
        })
    }
}

const clientData = ref(false);

const form = reactive({
    cart: props.cart,
    name: '',
    address: '',
    duiornit: '',
    cash: null,
    totalItems: props.total_items,
    totalPrice: props.total_price,
    change: null,
})

const cashChange = reactive({
    cash: form.cash
})

let changeCash = 0;

watch(
    () => props.total_items,
    (newTotalItems) => {
        form.totalItems = newTotalItems
    },
    { deep: true }
)

watch(
    () => props.total_price,
    (newTotalPrice) => {
        form.totalPrice = newTotalPrice

        if (cashChange.cash > 0 || cashChange.cash == newTotalPrice) {
            changeCash = parseFloat(form.cash - newTotalPrice).toFixed(2);
            form.change = changeCash;
        }
    },
    { deep: true }
)

watch(cashChange, newCash => {
    if (newCash.cash >= props.total_price) {
        changeCash = parseFloat(newCash.cash - props.total_price).toFixed(2);
        form.cash = newCash.cash
        form.change = changeCash;
    } else {
        changeCash = parseFloat(newCash.cash - props.total_price).toFixed(2);
        form.cash = newCash.cash
        form.change = changeCash;
    }
})

const reload = () => {
    window.location.reload();
}

const checkout = () => {
    router.post(`/cart/checkout/${props.cart_id}`, {
        checkout: form
    }, {
        preserveState: true,
        deep: true,
        onBefore: () => window.open('?checkout=true')
    })
};

const searchFilter = reactive({
    search: props.filters.search
});

watch(searchFilter, value => {
    router.get('/cart', {
        search: value.search
    }, {
        preserveState: true,
        deep: true
    })
})

const reset = () => {
    router.get('/cart', {
    }, searchFilter.search = '', {
        preserveState: true,
        deep: true
    });
};

//Modal
const searchModal = ref(false);

const searchProduct = () => {
    searchModal.value = true;
};

const closeModal = () => {
    searchModal.value = false;
};

</script>

<template>
    <Head title="Carrito" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex justify-start max-w-3xl">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link class="text-indigo-400 hover:text-indigo-600" href="/cart">Carrito</Link>
                </h1>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg col-span-9">
                        <div class="p-6">

                            <div class="flex items-center pb-3">
                                <button @click="searchProduct"
                                    class="text-white flex md:flex flex-row-reverse space-x-1 pb-4 bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Buscar
                                    producto
                                </button>
                            </div>

                            <form target="_blank" @submit.prevent="submit">
                                <Table :header="tableHeader">
                                    <tbody>

                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                            v-if="$page.props.cart.length > 0" v-for="(item, index) in $page.props.cart" :key="item.id">
                                            <td scope="row"
                                                class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                <img v-if="item.image" :src="item.image" class="w-10 h-10 rounded-full">
                                                <img v-else :src="route().t.url + '/img/defaultimg.png'" class="w-10 h-10 rounded-full">
                                                <br>

                                                <div class="text-base font-semibold">
                                                    {{ item.name }}
                                                </div>

                                            </td>
                                            <td scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                ${{ item.price }}
                                            </td>
                                            <td scope="row" class="px-6 py-4">
                                                <div class="flex justify-center items-center">
                                                    <TextInput min="1" :max="item.stock"
                                                        :value="item.quantity > item.stock ? item.stock : item.quantity"
                                                        type="number" class="mt-1 block w-9/12"
                                                        @change="updateQuantity($event, item.id)"/>
                                                </div>
                                            </td>
                                            <td scope="row" class="px-6 py-4">
                                                <div class="flex justify-center items-center">
                                                    <TextInput min="0" max="99" :value="item.discount" type="number"
                                                        class="mt-1 block w-1/2" 
                                                        @change="updateDiscount($event, item.id)"/>%
                                                </div>
                                            </td>
                                            <td scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                ${{ discountedPrice(item.price, item.discount, item.quantity) }}
                                            </td>
                                            <td scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <Link method="post" as="button"
                                                    :href="route('cart.destroy', [props.cart_id, item.id])"
                                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                Eliminar</Link>
                                            </td>
                                        </tr>
                                        <tr v-else>
                                            <td :colspan="tableHeader.length"
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                                <div class="flex justify-center">
                                                    <div
                                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <p class="text-center ">No se han encontrado productos en el
                                                            carrito.
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </Table>
                            </form>
                        </div>
                    </div>
                    <div class="ml-6 bg-white overflow-hidden shadow-xl sm:rounded-lg col-span-3">
                        <div class="p-6">
                            <h1 class="font-semibold text-center text-xl text-gray-800 leading-tight">
                                Resumen de la venta
                            </h1>
                        </div>
                        <div class="p-2">
                            <h1 class="text-xl text-gray-800 leading-tight">
                                Total: <span class="font-semibold ">${{ props.total_price }}</span>
                            </h1>
                        </div>
                        <div class="p-2">
                            <h2 class="text-md leading-tight">
                                Artículos: <span class="font-semibold ">{{ props.total_items }}</span>
                            </h2>
                        </div>
                        <div class="block mt-4">
                            <div class="flex justify-center items-center">
                                <Checkbox name="clientData" v-model:checked="clientData" />
                                <span class="ml-2 text-sm text-gray-600">Agregar datos del cliente</span>
                            </div>
                        </div>
                        <div v-if="clientData">
                            <div class="pt-6">
                                <h1 class="font-semibold text-center text-xl text-gray-800 leading-tight">
                                    Datos del cliente
                                </h1>
                            </div>
                            <div class="p-2">
                                <div class="mt-1">
                                    <InputLabel for="name" value="Nombre del cliente:" />

                                    <TextInput id="name" type="text" class="mt-1 block w-full"
                                        v-model="form.name" />
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="mt-1">
                                    <InputLabel for="address" value="Dirección del cliente:" />

                                    <TextInput id="address" type="text" class="mt-1 block w-full"
                                        v-model="form.address" />
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="mt-1">
                                    <InputLabel for="duiornit" value="DUI o NIT del cliente:" />

                                    <TextInput id="duiornit" type="text" class="mt-1 block w-full"
                                        v-model="form.duiornit" />
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="mt-4">
                                <InputLabel for="cash" value="Efectivo" />

                                <TextInput id="cash" type="number" step="0.01" class="mt-1 block w-full"
                                    v-model.number="cashChange.cash" required autocomplete="cash" />

                                <p class="tracking-tighter text-gray-500 md:text-lg dark:text-gray-400">
                                    Cambio: ${{ changeCash }}
                                </p>
                                <div v-if="cashChange.cash >= props.total_price && cashChange.cash > 0 && props.cart_id != null"
                                    class="flex items-center justify-end mt-4">
                                    <PrimaryButton class="ml-4" @click="checkout" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                        Realizar venta
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Modal
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
                    :show="searchModal" @close="closeModal">
                    <div class="p-6">
                        <div class="flex items-center pb-3">
                            <label for="table-search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" v-model="searchFilter.search"
                                    class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    name="search" placeholder="Buscar…">
                            </div>
                            <button @click="reset" type="button" class="pl-3 font-small text-blue-600 dark:text-blue-500">
                                Reset
                            </button>
                        </div>
                        <Table :header="tableHeaderModal">
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                    v-if="filters.length" v-for="(product, index) in filters" :key="product.id">
                                    <td class="flex justify-center items-center" scope="row">
                                        <div v-if="product.image">
                                            <img :src="`${route().t.url}/img/${product.image}?w=45&amp;h=45&amp;fit=crop`"
                                                class="w-10 h-10 rounded-full">
                                        </div>
                                        <div v-else>
                                            <img :src="route().t.url + '/img/defaultimg.png'" class="w-10 h-10 rounded-full">
                                        </div>
                                    </td>
                                    <td scope="row">
                                        {{ product.name }}
                                    </td>
                                    <td scope="row">
                                        ${{ product.price }}
                                    </td>
                                    <td scope="row">
                                        {{ product.category }}
                                    </td>
                                    <td scope="row">
                                        <button @click="reload">
                                            <Link
                                                class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                method="post" as="button" :href="route('cart.store', product.id)">
                                            Agregar al carrito
                                            </Link>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td :colspan="tableHeader.length"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <div class="flex justify-center">
                                            <div
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <p class="text-center ">No se han encontrado productos en la
                                                    busqueda.
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </Table>

                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal"> Cerrar </SecondaryButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>

    </AuthenticatedLayout>
</template>