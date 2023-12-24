<script setup>
import { onMounted, ref } from 'vue';

let props = defineProps({
    modelValue: { type: String, required: true },
    errors: String,
    label: String,
    type: { type: String, default: 'text' },
    inputType: { type: String, default: 'input' },
    autofocus: { type: Boolean, default: false },
    required: { type: Boolean, default: true }
});

defineEmits(['update:modelValue']);

const input = ref(null);
onMounted(() => {
    if (props.autofocus) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="mb-6">
        <label :for="modelValue" class="block mb-2 dark:text-slate-100">{{ label }}</label>
        <Component :is="inputType" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" :required="required" :type="type"
            class="rounded-lg border border-gray-400 p-2 w-full focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600 dark:text-gray-700"
            :class="{'resize-y':inputType==='textarea'}" />
        <div v-if="errors" class="block mb-2 mt-1 text-red-600">
            {{ errors }}
        </div>
    </div>
</template>
