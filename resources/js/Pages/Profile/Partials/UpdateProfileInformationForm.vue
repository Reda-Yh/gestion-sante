<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({ mustVerifyEmail: Boolean, status: String });

const user = usePage().props.auth.user;
const form = useForm({ name: user.name, email: user.email });
</script>

<template>
    <section>
        <header class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600">👤</div>
            <div>
                <h2 class="text-xl font-black text-slate-800 dark:text-white italic">Informations Personnelles</h2>
                <p class="text-sm text-slate-500">Mettez à jour vos coordonnées publiques.</p>
            </div>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6 max-w-2xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <InputLabel for="name" value="Nom Complet" class="dark:text-slate-300 font-bold ml-1" />
                    <TextInput id="name" type="text" class="block w-full dark:bg-slate-800 dark:border-slate-700" v-model="form.name" required autofocus />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="email" value="Adresse Email" class="dark:text-slate-300 font-bold ml-1" />
                    <TextInput id="email" type="email" class="block w-full dark:bg-slate-800 dark:border-slate-700" v-model="form.email" required />
                    <InputError :message="form.errors.email" />
                </div>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-800">
                <p class="text-sm text-amber-800 dark:text-amber-400 font-medium">Votre email n'est pas vérifié.</p>
                <Link :href="route('verification.send')" method="post" as="button" class="text-xs font-black uppercase tracking-widest text-indigo-600 mt-2 hover:underline">Renvoyer le lien</Link>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <PrimaryButton :disabled="form.processing">Enregistrer les modifications</PrimaryButton>
                <Transition enter-active-class="transition duration-500" enter-from-class="opacity-0 translate-x-2">
                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-emerald-500 uppercase italic">C'est noté ! ✨</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
