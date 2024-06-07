<script setup>
import { useForm } from '@inertiajs/vue3';
import InputGroup from '@/Pages/Shared/InputGroup.vue';

let props = defineProps({
    routeData: String,
    text: String,
    categoryData: Object,
});

let emits = defineEmits(['updateCompleted'])

const form = useForm({
    name: props.categoryData ? props.categoryData.name : '',
    _method: props.categoryData ? 'put' : 'post'
})
function success() {
    emits('updateCompleted')
    form.reset()
}

</script>

<template>
    <form @submit.prevent="form.post(routeData, {
        preserveScroll: true,
        onSuccess: () => {
            success()
        }
    })" class="flex flex-col w-full">
        <InputGroup autofocus v-model="form.name" :errors="form.errors.name" label="Category name" />
        <button :disabled="form.processing" type="submit"
            class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 -mt-2  disabled:bg-green-200">{{
        text }}</button>
    </form>
</template>
