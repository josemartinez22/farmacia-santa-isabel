<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
    options: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue'])

const selected = ref(null);

</script>

<template>
    <select ref="selected" @change="$emit('update:modelValue', Number($event.target.value))" :value="modelValue"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
        <option v-for="option in options" :value="option.value">{{ option.text }}</option>
    </select>
</template>