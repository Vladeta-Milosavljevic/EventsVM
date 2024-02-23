<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { ReArrowDropDownLine, ReArrowDropUpLine } from "@kalimahapps/vue-icons";


let show = ref(false)
const page = usePage();
let userName = page.props.auth.userName ? page.props.auth.userName : ''
let user_id = page.props.auth.user_id ? page.props.auth.user_id : ''
let toggle = () => {
    show.value = !show.value
    setTimeout(() => {
        show.value = false
    }, 3000);
}
</script>

<template>
    <div class="relative">
        <!-- Dropdown toggle button -->
        <button @click="toggle()" class="flex items-center p-2 rounded-md ml-1">
            <span class="hover:underline hover:underline-offset-2 uppercase">{{ userName }}</span>
            <div class="text-3xl">
                <ReArrowDropDownLine v-show="!show" />
                <ReArrowDropUpLine v-show="show" />
            </div>
        </button>

        <!-- Dropdown menu -->
        <Transition enter-from-class="opacity-0 -translate-y-12 scale-100" enter-active-class="transition duration-300"
            enter-to-class="opacity-100 scale-100" leave-from-class="opacity-100 scale-100" leave-active-class="transition duration-300"
            leave-to-class="opacity-0 -translate-y-12 scale-100">
            <div v-show="show"
                class="absolute -right-3 md:right-0  py-2 mt-0 w-32 bg-blue-800 dark:bg-slate-800 rounded-md shadow-xl flex flex-col items-center">
                <Link method="post" :href="route('logout')" as="button"
                    class="block py-2 hover:underline hover:underline-offset-2 px-4 mx-2 md:w-auto whitespace-nowrap uppercase">
                Log Out
                </Link>
                <Link :href="route('profile.edit')" as="button"
                    class="block py-2 hover:underline hover:underline-offset-2 px-4 mx-2 w-36 md:w-auto whitespace-nowrap uppercase">
                Profile
                </Link>
            </div>
        </Transition>
    </div>
</template>
