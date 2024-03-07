<script setup>
import ModalItem from '@/Pages/Shared/ModalItem.vue'
import { ref } from 'vue'
import { Link } from "@inertiajs/vue3";

let props = defineProps({
    event_id: Number
})


let showModal = ref(false)
let deleteConfirmed = ref(false)
</script>

<template>
    <button @click="showModal = true" class="w-full h-full uppercase">
        Delete The Event
    </button>

    <Teleport to="body">
        <ModalItem :show="showModal" @remove="showModal = false">
            <template #header>
                <div class="text-bold text-2xl text-center dark:bg-slate-700">
                    Delete the Event

                </div>
            </template>
            <template #default>
                <div class="font-bold uppercase underline text-center mt-4">
                    This action is permanent, please confirm.
                </div>
                <div v-if="deleteConfirmed" class="font-bold uppercase underline text-center text-red-700 mt-4">
                    Confirmed, executing
                </div>
            </template>
            <template #footer>
                <Link :href="route('event.destroy', event_id)" method="delete" @click="deleteConfirmed = true" as="button"
                    class="w-full bg-red-600 text-white font-bold text-sm uppercase rounded hover:bg-red-700 flex items-center justify-center px-2 py-3 mt-6">
                Delete The Event</Link>
                <button @click="showModal = false"
                    class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6">Cancel</button>
            </template>
        </ModalItem>
    </Teleport>
</template>
