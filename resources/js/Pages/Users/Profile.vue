<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import { Head } from '@inertiajs/vue3';
import InputGroup from '@/Pages/Shared/InputGroup.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue'

defineOptions({ layout: Layout });


const page = usePage();

let props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    userName: String,
    email: String,
});
const formProfile = useForm({
    name: props.userName,
    email: props.email,
});
let profileMessage = ref('')
function successProfile() {
    profileMessage.value = 'Profile username and/or email changed'
    setTimeout(() => {
        profileMessage.value = ''
    }, 3000);
}


const formPassword = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});
let passwordMessage = ref('')
function successPassword() {
    formPassword.reset()
    passwordMessage.value = 'Password updated'
    setTimeout(() => {
        passwordMessage.value = ''
    }, 3000);
}


const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const updatePassword = () => {
    formPassword.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => successPassword(),
        onError: () => {
            if (formPassword.errors.password) {
                formPassword.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (formPassword.errors.current_password) {
                formPassword.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};

</script>

<template>
    <div>

        <Head title="Profile" />
        <p class=" flex justify-center text-xl text-gray-600 dark:text-slate-100 text-center uppercase ">
            Update your profile
        </p>
        <div class="flex flex-wrap justify-center p-4">
            <div class="p-6 w-full">
                <div class="dark:text-slate-100 mb-4 text-center text-lg"> Change username and/or email</div>
                <div v-if="profileMessage" class="text-green-500 underline font-bold w-60">{{ profileMessage }}</div>

                <form @submit.prevent="formProfile.patch(route('profile.update'), { onSuccess: () => successProfile() })" class="flex flex-col">
                    <InputGroup ref="currentPasswordInput" v-model="formProfile.name" :errors="formProfile.errors.name" label="Username" />
                    <InputGroup ref="passwordInput" v-model="formProfile.email" :errors="formProfile.errors.email" label="Email" />
                    <button :disabled="formProfile.processing" type="submit"
                        class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">Save
                        Changes
                    </button>
                </form>
            </div>
            <div class="p-6 w-full">
                <div class="dark:text-slate-100 mb-4 text-center text-lg"> Update password</div>
                <div v-if="passwordMessage" class="text-green-500 underline font-bold w-60">{{ passwordMessage }}</div>

                <form @submit.prevent="updatePassword" class="flex flex-col">
                    <InputGroup v-model="formPassword.current_password" :errors="formPassword.errors.current_password" type="password" label="Current password" />
                    <InputGroup v-model="formPassword.password" :errors="formPassword.errors.password" type="password" label="New password" />
                    <InputGroup v-model="formPassword.password_confirmation" :errors="formPassword.errors.password_confirmation" type="password"
                        label="Confirm password" />
                    <button :disabled="formPassword.processing" type="submit"
                        class="w-full bg-green-600 text-white font-bold text-sm uppercase rounded hover:bg-green-700 flex items-center justify-center px-2 py-3 mt-6 disabled:bg-green-200">Update
                        password
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
