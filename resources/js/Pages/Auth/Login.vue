<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Connexion" />

        <div class="flex flex-col md:flex-row min-h-[600px] overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-2xl">

            <div class="hidden md:flex md:w-1/2 relative bg-indigo-600">
                <img
                    src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=800&auto=format&fit=crop"
                    alt="Medical Background"
                    class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay"
                />
                <div class="relative z-10 flex flex-col justify-center p-12 text-white">
                    <h1 class="text-4xl font-bold mb-4">Content de vous revoir ! 👋</h1>
                    <p class="text-indigo-100 text-lg">
                        Connectez-vous pour accéder à votre espace de santé sécurisé et gérer vos consultations.
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-600/80 to-transparent"></div>
            </div>

            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Connexion</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Saisissez vos identifiants ci-dessous</p>
                </div>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-xl border border-green-100">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="group">
                        <InputLabel for="email" value="Adresse Email" class="group-focus-within:text-indigo-600 transition-colors" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 border-transparent focus:bg-white transition-all duration-200"
                            v-model="form.email"
                            required
                            autofocus
                            placeholder="docteur@exemple.com"
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center">
                            <InputLabel for="password" value="Mot de passe" />
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-xs text-indigo-600 hover:text-indigo-500 font-semibold"
                            >
                                Oublié ?
                            </Link>
                        </div>
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 border-transparent focus:bg-white transition-all duration-200"
                            v-model="form.password"
                            required
                            placeholder="••••••••"
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <Checkbox name="remember" v-model:checked="form.remember" class="text-indigo-600 focus:ring-indigo-500" />
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 transition-colors">Se souvenir de moi</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <PrimaryButton
                            class="w-full justify-center py-3 text-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition-all shadow-lg shadow-indigo-200 dark:shadow-none"
                            :class="{ 'opacity-50 pointer-events-none': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Connexion...</span>
                            <span v-else>Se connecter</span>
                        </PrimaryButton>
                    </div>

                    <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                        Pas encore de compte ?
                        <Link :href="route('register')" class="text-indigo-600 font-bold hover:underline">
                            Créer un compte
                        </Link>
                    </p>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Effet de transition fluide pour les inputs */
input {
    @apply border-2 border-gray-100 dark:border-gray-700 px-4 py-3 rounded-xl;
}
input:focus {
    @apply border-indigo-500 ring-0 shadow-md transform -translate-y-0.5;
}
</style>
