<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import EventUpdateModal from "@/Pages/Shared/EventUpdateModal.vue";
import EventDeleteModal from "@/Pages/Shared/EventDeleteModal.vue";
import { Link } from "@inertiajs/vue3";


defineOptions({ layout: Layout });
const props = defineProps({
    event: Object,
    categories: Array,
    image: String,
    can: Object
});

let tags = props.event.tags.split(" ")
console.log(props.event.user_id)
</script>

<template>
    <div class="flex justify-center w-full mb-24 dark:bg-slate-800 rounded-lg">
        <div class="w-full rounded-lg overflow-hidden shadow-lg">
            <img class="w-full h-auto" :src="event.image" alt="Image is not available">
            <div class="px-6 py-4">
                <div class="font-bold text-xl my-2 dark:text-slate-100">{{ event.name }}</div>
                <Link :href="route('home')" :data="{ category: category }" class="text-gray-700 hover:text-gray-900 dark:text-slate-100 dark:hover:text-slate-200 hover:underline text-base w-auto">
                    {{ event.category }} <br>
                </Link>
                <p class="text-gray-700 dark:text-slate-100 text-base my-2">
                    {{ event.description }} <br>
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 h-auto">
                <Link :href="route('home')" :data="{ tag: tag }" v-for="tag in tags" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 hover:bg-gray-300 mr-2 mb-2">
                    {{ tag }}</Link>
            </div>
            <div class="flex flex-row h-auto">
                <div v-if="can.edit"
                    class="w-1/2 h-12 bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">
                    <EventUpdateModal name="Edit The Event" :categories="categories" :event="event" uppercase />
                </div>

                <button v-if="can.delete"
                    class="w-1/2 h-12 bg-red-600 text-white font-bold text-sm uppercase rounded hover:bg-red-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">
                    <EventDeleteModal name="Delete The Event"  :event_id="event.id" uppercase />
                </button>

            </div>

        </div>
    </div>
</template>
