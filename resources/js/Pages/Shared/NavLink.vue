<script setup>
import { Link } from "@inertiajs/vue3";

let props = defineProps({
    linkData: { type: String, required: true },
    text:String,
    data:Object,
    addStyle:String
});

</script>

<template>
<Link :href="linkData" :data="data" class="py-2 hover:underline hover:underline-offset-2 px-4 mx-2 w-36 md:w-auto" :class="addStyle">{{ text }}</Link>
</template>
