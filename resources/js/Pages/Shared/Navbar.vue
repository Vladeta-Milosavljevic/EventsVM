<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { AiDryad, HeFilledUiMenuGrid, CgClose } from "@kalimahapps/vue-icons";
import EventCreateModal from "./EventCreateModal.vue";
import NavDropDown from "./NavDropDown.vue"
import NavLink from "./NavLink.vue"
import debounce from 'lodash.debounce'

let props = defineProps({
    menu: { type: Boolean, default: false }
})
defineEmits(['update:menu']);
const page = usePage();
let user_id = page.props.auth.user_id ? page.props.auth.user_id : ''
let isAdmin = page.props.auth.isAdmin ? page.props.auth.isAdmin : 'false'
let menu = props.menu;
let searchFilter = computed(() => page.props.filters.search)
let search = ref('');
watch(
    search,
    debounce(() => {
        router.get(
            route("index"),
            { search: search.value },
            // preserveState: true is not to be used because of the persistent layout which will interfere and not trigger the search update
            { replace: true }
        );
    }, 500)
);
watch(searchFilter, () => { search.value = searchFilter.value })
</script>

<template>
    <nav class="w-full bg-blue-800 dark:bg-slate-800 shadow-md mx-auto md:flex items-center justify-between fixed top-0 left-0 z-10">
        <div
            class="justify-between flex w-full items-center bg-blue-800 dark:bg-slate-800 font-bold  md:ml-0 pr-10 py-4 text-3xl md:text-2xl text-white">
            <div class="flex items-center md:pl-6 pl-2">
                <AiDryad />
                <Link :href="route('index')" class="ml-2 whitespace-nowrap">VM Events</Link>
            </div>
            <div @click="$emit('update:menu', menu = !menu)" class="cursor-pointer md:hidden">
                <HeFilledUiMenuGrid v-show="!menu" />
                <CgClose v-show="menu" />
            </div>

        </div>
        <div class="md:static  md:pb-0  md:justify-between absolute ease-in duration-500 md:z-auto z-[-1] md:w-auto w-full bg-blue-800 dark:bg-slate-800 mx-auto text-white flex flex-col items-center text-center md:flex-row text-sm font-bold uppercase px-4 mr-2"
            :class="[menu ? 'top-16' : 'top-[-190%]']">
            <NavLink :linkData="route('index')" text="Home" pageComponent="Events/Index" />
            <div v-if="user_id" class="py-2 px-4 mx-2 w-36 md:w-auto">
                <EventCreateModal />
            </div>
            <div v-if="user_id">
                <NavLink :linkData="route('myEvents', user_id)" text="My Events" pageComponent="Events/MyEvents" />
            </div>
            <div v-if="isAdmin=='true'">
                <NavLink :linkData="route('category.index')" text="Categories" pageComponent="Category/CategoryIndex" />
            </div>
            <div v-if="!user_id">
                <NavLink :linkData="route('login')" text="Log in" />
                <NavLink :linkData="route('register')" text="Register" />
            </div>

            <div class="ml-4">
                <NavDropDown v-if="user_id" />
            </div>
            <input v-model="search" placeholder="Search ..." v-html="search" type="text"
                class="rounded-lg border font-normal text-gray-700  p-2 w-80 md:w-48 mb-2 md:mb-0" />
        </div>
    </nav>
</template>
