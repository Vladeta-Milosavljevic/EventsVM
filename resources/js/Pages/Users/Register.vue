<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthLayout from "@/Pages/Shared/AuthLayout.vue";
import InputGroup from "@/Pages/Shared/InputGroup.vue"

defineOptions({ layout: AuthLayout });

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})
</script>

<template>
    <div class="bg-slate-800 rounded-lg p-6 m-6">
        <form @submit.prevent="form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') })" class="flex flex-col w-96">
            <InputGroup required autofocus v-model="form.name" :errors="form.errors.name" label="Name"  />
            <InputGroup required v-model="form.email" :errors="form.errors.email" label="Email" />
            <InputGroup required v-model="form.password" :errors="form.errors.password" label="Password" type="password" />
            <InputGroup required v-model="form.password_confirmation" :errors="form.errors.password_confirmation" label="Confirm Password" type="password" />

            <button :disabled="form.processing" type="submit"
                class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">
                Register
            </button>
            <Link :href="route('home')"
                class="w-full bg-blue-600 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">
            Cancel
            </Link>
        </form>

    </div>
    <div class="h-96">
    </div>
</template>
