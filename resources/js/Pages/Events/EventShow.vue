<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import EventUpdateModal from "@/Pages/Shared/EventUpdateModal.vue";
import DeleteModal from "@/Pages/Shared/DeleteModal.vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";
import { register } from 'swiper/element/bundle';

register();

defineOptions({ layout: Layout });
const props = defineProps({
    event: Object,
    can: Object,
});
let event = props.event.data
let tags = props.event.data.tags.split(" ")
const page = usePage();
let user = page.props.auth.userName
const form = useForm({
    event_id: props.event.data.id,
})

</script>

<template>
    <div class="flex justify-center w-4/5 mb-24 dark:bg-slate-800 rounded-lg">
        <div class="w-full rounded-lg overflow-hidden shadow-lg">
            <div v-if="$page.props.flash.stripeSuccess" class="w-full h-8 overflow-hidden shadow-lg bg-green-600 text-white text-center text-xl">
                {{ $page.props.flash.stripeSuccess }}
            </div>
            <div v-if="$page.props.flash.stripeError" class="w-full h-8 overflow-hidden shadow-lg bg-red-600 text-white text-center text-xl">
                {{ $page.props.flash.stripeError }}
            </div>
            <swiper-container clickable="true" navigation="true" pagination-clickable="true" scrollbar-draggable="true">
                <swiper-slide class="w-full h-full" v-for="image in event.images"><img class="w-full h-auto" :src="image"
                        alt="Image is not available"></swiper-slide>
            </swiper-container>
            <div class="px-6 py-4">
                <div class="font-bold text-xl my-2 dark:text-slate-100">{{ event.name }}</div>
                <Link :href="route('index')" method="POST" :data="{ category: event.category }" as="button"
                    class="text-gray-700 hover:text-gray-900 dark:text-slate-100 dark:hover:text-slate-200 hover:underline text-base w-auto">
                {{ event.category }} <br>
                </Link>
                <div class="text-gray-700 dark:text-slate-100 text-base w-auto">
                    {{ event.price }} € <br>
                </div>
                <p class="text-gray-700 dark:text-slate-100 text-base my-2">
                    {{ event.description }} <br>
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 h-auto">
                <Link :href="route('index')" :data="{ tag: tag }" v-for="tag in tags"
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 hover:bg-gray-300 mr-2 mb-2">
                {{ tag }}</Link>
            </div>

            <div v-if="user !='guest'" class="flex flex-row h-14 mt-2">
                <form @submit.prevent="form.post(route('stripe.buyTicket'))" class="w-full h-full">
                    <input type="text" hidden v-model="form.event_id">
                    <button :disabled="form.processing" type="submit"
                        class="w-full h-full bg-blue-600 text-white font-bold text-sm uppercase hover:bg-blue-700 flex items-center justify-center disabled:bg-blue-200 ">
                        Buy Ticket for the event ({{ event.price }} €)
                    </button>
                </form>
            </div>
            <div v-if="can.edit || can.delete" class="flex flex-row h-14 ">
                <div v-if="can.edit"
                    class="w-1/2 h-full bg-green-600 text-white font-bold text-sm uppercase hover:bg-green-700 flex items-center justify-center disabled:bg-green-200">
                    <EventUpdateModal :event="event" />
                </div>
                <button v-if="can.delete"
                    class="w-1/2 h-full bg-red-600 text-white font-bold text-sm uppercase hover:bg-red-700 flex items-center justify-center  disabled:bg-green-200">
                    <DeleteModal :routeData="route('event.destroy',event.id)" text="Delete the event." />
                </button>
            </div>

        </div>
    </div>
</template>
