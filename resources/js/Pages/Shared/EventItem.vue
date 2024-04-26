<script setup>
import { Link } from "@inertiajs/vue3";


const props = defineProps({
    event: Object
});
let tags = props.event.tags.split(" ")
</script>

<template>
    <Link :href="route('event.show', event.id)" class="flex flex-wrap rounded-lg dark:bg-gray-800">
    <div class="w-96 rounded-lg overflow-hidden shadow-lg">
        <img class="w-full h-72" :src="event.image" alt="Sunset in the mountains">
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2 h-12 dark:text-slate-100">{{ event.name }}</div>
            <div class="flex flex-row justify-between">
                <div class="text-gray-700 hover:text-gray-900 hover:underline dark:text-slate-100 dark:hover:text-slate-200 text-base">
                    <Link :href="route('index')" :data="{ category: event.category }"> {{ event.category }} </Link>
                </div>
                <div class="text-gray-700 dark:text-slate-100  text-base">
                    {{ event.price }} €
                </div>
            </div>
        </div>
        <div class="px-6 pt-4 pb-2 h-32">
            <Link :href="route('index')" :data="{ tag: tag }" v-for="tag in tags"
                class="inline-block bg-gray-200 hover:bg-gray-300 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
            {{ tag }}</Link>
        </div>
    </div>
    </Link>
</template>
