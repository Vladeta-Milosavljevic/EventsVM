<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
    links:Array,
})
</script>


<template>
     <div>
        <Link v-for="link in links" :href="link.url ? link.url :''" class="mx-1" as="button"  v-html="link.label"  :disabled="link.url===null"
        :class="{'font-bold underline dark:text-slate-100': link.active,'text-gray-400 ':link.url===null}" />
    </div>
</template>
