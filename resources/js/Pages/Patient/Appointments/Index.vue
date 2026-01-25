<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import debounce from 'lodash/debounce'

const props = defineProps({
    appointments: Array,
    filters: Object,
})

// État des filtres
const search = ref(props.filters.search || '')
const status = ref(props.filters.status || '')
const type = ref(props.filters.type || '')
const date = ref(props.filters.date || '')

// Logique de filtrage
const updateFilters = () => {
    router.get(route('patient.appointments.index'), {
        search: search.value,
        status: status.value,
        type: type.value,
        date: date.value
    }, {
        preserveState: true,
        replace: true
    })
}

// Watchers pour déclencher la recherche (avec debounce pour le texte)
watch(search, debounce(() => updateFilters(), 500))
watch([status, type, date], () => updateFilters())

const resetFilters = () => {
    search.value = ''
    status.value = ''
    type.value = ''
    date.value = ''
}

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black tracking-tight text-slate-800 dark:text-slate-100">
                        📋 Mes Rendez-vous
                    </h2>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Gérez vos consultations et accédez à vos documents de santé.
                    </p>
                </div>
                <Link
                    :href="route('patient.calendar')"
                    class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 active:scale-95"
                >
                    + Prendre rendez-vous
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <div class="relative overflow-hidden bg-slate-900 dark:bg-indigo-950 rounded-[2.5rem] p-8 md:p-12 shadow-2xl border border-white/5">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="max-w-xl text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-6">
                            Espace Patient sécurisé
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight">
                            Votre santé en <span class="text-indigo-400">un seul endroit.</span>
                        </h3>
                        <p class="text-slate-300 text-lg leading-relaxed">
                            Suivez l'évolution de vos soins, retrouvez vos ordonnances et échangez avec vos praticiens.
                        </p>
                    </div>
                    <div class="hidden lg:flex relative h-48 w-48 items-center justify-center bg-white/5 backdrop-blur-2xl rounded-full border border-white/10 shadow-3xl">
                        <span class="text-6xl animate-pulse">🩺</span>
                        <div class="absolute inset-0 bg-indigo-500/20 rounded-full blur-2xl"></div>
                    </div>
                </div>
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px]"></div>
            </div>

            <div class="bg-white dark:bg-slate-900/50 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[250px]">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher un médecin..."
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white"
                        />
                    </div>
                    <select v-model="status" class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="confirme">Confirmé</option>
                        <option value="annule">Annulé</option>
                    </select>
                    <select v-model="type" class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white">
                        <option value="">Tous les types</option>
                        <option value="présentiel">Présentiel</option>
                        <option value="téléconsultation">Téléconsultation</option>
                    </select>
                    <input
                        v-model="date"
                        type="date"
                        class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white"
                    />
                    <button
                        @click="resetFilters"
                        class="p-2 text-slate-400 hover:text-indigo-600 transition-colors"
                        title="Réinitialiser les filtres"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-6">
                <h4 class="text-lg font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-tight">Liste des consultations</h4>
                <span class="px-4 py-1.5 bg-indigo-50 dark:bg-indigo-500/10 rounded-full text-xs font-black text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/20">
                    {{ appointments.length }} RDV trouvés
                </span>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="appointment in appointments"
                    :key="appointment.id"
                    class="group relative bg-white dark:bg-slate-900/40 dark:backdrop-blur-md rounded-[2rem] p-6 border border-slate-200 dark:border-slate-800/50 hover:border-indigo-500/50 transition-all duration-500 shadow-sm hover:shadow-2xl hover:-translate-y-2"
                >
                    <div class="flex flex-col h-full space-y-6">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 rounded-2xl bg-slate-50 dark:bg-slate-800/80 flex items-center justify-center text-2xl shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                                    👨‍⚕️
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ appointment.medecin.name }}
                                    </h3>
                                    <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 dark:text-slate-500">
                                        {{ appointment.type }}
                                    </p>
                                </div>
                            </div>

                            <span
                                :class="{
                                    'bg-amber-100/80 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400': appointment.status === 'en_attente',
                                    'bg-emerald-100/80 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400': appointment.status === 'confirme',
                                    'bg-rose-100/80 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400': appointment.status === 'annule',
                                }"
                                class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider"
                            >
                                {{ appointment.status.replace('_', ' ') }}
                            </span>
                        </div>

                        <div class="space-y-4 flex-grow">
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-transparent dark:border-slate-800 transition-colors group-hover:bg-white dark:group-hover:bg-slate-800">
                                <div class="text-indigo-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200 capitalize">
                                    {{ formatDate(appointment.appointment_at) }}
                                </span>
                            </div>

                            <div class="px-1">
                                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Motif du rendez-vous</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                                    {{ appointment.reason ?? 'Consultation de suivi habituelle' }}
                                </p>
                            </div>

                            <div v-if="appointment.cancel_reason" class="flex items-start gap-3 p-3 rounded-2xl bg-rose-50 dark:bg-rose-500/5 border border-rose-100 dark:border-rose-500/20">
                                <span class="text-sm">🚫</span>
                                <p class="text-xs text-rose-600 dark:text-rose-400 font-semibold italic">
                                    {{ appointment.cancel_reason }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 pt-5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <Link
                                :href="route('appointments.timeline', appointment.id)"
                                class="group/btn text-xs font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-2 transition-all uppercase tracking-widest"
                            >
                                <svg class="w-4 h-4 transition-transform group-hover/btn:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Historique
                            </Link>

                            <Link
                                v-if="appointment.medical_record"
                                :href="route('medical-records.show', appointment.medical_record.id)"
                                class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-600 hover:text-white dark:text-slate-200 rounded-xl text-xs font-black transition-all shadow-sm active:scale-95"
                            >
                                Dossier Médical
                            </Link>
                        </div>
                    </div>
                </div>

                <div
                    v-if="appointments.length === 0"
                    class="col-span-full flex flex-col items-center justify-center py-24 bg-white dark:bg-slate-900/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800 shadow-inner"
                >
                    <div class="w-24 h-24 bg-indigo-50 dark:bg-indigo-500/10 rounded-full flex items-center justify-center text-5xl mb-6 shadow-xl">
                        🍃
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100">Aucun résultat...</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 mb-8 text-center max-w-sm font-medium">
                        Essayez de modifier vos filtres ou prenez un nouveau rendez-vous.
                    </p>
                    <button
                        @click="resetFilters"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black shadow-lg hover:bg-indigo-700 transition-all"
                    >
                        Réinitialiser la recherche
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

:deep(body) {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.dark .group:hover {
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.8) 100%);
}
</style>
