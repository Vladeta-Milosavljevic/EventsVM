<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import Navbar from "@/Pages/Shared/Navbar.vue";


let menu = ref(false);
</script>

<template>
    <Head>
        <title>Events VM App</title>
        <meta type="description" content="Information about the app" head-key="description">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </Head>

    <body class="bg-white dark:bg-slate-700 flex flex-col min-h-screen">
        <Navbar v-model:menu="menu" />


        <!-- Text Header -->
        <header class="w-full container mx-auto" :class="[menu ? 'mt-64 ease-in duration-500' : 'mt-16 ease-in duration-500']">
            <div class="flex flex-col items-center py-12">
                <div class="font-bold text-gray-800 uppercase dark:text-slate-100 text-5xl text-center ">
                    Events VM
                </div>
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

