<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue' // Vérifie bien le chemin

const props = defineProps({
    appointment: Object,
    events: Array,
})
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight italic">
                        🕒 Historique & Timeline
                    </h2>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Suivi détaillé des actions pour le rendez-vous #{{ appointment?.id ?? '...' }}
                    </p>
                </div>

                <div class="flex -space-x-2">
                    <img class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-900 shadow-sm" src="https://ui-avatars.com/api/?name=Doc&background=6366f1&color=fff" alt="">
                    <img v-if="appointment?.patient" class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-900 shadow-sm" :src="'https://ui-avatars.com/api/?name=' + appointment.patient.name" alt="">
                </div>
            </div>
        </template>

        <div class="p-6 max-w-6xl mx-auto">
            <div v-if="appointment" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl">🩺</div>

                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400 mb-6">Patient concerné</h3>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl flex items-center justify-center text-2xl">
                                    👤
                                </div>
                                <div>
                                    <p class="text-xl font-black text-slate-800 dark:text-white">
                                        {{ appointment.patient?.name ?? 'Chargement...' }}
                                    </p>
                                    <p class="text-sm font-bold text-slate-400">{{ appointment.type }}</p>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-400 font-bold uppercase text-[10px]">Date du RDV</span>
                                    <span class="text-slate-700 dark:text-slate-200 font-bold">{{ appointment.appointment_at }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-400 font-bold uppercase text-[10px]">Statut actuel</span>
                                    <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 rounded text-[10px] font-black uppercase italic">
                                        {{ appointment.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] overflow-hidden shadow-lg h-48 relative group">
                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=500" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="">
                        <div class="absolute inset-0 bg-indigo-600/20 mix-blend-multiply"></div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100 dark:border-slate-800">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white italic mb-10">Journal d'activité</h3>

                        <div class="relative">
                            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-100 dark:bg-slate-800"></div>

                            <div class="space-y-12">
                                <div
                                    v-for="(event, index) in events"
                                    :key="index"
                                    class="relative pl-16 group"
                                >
                                    <div class="absolute left-0 w-12 h-12 bg-white dark:bg-slate-800 border-4 border-slate-50 dark:border-slate-900 rounded-2xl flex items-center justify-center text-xl shadow-md group-hover:scale-110 group-hover:border-indigo-500 transition-all duration-300 z-10">
                                        {{ event.icon }}
                                    </div>

                                    <div class="space-y-1">
                                        <div class="flex flex-col md:flex-row md:items-center gap-2">
                                            <p class="font-black text-lg text-slate-800 dark:text-slate-100 tracking-tight">
                                                {{ event.label }}
                                            </p>
                                            <span class="hidden md:block text-slate-300 dark:text-slate-700">•</span>
                                            <p class="text-xs font-black text-indigo-500 uppercase tracking-widest">
                                                {{ new Date(event.date).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                            </p>
                                        </div>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                            Le {{ new Date(event.date).toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="events && events.length === 0" class="text-center py-10">
                            <p class="text-slate-400 font-bold italic">Aucun événement enregistré pour le moment.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="flex flex-col items-center justify-center py-20 text-slate-400">
                <div class="animate-spin text-4xl mb-4">⌛</div>
                <p class="font-bold italic">Chargement de l'historique...</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
