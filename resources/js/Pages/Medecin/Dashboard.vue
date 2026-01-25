<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Notifications from '@/Components/Notifications.vue'
import WeeklyAppointmentsChart from '@/Components/WeeklyAppointmentsChart.vue'
import { Link, Head } from '@inertiajs/vue3'

defineProps({
    stats: Object,
    chart: Object,
})

const period = ref('week')
</script>

<template>
    <Head title="Tableau de Bord Médecin" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 w-full">
                <div class="flex flex-col gap-1">
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">
                        Espace <span class="text-indigo-600 dark:text-indigo-400">Praticien</span> 🩺
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">
                        Voici l'état actuel de votre cabinet et de votre patientèle.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="route('medecin.calendar')"
                        class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-6 py-3 rounded-2xl font-bold shadow-lg transition-all hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Ouvrir l'agenda
                    </Link>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto space-y-8 pb-12">

            <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 transition-all">
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                    <div class="flex-1 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                            Cabinet Ouvert
                        </div>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">
                            Prêt pour vos consultations, <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Docteur {{ $page.props.auth.user.name }}</span> ?
                        </h3>
                        <p class="text-slate-500 dark:text-slate-400 text-lg max-w-md">
                            Vous avez <span class="font-bold text-slate-800 dark:text-white">{{ stats.today }} patients</span> programmés pour aujourd'hui.
                        </p>
                    </div>
                    <div class="relative">
                        <div class="absolute -inset-4 bg-indigo-500/10 dark:bg-indigo-500/20 rounded-full blur-3xl"></div>
                        <img
                            src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=500"
                            alt="Médecin"
                            class="relative w-72 h-48 object-cover rounded-[2rem] shadow-2xl border-4 border-white dark:border-slate-800 transform -rotate-2"
                        />
                    </div>
                </div>
            </div>

            <Notifications />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="stat in [
                    { label: 'Aujourd\'hui', val: stats.today, sub: 'Consultations', color: 'blue', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
                    { label: 'Cette Semaine', val: stats.week, sub: 'Patients vus', color: 'emerald', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
                    { label: 'Annulations', val: stats.cancelled, sub: 'Taux : 2%', color: 'rose', icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' },
                    { label: 'Revenus (Est.)', val: stats.revenue + ' €', sub: 'Mois en cours', color: 'amber', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }
                ]" :key="stat.label"
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-[2rem] shadow-sm hover:shadow-md transition-all group">
                    <div :class="`inline-flex p-3 rounded-2xl mb-4 bg-${stat.color}-50 dark:bg-${stat.color}-500/10 text-${stat.color}-600 dark:text-${stat.color}-400 transition-transform group-hover:scale-110` ">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" /></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ stat.label }}</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-3xl font-black text-slate-800 dark:text-white">{{ stat.val }}</p>
                    </div>
                    <p class="text-xs font-medium text-slate-500 mt-1">{{ stat.sub }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-colors">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Analyse de l'Activité</h3>
                        <p class="text-slate-500 font-medium">Nombre de consultations par {{ period }}</p>
                    </div>

                    <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                        <button
                            v-for="p in [{id:'week', l:'Semaine'}, {id:'month', l:'Mois'}, {id:'year', l:'Année'}]"
                            :key="p.id"
                            @click="period = p.id"
                            :class="period === p.id
                                ? 'bg-white dark:bg-slate-700 shadow-md text-indigo-600 dark:text-indigo-300'
                                : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-400'"
                            class="px-5 py-2 rounded-lg text-sm font-black transition-all"
                        >
                            {{ p.l }}
                        </button>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-2 bg-slate-50/50 dark:bg-slate-950/30 rounded-3xl p-6 border border-slate-100 dark:border-slate-800/50">
                        <WeeklyAppointmentsChart
                            :labels="chart[period].labels"
                            :data="chart[period].data"
                        />
                    </div>

                    <div class="relative overflow-hidden rounded-[2rem] bg-indigo-600 group">
                        <img
                            src="https://images.unsplash.com/photo-1551076805-e1869033e561?q=80&w=500"
                            alt="Medical"
                            class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-110 transition duration-700"
                        />
                        <div class="relative z-10 p-8 h-full flex flex-col justify-end bg-gradient-to-t from-indigo-900 via-transparent text-white">
                            <h4 class="font-black text-xl mb-2">Conseil Data</h4>
                            <p class="text-sm text-indigo-100">Votre activité a augmenté de 12% par rapport au mois dernier. Pensez à vérifier vos créneaux de la semaine prochaine.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <Link v-for="link in [
                    { route: 'medecin.appointments.index', title: 'Mes Rendez-vous', desc: 'Confirmer ou reporter les visites', icon: 'calendar', color: 'indigo' },
                    { route: 'medecin.calendar', title: 'Calendrier Expert', desc: 'Gérer vos disponibilités H24', icon: 'planner', color: 'emerald' },
                    { route: 'dashboard', title: 'Dossiers Patients', desc: 'Accès sécurisé aux antécédents', icon: 'clipboard', color: 'blue' }
                ]" :key="link.title" :href="route(link.route)"
                    class="group bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100 dark:border-slate-800 flex flex-col items-center text-center">

                    <div :class="`mb-6 p-5 rounded-[2rem] bg-${link.color}-50 dark:bg-slate-800 group-hover:rotate-6 transition-transform duration-300` ">
                        <img :src="`https://img.icons8.com/color/96/${link.icon}.png`" class="w-14 h-14" />
                    </div>
                    <h3 class="font-black text-xl text-slate-800 dark:text-white tracking-tight mb-2">
                        {{ link.title }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ link.desc }}
                    </p>
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:deep(main) {
    font-family: 'Plus Jakarta Sans', sans-serif;
}
</style>
