<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import { ref } from 'vue'
import CategoryForm from "@/Pages/Shared/CategoryForm.vue";
import DeleteModal from "@/Pages/Shared/DeleteModal.vue";


defineOptions({ layout: Layout });

let props = defineProps({
    categories: Object
})
let showEdit = ref(false)
let editData = ref({})


function editClose() {
    editData.value = {}
    showEdit.value = false
}
function editOpen(category) {
        editData.value = category
        showEdit.value = true
}
</script>

<template>
    <div class="flex flex-col w-3/4">
        <div class="flex flex-wrap justify-center">
            <CategoryForm :routeData="route('category.store')" text="Create a new category" />
            <div v-if="showEdit" class="w-full">
                <CategoryForm :key="editData" :routeData="route('category.update', editData.id)" :categoryData="editData" text="Edit a category"
                    @updateCompleted="editClose()" />
                <button @click="editClose()"
                    class="w-full bg-yellow-600 text-white font-bold text-sm uppercase rounded hover:bg-yellow-700 flex items-center justify-center px-2 py-3   disabled:bg-yellow-200">Cancel
                    the edit.</button>
            </div>
            <div v-for="category in categories" class="p-2 m-2 w-full text-center uppercase">
                <div class="dark:bg-slate-500 bg-blue-200 rounded dark:text-white"
                    :class="{ 'underline underline-offset-2 font-extrabold': category.id === editData.id }">
                    {{ category.name }}
                </div>

                <div class="w-full h-full flex flex-row">
                    <button @click="editOpen(category)"
                        class="w-1/2 rounded-l bg-green-600 text-white font-bold text-sm uppercase hover:bg-green-700 flex items-center justify-center disabled:bg-green-200">
                        Edit
                    </button>
                    <button
                        class="w-1/2 rounded-r bg-red-600 text-white font-bold text-sm uppercase hover:bg-red-700 flex items-center justify-center disabled:bg-green-200">
                        <DeleteModal :routeData="route('category.destroy', category.id)" text="Delete the category."
                            additionalText="This will also delete all the events with the related category" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
