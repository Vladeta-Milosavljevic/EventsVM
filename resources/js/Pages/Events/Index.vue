<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import EventItem from "@/Pages/Shared/EventItem.vue";
import Pagination from "@/Pages/Shared/Pagination.vue";
import { usePage } from "@inertiajs/vue3";

defineOptions({ layout: Layout });
let props = defineProps({
    events: Object,
});
let events = props.events.data
let paginationLinks = props.events.links
</script>

<template>
    <div class="flex flex-col">
        <div class="flex flex-wrap justify-center">
            <div v-for="event in events" class="p-2 m-2">
                <EventItem :event="event" />
            </div>
        </div>

        <Pagination :links="paginationLinks" class="flex justify-center mb-28 mt-6 text-lg" />
    </div>
</template>
