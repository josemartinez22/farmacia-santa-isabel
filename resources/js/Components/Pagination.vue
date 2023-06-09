<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Object,
});

</script>

<template>
    <div v-if="links.links.length > 3"
        class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ links.from }}</span>
                    a
                    <span class="font-medium">{{ links.to }}</span>
                    de
                    <span class="font-medium">{{ links.total }}</span>
                    resultados
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <template v-for="(link, key) in links.links" :key="key">
                        <Link v-if="link.url != null && link.active" :href="link.url" v-html="link.label"
                            aria-current="page"
                            class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" />
                        <span v-else-if='link.url == null && link.label == "..."' v-html="link.label" 
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-300 focus:z-20 focus:outline-offset-0" />
                        <Link v-else-if="link.url != null" :href="link.url" v-html="link.label"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-300 focus:z-20 focus:outline-offset-0" />
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>