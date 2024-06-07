<script setup>
import ModalItem from '@/Pages/Shared/ModalItem.vue'
import { ref } from 'vue'
import { Link } from "@inertiajs/vue3";

let props = defineProps({
    routeData:String,
    text:String,
    additionalText:String,
})


let showModal = ref(false)
let deleteConfirmed = ref(false)
function executeModal() {
    deleteConfirmed.value=true
    setTimeout(() => {
        showModal.value=false
    }, 700);
}
function closeModal() {
    showModal.value=false
    deleteConfirmed.value=false
}
</script>

<template>
    <button @click="showModal = true" class="w-full h-full uppercase">
        {{ text }}
    </button>

    <Teleport to="body">
        <ModalItem :show="showModal" @remove="closeModal()">
            <template #header>
                <div class="text-bold text-2xl text-center dark:bg-slate-700">
                    {{ text }}

                </div>
            </template>
            <template #default>
                <div class="font-bold uppercase underline text-center mt-4">
                    This action is permanent, please confirm. {{ additionalText }}
                </div>
                <div v-if="deleteConfirmed" class="font-bold uppercase underline text-center text-red-700 mt-4">
                    Confirmed, executing
                </div>
            </template>
            <template #footer>
                <Link :href="routeData" method="delete" @click="executeModal()" as="button"
                    class="w-full bg-red-600 text-white font-bold text-sm uppercase rounded hover:bg-red-700 flex items-center justify-center px-2 py-3 mt-6">
                {{ text }}</Link>
                <button @click="closeModal()"
                    class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6">Cancel</button>
            </template>
        </ModalItem>
    </Teleport>
</template>
