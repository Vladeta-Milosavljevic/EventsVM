<script setup>
import ModalItem from '@/Pages/Shared/ModalItem.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3';
import InputGroup from '@/Pages/Shared/InputGroup.vue';
import SelectGroup from '@/Pages/Shared/SelectGroup.vue';


let props = defineProps({
    name: String,
    uppercase: Boolean,
    categories: Array,
    event: Object,
})

const form = useForm({
    name: props.event.name,
    category: props.event.category,
    tags: props.event.tags,
    description: props.event.description,
    image: '',
    _method: 'put'
})

let showModal = ref(false)
function success() {
    showModal.value = false
    form.reset()
}
</script>

<template>
    <button @click="showModal = true" class="w-full h-full" :class="{ 'uppercase': uppercase }">
        {{ name }}
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

                    <SelectGroup v-model="form.category" :selectData="categories" :errors="form.errors.category" label="Event's category" />



                    <InputGroup v-model="form.tags" :errors="form.errors.tags" label="Event's tags - please separate them with whitespace" />
                    <InputGroup inputType="textarea" v-model="form.description" :errors="form.errors.description" label="Event's description" />
                    <div class="mb-6">
                        <label :for="form.image" class="block mb-2">Event's image</label>
                        <input @input="form.image = $event.target.files[0]" type="file" name="image"
                            class="rounded-lg border border-gray-400 p-2 w-full" />
                        <div v-if="form.errors.image" class="block mb-2 mt-1 text-red-600">
                            {{ form.errors.image }}
                        </div>
                    </div>

                    <button :disabled="form.processing" type="submit"
                        class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">Update
                        The Event</button>
                </form>
            </template>
            <template #footer>
                <button @click="form.reset()"
                    class="w-full bg-yellow-600 text-white font-bold text-sm uppercase rounded hover:bg-yellow-700 flex items-center justify-center px-2 py-3 mt-6">Reset
                    the form</button>
                <button @click="showModal = false"
                    class="w-full bg-blue-600 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-6">Close</button>
            </template>
        </ModalItem>
    </Teleport>
</template>
