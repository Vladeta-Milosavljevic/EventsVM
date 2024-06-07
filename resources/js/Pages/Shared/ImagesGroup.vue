<script setup>

let props = defineProps({
    modelValue: { type: File, required: true },
    required: { type: Boolean, default: false },
    errors: String,
    label: String,
});

defineEmits(['update:modelValue']);

</script>

<template>
    <div class="mb-6">
        <label :for="modelValue" class="block mb-2">{{ label }}</label>
        <input @input="$emit('update:modelValue', $event.target.files)" type="file" name="image" id="image" :required="required"
            class="rounded-lg border border-gray-400 p-2 w-full" multiple />
        <div v-for="(item, index) in errors" :key="index" class="block mb-2 mt-1 text-red-600">
            <div v-if="index.includes('addImages')"> {{ errors[index] }}</div>
        </div>
    </div>
</template>
