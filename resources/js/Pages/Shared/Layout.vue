<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Navbar from "@/Pages/Shared/Navbar.vue";
import Footer from "@/Pages/Shared/Footer.vue";


let menu = ref(false);
</script>

<template>
    <Head>
        <title>Events VM App</title>
        <meta type="description" content="Information about the app" head-key="description">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </Head>

    <body class="bg-slate-50 dark:bg-slate-700 flex flex-col min-h-screen">
        <Navbar v-model:menu="menu" />


        <!-- Text Header -->
        <header class="w-full container mx-auto" :class="[menu ? 'mt-64 ease-in duration-500' : 'mt-16 ease-in duration-500']">
            <div class="flex flex-col items-center py-12">
                <Link :href="route('index')" class="font-bold text-gray-800 uppercase dark:text-slate-100 text-5xl text-center ">
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

        <Footer />

    </body>
</template>

