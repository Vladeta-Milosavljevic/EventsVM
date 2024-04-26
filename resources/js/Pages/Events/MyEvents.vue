<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import EventItem from "@/Pages/Shared/EventItem.vue";
import Pagination from "@/Pages/Shared/Pagination.vue";

defineOptions({ layout: Layout });
let props = defineProps({
    myEvents: Object,
    user_id: String,
});
let events = props.myEvents.data
let noEvents = Object.keys(events).length === 0
let paginationLinks = props.myEvents.links
</script>

<template>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tailwind Blog Template</title>
        <meta name="author" content="David Grzyb">
        <meta name="description" content="">



    </head>

    <body>
        <div class="flex flex-wrap justify-center">
            <div v-if="noEvents" class="p-2 m-2 dark:text-slate-100 underline underline-offset-4 uppercase text-2xl">
                You have no registered events. WHY?
                Fix this immediately

            </div>
            <div v-for="event in events" class="p-2 m-2">
                <EventItem :event="event" />
            </div>
        </div>

        <Pagination v-if="!noEvents" :links="paginationLinks" class="flex justify-center mb-32" />
    </body>
</template>
