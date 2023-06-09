<script  setup>
import { reactive } from 'vue';
import Modal from "@/Shared/Modal.vue"
import Table from '@/Components/Table.vue';

const props = defineProps({
    sale_details: {
        type: Object,
        required: true
    },
    total_items: {
        type: Number,
        required: true
    },
    total_price: {
        type: String,
        required: true
    },
});

const tableHeader = reactive([
    {
        name: 'PRODUCTO',
    },
    {
        name: 'CANTIDAD',
    },
    {
        name: 'PRECIO',
    },
    {
        name: 'DESCUENTO (%)',
    },
    {
        name: 'SUBTOTAL',
    }
]);

</script>

<template>
    <Modal>

        <h1 class="mb-4 text-xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white">
            Detalle de venta <mark class="px-2 text-white bg-blue-600 rounded dark:bg-blue-500">#{{
                props.sale_details.sale_id }}</mark>
        </h1>
        <Table :header="tableHeader">
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                    v-if="sale_details.products.length" v-for="(sale_detail, index) in sale_details.products"
                    :key="sale_detail.id">
                    <td scope="row">
                        {{ sale_detail.product_name }}
                    </td>
                    <td scope="row">
                        {{ sale_detail.quantity }}
                    </td>
                    <td scope="row">
                        ${{ sale_detail.price }}
                    </td>
                    <td scope="row">
                        ${{ sale_detail.discPrice }}
                        ({{ sale_detail.discount }}%)
                    </td>
                    <td scope="row">
                        ${{ sale_detail.subtotal }}
                    </td>
                </tr>
                <tr v-else>
                    <td :colspan="tableHeader.length"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                        <div class="flex justify-center">
                            <div class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <p class="text-center ">No se han encontrado detalles en la
                                    venta.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <td class="text-right">
                    <p class="font-black text-gray-900 dark:text-white">TOTAL ARTÍCULOS:</p>
                </td>
                <td class="text-center">
                    <p class="font-black text-gray-900 dark:text-white">{{ props.total_items }}</p>
                </td>
                <td></td>
                <td class="text-right">
                    <p class="font-black text-gray-900 dark:text-white">TOTAL VENTA:</p>
                </td>
                <td>
                    <p class="font-black text-gray-900 dark:text-white">${{ props.total_price }}</p>
                </td>
            </tfoot>

        </Table>

    </Modal>
</template>