<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { Head,router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { AiDryad, HeFilledUiMenuGrid, CgClose } from "@kalimahapps/vue-icons";
import EventCreateModal from "./EventCreateModal.vue";
import debounce from 'lodash.debounce'

let props=defineProps({
    filters: {type:Object,default:{}},
})
const page = usePage();
let menu = ref(false);
let search = ref(props.filters.search);
watch(
    search,
    debounce((value) => {
        router.get(
            route("index"),
            { search: value },
            // preserveState: true NE UBACUJ jer je layout trajan (persistent) i nece aktivirati pretragu
            { replace: true }
        );
        console.log('test');
    },500)
);
</script>

<template>
    <Head>
        <title>Events VM App</title>
        <meta type="description" content="Information about the app" head-key="description">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </Head>

    <body class="bg-white dark:bg-slate-700 flex flex-col min-h-screen">

        <!-- Top Bar Nav -->
        <nav class="w-full bg-blue-800 dark:bg-slate-800 shadow-md mx-auto md:flex items-center justify-between fixed top-0 left-0">
            <div class="justify-between flex w-full items-center bg-blue-800 dark:bg-slate-800 font-bold  md:ml-0 pr-10 py-4 text-3xl md:text-2xl text-white">
                <div class="flex items-center md:pl-6 pl-2">
                    <AiDryad />
                    <Link :href="route('index')" class="ml-2 whitespace-nowrap">VM Events</Link>
                </div>
                <div @click="() => menu = !menu" class="cursor-pointer md:hidden">
                    <HeFilledUiMenuGrid v-show="!menu" />
                    <CgClose v-show="menu" />
                </div>

            </div>

        </nav>

        <!-- Text Header -->
        <header class="w-full container mx-auto" :class="[menu ? 'mt-56 ease-in duration-500' : 'mt-16 ease-in duration-500']">
            <div class="flex flex-col items-center py-12">
                <Link :href="route('index')" class="font-bold text-gray-800 uppercase dark:text-slate-100 text-5xl text-center">
                    Events VM
                </Link>
                <p class="text-lg text-gray-600 dark:text-slate-100 text-center">
                    Register and search for your favorite gigs, party spots and so on
                </p>
            </div>
        </header>

        <div class="container mx-auto flex flex-wrap py-6 justify-center">
            <slot />
        </div>

        <footer class="w-full border-t bg-white dark:bg-slate-800 pb-12 h-28 mt-auto">

            <div class="w-full container mx-auto flex flex-col items-center">
                <div class="flex flex-row text-center md:text-left md:justify-between py-6">
                    <a href="#" class="uppercase px-3 dark:text-slate-100">About Us</a>
                    <a href="#" class="uppercase px-3 dark:text-slate-100">Contact Us</a>
                </div>
                <div class="uppercase pb-6 dark:text-slate-100">&copy; Vladeta Milosavljevic</div>
            </div>
        </footer>

    </body>
</template>

