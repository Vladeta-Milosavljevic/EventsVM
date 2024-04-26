<script setup>
import ModalItem from '@/Pages/Shared/ModalItem.vue'
import { ref, computed, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3';
import InputGroup from '@/Pages/Shared/InputGroup.vue';
import SelectGroup from '@/Pages/Shared/SelectGroup.vue';


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
    _method: 'put'
})
let showModal = ref(false)
function success() {
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
                <!-- preserveState: false - to show the updated list of events instead of preserving the old one -->
                <form @submit.prevent="form.post(route('event.update', event.id), { preserveState: false, onSuccess: () => success() })"
                    class="flex flex-col">
                    <InputGroup autofocus v-model="form.name" :errors="form.errors.name" label="Event's name" />
                    <SelectGroup v-model="form.category_id" :selectData="categories" :errors="form.errors.category_id" label="Event's category" />
                    <InputGroup v-model="form.tags" :errors="form.errors.tags" label="Event's tags - please separate them with whitespace" />
                    <InputGroup inputType="textarea" v-model="form.description" :errors="form.errors.description" label="Event's description" />
                    <InputGroup type="decimal" v-model="form.price" :errors="form.errors.price"
                        label="Event's ticket price. No more than 200 €, and dont forget two decimal places." />
                    <div class="mb-6">
                        <label :for="form.image" class="block mb-2">Event's title image</label>
                        <input @input="form.image = $event.target.files[0]" type="file" name="image" id="image"
                            class="rounded-lg border border-gray-400 p-2 w-full" />
                        <div v-if="form.errors.image" class="block mb-2 mt-1 text-red-600">
                            {{ form.errors.image }}
                        </div>
                    </div>

                    <div class="mb-6">
                        <label :for="form.addImages" class="block mb-2">If you choose to update please update all images as the previous will be <span
                                class="text-red-400">deleted</span> (no more than five)</label>
                        <input @input="form.addImages = $event.target.files" type="file" name="addImages[]" id="addImages"
                            class="rounded-lg border border-gray-400 p-2 w-full" multiple />
                        <div v-for="(item, index) in form.errors" :key="index" class="block mb-2 mt-1 text-red-600">
                            <div v-if="index.includes('addImages')"> {{ form.errors[index] }}</div>
                        </div>
                    </div>
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
