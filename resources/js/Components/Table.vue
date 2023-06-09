<script setup>
  defineProps({
    header: Array,
  })
</script>

<template>
    <div class="relative overflow-x-auto shadow-lg sm:rounded-lg">
        <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
        <thead  class="text-md text-gray-100 uppercase bg-gray-700 dark:bg-gray-700 dark:text-white">
            <tr>
                <th v-for="item in header" scope="col" class="px-6 py-3">
                    {{ item.name }}
                </th>
            </tr>
        </thead>
        <slot />
        </table>
    </div>
</template>