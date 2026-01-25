<script setup>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import frLocale from '@fullcalendar/core/locales/fr'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    events: Array,
})

const calendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    locale: frLocale,
    height: '75vh',
    nowIndicator: true,
    stickyHeaderDates: true,
    dayMaxEvents: 3,

    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek',
    },

    buttonText: {
        today: "Aujourd'hui",
        month: 'Mois',
        week: 'Semaine',
    },

    selectable: true,

    dateClick(info) {
        router.visit(route('patient.appointments.create'), {
            data: { appointment_at: info.dateStr },
        })
    },

    eventClick() {
        router.visit(route('patient.appointments.index'))
    },

    eventClassNames({ event }) {
        const status = event.extendedProps.status;
        return [
            'fc-event-custom',
            `status-${status || 'pending'}`
        ];
    },

    events: props.events,
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black tracking-tight text-slate-800 dark:text-white">
                        📅 Mon Agenda
                    </h2>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Gérez vos disponibilités et vos consultations à venir.
                    </p>
                </div>

                <button
                    @click="router.visit(route('patient.appointments.create'))"
                    class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm transition-all shadow-xl shadow-indigo-500/20 active:scale-95"
                >
                    + Nouveau RDV
                </button>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-6 ml-1">Légende</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                            <span class="w-4 h-4 rounded-full bg-amber-500 shadow-[0_0_12px_rgba(245,158,11,0.4)]"></span>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300">En attente</span>
                        </div>
                        <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                            <span class="w-4 h-4 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.4)]"></span>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Confirmé</span>
                        </div>
                        <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                            <span class="w-4 h-4 rounded-full bg-rose-500 shadow-[0_0_12px_rgba(244,63,94,0.4)]"></span>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Annulé</span>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-600 dark:bg-indigo-950 p-6 rounded-[2rem] text-white relative overflow-hidden group shadow-xl">
                    <div class="relative z-10">
                        <p class="text-xs font-black uppercase tracking-widest opacity-70 mb-2">Astuce pro</p>
                        <p class="text-sm leading-relaxed font-medium">
                            Cliquez directement sur une case vide pour gagner du temps lors de la prise de rendez-vous.
                        </p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl p-6 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
                    <FullCalendar :options="calendarOptions" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* --- FULLCALENDAR DARK THEME REINVENTION --- */

.fc {
    --fc-border-color: rgba(226, 232, 240, 0.8);
    --fc-button-text-color: #64748b;
    --fc-today-bg-color: rgba(79, 70, 229, 0.05);
    font-family: 'Inter', system-ui, sans-serif;
}

.dark .fc {
    --fc-border-color: rgba(51, 65, 85, 0.5);
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: rgba(15, 23, 42, 0.5);
    --fc-list-event-hover-bg-color: rgba(30, 41, 59, 0.5);
    --fc-today-bg-color: rgba(99, 102, 241, 0.1);
}

/* Titre du Calendrier */
.fc-toolbar-title {
    font-size: 1.25rem !important;
    font-weight: 900 !important;
    color: #1e293b;
    letter-spacing: -0.025em;
}
.dark .fc-toolbar-title { color: #f8fafc; }

/* Boutons Modernisés */
.fc-button {
    border-radius: 12px !important;
    font-weight: 800 !important;
    font-size: 0.8rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    padding: 10px 18px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.fc-button-primary {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #475569 !important;
}

.dark .fc-button-primary {
    background: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #cbd5e1 !important;
}

.fc-button-primary:hover {
    background: #f1f5f9 !important;
    transform: translateY(-1px);
}

.dark .fc-button-primary:hover {
    background: #334155 !important;
}

.fc-button-active {
    background: #4f46e5 !important;
    color: white !important;
    border-color: #4f46e5 !important;
    box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3) !important;
}

/* Grille et Jours */
.fc-theme-standard td, .fc-theme-standard th {
    border-color: var(--fc-border-color) !important;
}

.fc-col-header-cell {
    padding: 15px 0 !important;
    background: #f8fafc;
}
.dark .fc-col-header-cell { background: #0f172a; }

.fc-col-header-cell-cushion {
    text-decoration: none !important;
    color: #94a3b8 !important;
    font-size: 0.7rem;
    font-weight: 900;
}

/* Numéros de jours */
.fc-daygrid-day-number {
    font-weight: 700;
    font-size: 0.9rem;
    padding: 10px !important;
    color: #64748b;
    text-decoration: none !important;
}
.dark .fc-daygrid-day-number { color: #94a3b8; }

/* Style des événements */
.fc-event-custom {
    border: none !important;
    margin: 2px 4px !important;
    padding: 5px 10px !important;
    border-radius: 10px !important;
    font-size: 0.75rem !important;
    font-weight: 800 !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Couleurs dynamiques Thème Sombre */
.status-pending { background-color: #fef3c7 !important; color: #b45309 !important; border-left: 4px solid #f59e0b !important; }
.status-confirmed { background-color: #d1fae5 !important; color: #065f46 !important; border-left: 4px solid #10b981 !important; }
.status-cancelled { background-color: #fee2e2 !important; color: #991b1b !important; border-left: 4px solid #f43f5e !important; }

.dark .status-pending { background-color: rgba(245, 158, 11, 0.1) !important; color: #fbbf24 !important; }
.dark .status-confirmed { background-color: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; }
.dark .status-cancelled { background-color: rgba(244, 63, 94, 0.1) !important; color: #fb7185 !important; }

/* Masquer la bordure de scroll par défaut */
.fc-scrollgrid { border-radius: 2rem !important; overflow: hidden; border: none !important; }
</style>
