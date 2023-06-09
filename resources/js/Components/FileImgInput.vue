<script setup>

const props = defineProps({
    modelValue: File,
})

const emit = defineEmits(['update:modelValue']);

const onChange = (e) => {
    emit('update:modelValue', e.target.files[0]);
};

</script>

<template>
    <input 
        class="block mt-1 w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 file:bg-transparent file:border-0 file:bg-gray-100 file:mr-4 file:py-2 file:px-4 dark:file:bg-gray-700 dark:file:text-gray-400"
        type="file" 
        @change="onChange($event)" 
        accept="image/*"
    />
</template>