<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const form = useForm({ current_password: '', password: '', password_confirmation: '' });

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) { form.reset('password', 'password_confirmation'); passwordInput.value.focus(); }
            if (form.errors.current_password) { form.reset('current_password'); currentPasswordInput.value.focus(); }
        },
    });
};
</script>

<template>
    <section>
        <header class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-600">🔐</div>
            <div>
                <h2 class="text-xl font-black text-slate-800 dark:text-white italic">Mot de Passe</h2>
                <p class="text-sm text-slate-500">Utilisez un mot de passe fort pour protéger votre accès.</p>
            </div>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6 max-w-2xl">
            <div class="space-y-2">
                <InputLabel for="current_password" value="Mot de passe actuel" class="dark:text-slate-300 font-bold" />
                <TextInput id="current_password" ref="currentPasswordInput" v-model="form.current_password" type="password" class="block w-full dark:bg-slate-800 border-slate-200 dark:border-slate-700" />
                <InputError :message="form.errors.current_password" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <InputLabel for="password" value="Nouveau mot de passe" class="dark:text-slate-300 font-bold" />
                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password" class="block w-full dark:bg-slate-800" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="password_confirmation" value="Confirmation" class="dark:text-slate-300 font-bold" />
                    <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="block w-full dark:bg-slate-800" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <PrimaryButton :disabled="form.processing">Mettre à jour la sécurité</PrimaryButton>
                <Transition enter-active-class="transition duration-500" enter-from-class="opacity-0 translate-x-2">
                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-emerald-500 uppercase italic">Sécurisé avec succès ! 🔒</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
