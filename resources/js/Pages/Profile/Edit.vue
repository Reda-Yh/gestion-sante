<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});
</script>

<template>
    <Head title="Mon Profil" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight italic">Paramètres du Profil</h2>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Gérez vos informations personnelles et votre sécurité.</p>
                </div>
            </div>
        </template>

        <div class="py-12 px-4">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-500/20">
                        <div class="relative z-10">
                            <h3 class="text-2xl font-black italic mb-2">Votre Santé, <br>Votre Contrôle.</h3>
                            <p class="text-indigo-100 text-sm leading-relaxed opacity-80">Gardez vos informations à jour pour un meilleur suivi médical.</p>
                        </div>
                        <div class="absolute -right-10 -bottom-10 opacity-20 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=400" class="mt-6 rounded-2xl h-32 w-full object-cover mix-blend-soft-light" alt="">
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-8">
                    <div class="bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none sm:rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800">
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                    </div>

                    <div class="bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none sm:rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800">
                        <UpdatePasswordForm />
                    </div>

                    <div class="bg-red-50/50 dark:bg-red-950/10 border border-red-100 dark:border-red-900/30 sm:rounded-[2.5rem] p-8">
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
