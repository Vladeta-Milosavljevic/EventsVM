<script setup>
import { onMounted, ref } from 'vue';

let props = defineProps({
    modelValue: { type: String, required: true },
    selectData: { type: Array, required: true },
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
    <label :for="modelValue" class="block mb-2">{{ label }}</label>
    <select name="category" :value="modelValue" @click="$emit('update:modelValue', $event.target.value)" :required="required"
        class="rounded-lg border border-gray-400 p-2 w-full mb-6 dark:text-gray-700">
        <option v-for="data in selectData" :id="data.id">{{ data.name }} </option>
    </select>
    <div v-if="errors" class="block mb-2 mt-1 text-red-600">
        {{ errors }}
    </div>
</template>



