<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Inscription" />

        <div class="flex flex-col md:flex-row min-h-[700px] overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-2xl">

            <div class="hidden md:flex md:w-1/2 relative bg-emerald-600">
                <img
                    src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?q=80&w=800&auto=format&fit=crop"
                    alt="Registration Background"
                    class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay"
                />
                <div class="relative z-10 flex flex-col justify-center p-12 text-white">
                    <h1 class="text-4xl font-bold mb-4">Rejoignez-nous ! ✨</h1>
                    <p class="text-emerald-50 text-lg leading-relaxed">
                        Créez votre compte en quelques secondes et commencez à gérer vos rendez-vous médicaux en toute simplicité.
                    </p>
                    <div class="mt-8 flex items-center gap-4 text-emerald-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 backdrop-blur-md">🛡️</span>
                        <p class="text-sm">Données sécurisées et conformes RGPD</p>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white dark:bg-gray-800">
                <div class="mb-6 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Créer un compte</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Bienvenue sur votre espace santé</p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="group">
                        <InputLabel for="name" value="Nom Complet" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 border-transparent focus:bg-white transition-all duration-200"
                            v-model="form.name"
                            required
                            autofocus
                            placeholder="Jean Dupont"
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Adresse Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 border-transparent focus:bg-white transition-all duration-200"
                            v-model="form.email"
                            required
                            placeholder="jean.dupont@exemple.com"
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="password" value="Mot de passe" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 border-transparent focus:bg-white transition-all duration-200"
                                v-model="form.password"
                                required
                                placeholder="••••••••"
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Confirmation" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 border-transparent focus:bg-white transition-all duration-200"
                                v-model="form.password_confirmation"
                                required
                                placeholder="••••••••"
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>
                    </div>

                    <div class="pt-4 space-y-4">
                        <PrimaryButton
                            class="w-full justify-center py-3 text-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 transition-all shadow-lg shadow-emerald-100 dark:shadow-none border-none"
                            :class="{ 'opacity-50 pointer-events-none': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Création en cours...</span>
                            <span v-else>S'inscrire</span>
                        </PrimaryButton>

                        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                            Déjà inscrit ?
                            <Link :href="route('login')" class="text-emerald-600 font-bold hover:underline">
                                Se connecter
                            </Link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
input {
    @apply border-2 border-gray-100 dark:border-gray-700 px-4 py-3 rounded-xl;
}
input:focus {
    @apply border-emerald-500 ring-0 shadow-md transform -translate-y-0.5 outline-none;
}
</style>
