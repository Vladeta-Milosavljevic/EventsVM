<script setup>
import { Link } from "@inertiajs/vue3";

let props = defineProps({
    linkData: { type: String, required: true },
    text: String,
    data: Object,
    addStyle: String,
    pageComponent:String
});

</script>

<template>
    <Link :href="linkData" :data="data" class="py-2 hover:underline hover:underline-offset-2 px-4 mx-2 w-36 md:w-auto whitespace-nowrap"
    :class="{ 'underline underline-offset-2': $page.component === pageComponent }">{{ text }}</Link>
</template>
