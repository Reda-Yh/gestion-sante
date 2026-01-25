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

const period = ref('year')
</script>

<template>
    <Head title="Mon Tableau de Bord" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 w-full">
                <div class="flex flex-col gap-1">
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none">
                        Bonjour, <span class="text-blue-600 dark:text-blue-500 italic">Patient</span> 👋
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">
                        Heureux de vous revoir. Voici le résumé de votre santé.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="route('patient.appointments.create')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black shadow-xl shadow-blue-500/20 dark:shadow-none transition-all flex items-center gap-2 transform hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                        Nouveau Rendez-vous
                    </Link>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto space-y-8 pb-12">

            <div class="transform transition-all duration-500 hover:translate-y-[-2px]">
                <Notifications />
            </div>

            <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 to-indigo-800 dark:from-blue-700 dark:to-slate-900 rounded-[2.5rem] p-8 lg:p-12 shadow-2xl shadow-blue-500/10 dark:shadow-none border border-white/10">
                <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12">
                    <div class="text-center lg:text-left space-y-6 max-w-xl">
                        <span class="inline-block bg-white/10 dark:bg-blue-500/20 text-white dark:text-blue-200 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-[0.2em] backdrop-blur-md border border-white/10">
                            Santé Connectée
                        </span>
                        <h3 class="text-3xl lg:text-5xl font-black text-white leading-[1.1]">
                            Votre historique médical à portée de main.
                        </h3>
                        <p class="text-blue-100/80 dark:text-slate-300 text-lg lg:text-xl leading-relaxed">
                            Consultez vos résultats, téléchargez vos factures et gérez vos rendez-vous en quelques clics.
                        </p>
                    </div>
                    <div class="relative group hidden sm:block">
                        <div class="absolute -inset-4 bg-white/10 rounded-[2rem] blur-2xl group-hover:bg-blue-400/20 transition duration-700"></div>
                        <img
                            src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?q=80&w=600"
                            alt="Health Data"
                            class="relative w-64 lg:w-96 rounded-[2rem] shadow-2xl transform lg:rotate-3 group-hover:rotate-0 transition duration-700 object-cover border-8 border-white/5"
                        />
                    </div>
                </div>
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-white/10 rounded-full blur-[100px]"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-400/20 rounded-full blur-[100px]"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="stat in [
                    { label: 'Prochain RDV', val: stats.nextAppointment ? new Date(stats.nextAppointment).toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'short' }) : 'Aucun', sub: stats.nextAppointment ? new Date(stats.nextAppointment).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'Libre', color: 'blue', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
                    { label: 'Total à venir', val: stats.upcoming, sub: 'Consultations', color: 'emerald', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
                    { label: 'En attente', val: stats.unpaidInvoices, sub: 'Factures impayées', color: 'rose', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }
                ]" :key="stat.label"
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50 p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div :class="`p-3 rounded-2xl transition-colors duration-500 ${isDark ? 'bg-slate-800 text-white' : 'bg-'+stat.color+'-50 text-'+stat.color+'-600'}`">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" /></svg>
                            </div>
                            <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ stat.label }}</p>
                        </div>
                        <p class="text-2xl font-black text-slate-800 dark:text-white mb-1 transition-colors">{{ stat.val }}</p>
                        <p :class="`text-sm font-bold text-${stat.color}-500 dark:text-${stat.color}-400`">{{ stat.sub }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 lg:p-10 shadow-sm border border-slate-100 dark:border-slate-800/50 transition-colors">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Activité Médicale</h3>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Répartition de vos visites par période</p>
                    </div>

                    <div class="flex p-1.5 bg-slate-100 dark:bg-slate-950 rounded-2xl border border-slate-200/50 dark:border-slate-800">
                        <button
                            v-for="p in [{id:'week', l:'Semaine'}, {id:'month', l:'Mois'}, {id:'year', l:'Année'}]"
                            :key="p.id"
                            @click="period = p.id"
                            :class="period === p.id
                                ? 'bg-white dark:bg-slate-800 shadow-xl text-blue-600 dark:text-blue-400 scale-100'
                                : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 scale-95 opacity-70'"
                            class="px-6 py-2.5 rounded-xl text-sm font-black transition-all duration-300"
                        >
                            {{ p.l }}
                        </button>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-12 items-center">
                    <div class="lg:col-span-2 bg-slate-50/50 dark:bg-slate-950/40 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800/50">
                        <WeeklyAppointmentsChart
                            :labels="chart[period].labels"
                            :data="chart[period].data"
                        />
                    </div>
                    <div class="relative overflow-hidden rounded-[2.5rem] h-full min-h-[300px] group shadow-2xl">
                        <img
                            src="https://images.unsplash.com/photo-1600959907703-125ba1374a12?q=80&w=500"
                            alt="Calendar"
                            class="absolute inset-0 w-full h-full object-cover transform scale-105 group-hover:scale-125 transition duration-[2000ms]"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/20 to-transparent flex items-end p-8">
                            <p class="text-white font-bold text-lg leading-snug">Restez informé de l'évolution de votre parcours de soin.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <Link v-for="action in [
                    { route: 'patient.appointments.create', label: 'Prendre RDV', img: 'plus', color: 'blue' },
                    { route: 'patient.appointments.index', label: 'Mes RDV', img: 'calendar', color: 'emerald' },
                    { route: 'patient.calendar', label: 'Calendrier', img: 'planner', color: 'purple' },
                    { route: 'patient.invoices.index', label: 'Factures', img: 'bill', color: 'orange' }
                ]" :key="action.label" :href="route(action.route)"
                    class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border border-slate-100 dark:border-slate-800/50 text-center flex flex-col items-center group overflow-hidden relative">

                    <div :class="`mb-6 p-5 rounded-3xl transition-all duration-500 group-hover:rotate-[10deg] ${isDark ? 'bg-slate-950' : 'bg-'+action.color+'-50'}`">
                        <img :src="`https://img.icons8.com/color/96/${action.img}.png`" class="w-14 h-14" />
                    </div>
                    <h3 class="font-black text-slate-800 dark:text-white text-lg tracking-tight">
                        {{ action.label }}
                    </h3>
                    <div class="absolute inset-0 bg-blue-500/5 dark:bg-blue-400/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
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

/* Animations d'entrée */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.max-w-7xl > div {
    animation: fadeInUp 0.6s ease-out forwards;
}

.max-w-7xl > div:nth-child(2) { animation-delay: 0.1s; }
.max-w-7xl > div:nth-child(3) { animation-delay: 0.2s; }
.max-w-7xl > div:nth-child(4) { animation-delay: 0.3s; }
</style>
