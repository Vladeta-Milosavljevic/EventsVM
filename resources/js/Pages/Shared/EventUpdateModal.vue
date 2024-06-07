<script setup>
import ModalItem from '@/Pages/Shared/ModalItem.vue'
import { ref } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3';
import InputGroup from '@/Pages/Shared/InputGroup.vue';
import SelectGroup from '@/Pages/Shared/SelectGroup.vue';
import ImageGroup from '@/Pages/Shared/ImageGroup.vue';
import ImagesGroup from '@/Pages/Shared/ImagesGroup.vue';


let props = defineProps({
    event: Object,
})

const page = usePage();
let categories = page.props.categories

const form = useForm({
    name: props.event.name,
    category_id: props.event.category_id,
    tags: props.event.tags,
    description: props.event.description,
    price: props.event.price,
    image: '',
    addImages: [],
    _method:'put'
})
let showModal = ref(false)
function success() {
    form.reset()
    showModal.value = false
}

function reset() {
    form.clearErrors()
    form.reset()
    document.getElementById('image').value = null
    document.getElementById('addImages').value = null
}

</script>

<template>
    <button @click="showModal = true" class="w-full h-full uppercase">
        Edit The Event
    </button>

    <Teleport to="body">
        <ModalItem :show="showModal" @remove="showModal = false">
            <template #header>
                <div class="text-bold text-2xl text-center">
                    Update the event.
                </div>
            </template>
            <template #default>
                <form @submit.prevent="form.post(route('event.update', event.id), { onSuccess: () => success() })" class="flex flex-col">
                    <InputGroup autofocus v-model="form.name" :errors="form.errors.name" label="Event's name" />

                    <SelectGroup v-model="form.category_id" :selectData="categories" :errors="form.errors.category_id" label="Event's category" />

                    <InputGroup v-model="form.tags" :errors="form.errors.tags" label="Event's tags - please separate them with whitespace" />

                    <InputGroup inputType="textarea" v-model="form.description" :errors="form.errors.description" label="Event's description" />

                    <InputGroup type="decimal" v-model="form.price" :errors="form.errors.price"
                        label="Event's ticket price. No more than 200 €, and dont forget two decimal places." />

                    <ImageGroup v-model="form.image" :errors="form.errors.image" label="Event's image" />

                    <ImagesGroup v-model="form.addImages" :errors="form.errors" label="Event's additional images (no more than five)" />

                    <button :disabled="form.processing" type="submit"
                        class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">Update
                        The Event</button>
                </form>
            </template>
            <template #footer>
                <button @click="reset()"
                    class="w-full bg-yellow-600 text-white font-bold text-sm uppercase rounded hover:bg-yellow-700 flex items-center justify-center px-2 py-3 mt-6">Reset
                    the form</button>
                <button @click="showModal = false"
                    class="w-full bg-blue-600 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-6">Close</button>
            </template>
        </ModalItem>
    </Teleport>
</template>
