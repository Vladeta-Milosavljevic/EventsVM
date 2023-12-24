<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { AiDryad, HeFilledUiMenuGrid, CgClose } from "@kalimahapps/vue-icons";
import EventCreateModal from "./EventCreateModal.vue";
import debounce from 'lodash.debounce'

let props = defineProps({
    menu: { type: Boolean, default: false }
})
defineEmits(['update:menu']);
const page = usePage();
let user_id = page.props.auth.user_id ? page.props.auth.user_id : ''
let menu = props.menu;
let categories = page.props.categories ? page.props.categories : []
let search = ref(page.props.filters ? page.props.filters.search : '');
watch(
    search,
    debounce((value) => {
        router.get(
            route("home"),
            { search: value },
            // preserveState: true NE UBACUJ jer je layout trajan (persistent) i nece aktivirati pretragu
            { replace: true }
        );
    }, 500)
);
</script>

<template>
    <nav class="w-full bg-blue-800 dark:bg-slate-800 shadow-md mx-auto md:flex items-center justify-between fixed top-0 left-0">
        <div class="justify-between flex w-full items-center bg-blue-800 dark:bg-slate-800 font-bold  md:ml-0 pr-10 py-4 text-3xl md:text-2xl text-white">
            <div class="flex items-center md:pl-6 pl-2">
                <AiDryad />
                <Link :href="route('home')" class="ml-2 whitespace-nowrap">VM Events</Link>
            </div>
            <div @click="$emit('update:menu', menu = !menu)" class="cursor-pointer md:hidden">
                <HeFilledUiMenuGrid v-show="!menu" />
                <CgClose v-show="menu" />
            </div>

        </div>
        <div class="md:static  md:pb-0  md:justify-between absolute ease-in duration-500 md:z-auto z-[-1] md:w-auto w-full bg-blue-800 dark:bg-slate-800 mx-auto text-white flex flex-col items-center text-center md:flex-row text-sm font-bold uppercase px-4 mr-2"
            :class="[menu ? 'top-16' : 'top-[-190%]']">
            <Link :href="route('home')" class="py-2 hover:underline px-4 mx-2 w-36 md:w-auto">Home</Link>
            <div v-if="user_id" class="py-2 px-4 mx-2 w-36 md:w-auto hover:underline">
                <EventCreateModal name="Create" :categories="categories" uppercase />
            </div>
            <Link v-if="user_id" :href="route('myEvents')" :data="{ user_id: user_id }"
                class="py-2 hover:underline px-4 mx-2 w-36 md:w-auto whitespace-nowrap">My Events</Link>
            <Link v-if="!user_id" :href="route('login')" class="py-2 hover:underline px-4 mx-2 w-36 md:w-auto whitespace-nowrap">Log in</Link>
            <Link v-if="!user_id" :href="route('register')" class="py-2 hover:underline px-4 mx-2 w-36 md:w-auto whitespace-nowrap">Register</Link>
            <Link v-if="user_id" method="post" :href="route('logout')" as="button" class="py-2 hover:underline uppercase px-4 mx-2 w-36 md:w-auto whitespace-nowrap">Log out</Link>
            <input v-model="search" placeholder="Search ..." v-html="search" type="text"
                class="rounded-lg border font-normal text-gray-700  p-2 w-80 md:w-48 mb-2 md:mb-0" />
        </div>
    </nav>
</template>