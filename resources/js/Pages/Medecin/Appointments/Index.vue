<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router, Link, Head } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'
import debounce from 'lodash/debounce'

const props = defineProps({
    appointments: Array,
    filters: Object,
})

// --- État des filtres ---
const search = ref(props.filters.search || '')
const status = ref(props.filters.status || '')
const type = ref(props.filters.type || '')
const date = ref(props.filters.date || '')

const updateFilters = () => {
    router.get(route('medecin.appointments.index'), {
        search: search.value,
        status: status.value,
        type: type.value,
        date: date.value
    }, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    })
}

watch(search, debounce(() => updateFilters(), 500))
watch([status, type, date], () => updateFilters())

const resetFilters = () => {
    search.value = ''
    status.value = ''
    type.value = ''
    date.value = ''
}

const deleteAppointment = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')) {
        router.delete(route('medecin.appointments.destroy', id), {
            preserveScroll: true,
        })
    }
}

// Stats mises à jour (on retire la stat "en attente")
const stats = computed(() => ({
    total: props.appointments.length,
    confirmed: props.appointments.filter(a => a.status === 'confirme').length,
    cancelled: props.appointments.filter(a => a.status === 'annule').length
}))

const formatDate = (dateStr) => {
    if (!dateStr) return 'Non définie';
    const d = new Date(dateStr);
    return d.toLocaleDateString('fr-FR', {
        day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
    });
}
</script>

<template>
    <Head title="Consultations validées" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        🏥 Historique & Consultations
                    </h2>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Gérez vos rendez-vous confirmés et dossiers patients.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        :href="route('medecin.appointments.invitations')"
                        class="group inline-flex items-center gap-3 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 px-6 py-3 rounded-2xl border border-indigo-100 dark:border-indigo-500/20 hover:bg-indigo-100 transition-all font-bold"
                    >
                        <span class="relative">
                            📩
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-rose-500 rounded-full animate-ping"></span>
                        </span>
                        Invitations
                    </Link>

                    <Link
                        :href="route('medecin.calendar')"
                        class="group inline-flex items-center gap-3 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-6 py-3 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:border-indigo-500 transition-all font-bold"
                    >
                        📅 Calendrier
                    </Link>
                </div>
            </div>
        </template>

        <div class="p-6 space-y-8 max-w-7xl mx-auto">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-slate-900 p-6 rounded-[2rem] text-white shadow-xl">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Total Liste</p>
                    <p class="text-4xl font-black italic">{{ stats.total }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-emerald-500 text-xs font-black uppercase tracking-widest">Confirmés</p>
                    <p class="text-4xl font-black italic text-slate-900 dark:text-white">{{ stats.confirmed }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-rose-500 text-xs font-black uppercase tracking-widest">Annulés</p>
                    <p class="text-4xl font-black italic text-slate-900 dark:text-white">{{ stats.cancelled }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-4 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative flex items-center bg-slate-50 dark:bg-slate-800 rounded-2xl px-4 py-2">
                    <span class="mr-2">🔍</span>
                    <input v-model="search" type="text" placeholder="Patient..." class="w-full bg-transparent border-none focus:ring-0 text-sm font-bold dark:text-white" />
                </div>

                <select v-model="status" class="bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tous les états</option>
                    <option value="confirme">✅ Confirmé</option>
                    <option value="annule">❌ Annulé</option>
                </select>

                <select v-model="type" class="bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tous les types</option>
                    <option value="présentiel">🏥 Présentiel</option>
                    <option value="téléconsultation">💻 Téléconsultation</option>
                </select>

                <div class="flex gap-2">
                    <input v-model="date" type="date" class="flex-1 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold dark:text-white" />
                    <button @click="resetFilters" class="p-3 bg-slate-100 dark:bg-slate-800 rounded-xl hover:text-rose-500 transition">🔄</button>
                </div>
            </div>

            <div class="grid gap-8 md:grid-cols-2">
                <div
                    v-for="appointment in appointments"
                    :key="appointment.id"
                    class="group relative bg-white dark:bg-slate-900/50 rounded-[2rem] p-7 border border-slate-100 dark:border-slate-800 transition-all duration-500 hover:shadow-xl"
                >
                    <div class="relative space-y-6">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">👤</div>
                                <div>
                                    <h3 class="font-black text-xl text-slate-900 dark:text-white">{{ appointment.patient.name }}</h3>
                                    <p class="text-xs font-bold text-indigo-500 tracking-widest uppercase">{{ appointment.type }}</p>
                                </div>
                            </div>

                            <span
                                :class="{
                                    'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400': appointment.status === 'confirme',
                                    'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400': appointment.status === 'annule',
                                }"
                                class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] border border-transparent shadow-sm"
                            >
                                {{ appointment.status.replace('_', ' ') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-100 dark:border-slate-800/50">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Heure</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ formatDate(appointment.appointment_at) }}</p>
                            </div>
                            <div class="space-y-1 text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Durée</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">⏱ {{ appointment.duration }} min</p>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Motif</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 font-medium line-clamp-2 italic">"{{ appointment.reason ?? 'Aucun motif' }}"</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <Link
                                v-if="appointment.status === 'confirme' && !appointment.medical_record"
                                :href="route('medecin.medical-records.create', appointment.id)"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-xl text-xs font-black uppercase text-center transition-all shadow-lg shadow-emerald-500/20"
                            >
                                ➕ Dossier Médical
                            </Link>

                            <Link
                                v-if="appointment.medical_record"
                                :href="route('medical-records.show', appointment.medical_record.id)"
                                class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-4 py-3 rounded-xl text-xs font-black uppercase text-center hover:bg-slate-200 transition-all"
                            >
                                🩺 Voir Dossier
                            </Link>

                            <div class="flex gap-2">
                                <button
                                    v-if="!appointment.medical_record && appointment.status === 'annule'"
                                    @click="deleteAppointment(appointment.id)"
                                    class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-rose-500 hover:text-white transition"
                                >
                                    🗑️
                                </button>
                                <Link
                                    :href="route('appointments.timeline', appointment.id)"
                                    class="p-3 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl hover:bg-indigo-100 transition"
                                >
                                    🕒
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.transition-all { transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); }
select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236366f1'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5em; padding-right: 2.5rem; }
</style>
