<script setup>
import { computed } from 'vue'
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

const stats = computed(() => {
    return {
        total: props.events.length,
        pending: props.events.filter(e => (e.extendedProps?.status || e.status) === 'pending').length,
        confirmed: props.events.filter(e => (e.extendedProps?.status || e.status) === 'confirmed').length,
    }
})

const calendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    locale: frLocale,
    slotMinTime: '07:00:00',
    slotMaxTime: '20:00:00',
    allDaySlot: false,
    height: 'auto',
    nowIndicator: true,
    selectable: true,
    editable: true,
    eventStartEditable: true,
    eventDurationEditable: true,

    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridWeek,timeGridDay,dayGridMonth',
    },

    buttonText: {
        today: "Aujourd'hui",
        month: 'Mois',
        week: 'Semaine',
        day: 'Jour'
    },

    eventDragStart: (info) => {
        info.el.style.opacity = '0.6';
        info.el.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.4)';
    },

    eventDrop(info) {
        info.el.style.opacity = '1';
        if (info.event.extendedProps.status === 'cancelled') {
            info.revert()
            return
        }

        router.patch(
            route('medecin.appointments.update-time', info.event.id),
            { appointment_at: info.event.startStr },
            {
                preserveScroll: true,
                onError: () => info.revert()
            }
        )
    },

    eventClick(info) {
        router.visit(route('medecin.appointments.index', { id: info.event.id }))
    },

    eventClassNames({ event }) {
        const status = event.extendedProps.status || 'pending';
        return ['fc-event-medical', `status-${status}`];
    },

    events: props.events,
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white flex items-center gap-4 tracking-tight">
                        <span class="flex items-center justify-center w-14 h-14 bg-indigo-600 rounded-2xl text-white shadow-xl shadow-indigo-500/30 text-2xl">
                            📅
                        </span>
                        Planning Médical
                    </h2>
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-2 ml-1">
                        Gestion optimisée des consultations en temps réel.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-black text-slate-700 dark:text-slate-200 shadow-sm hover:shadow-md hover:bg-slate-50 dark:hover:bg-slate-750 transition-all active:scale-95">
                        🖨️ Imprimer
                    </button>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <div class="xl:col-span-1 space-y-6">
                <div class="grid grid-cols-2 xl:grid-cols-1 gap-4">
                    <div class="bg-emerald-50/50 dark:bg-emerald-500/5 p-6 rounded-[2.5rem] border border-emerald-100 dark:border-emerald-500/10 shadow-sm">
                        <p class="text-[10px] font-black text-emerald-600/70 dark:text-emerald-400/60 uppercase tracking-widest mb-1">Confirmés</p>
                        <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">{{ stats.confirmed }}</p>
                    </div>
                    <div class="bg-amber-50/50 dark:bg-amber-500/5 p-6 rounded-[2.5rem] border border-amber-100 dark:border-amber-500/10 shadow-sm">
                        <p class="text-[10px] font-black text-amber-600/70 dark:text-amber-400/60 uppercase tracking-widest mb-1">En attente</p>
                        <p class="text-4xl font-black text-amber-600 dark:text-amber-400 tracking-tighter">{{ stats.pending }}</p>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-slate-900/50 backdrop-blur-xl p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-8">Statuts</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 ring-4 ring-emerald-500/10 dark:ring-emerald-500/5"></span>
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Confirmé</span>
                            </div>
                            <span class="text-[9px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2.5 py-1 rounded-lg font-black uppercase">Validé</span>
                        </div>
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <span class="w-3.5 h-3.5 rounded-full bg-amber-500 ring-4 ring-amber-500/10 dark:ring-amber-500/5"></span>
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">En attente</span>
                            </div>
                            <span class="text-[9px] bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 px-2.5 py-1 rounded-lg font-black uppercase">Action</span>
                        </div>
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <span class="w-3.5 h-3.5 rounded-full bg-rose-500 ring-4 ring-rose-500/10 dark:ring-rose-500/5"></span>
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Annulé</span>
                            </div>
                            <span class="text-[9px] bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 px-2.5 py-1 rounded-lg font-black uppercase">Refusé</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-3">
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-3xl rounded-[3rem] border border-slate-200 dark:border-slate-800 shadow-2xl p-6 sm:p-10 transition-all ring-1 ring-white/5">
                    <FullCalendar :options="calendarOptions" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* --- FULLCALENDAR DARK THEME ENGINE --- */

.fc {
    --fc-border-color: rgba(226, 232, 240, 0.6);
    --fc-today-bg-color: rgba(79, 70, 229, 0.04);
    --fc-now-indicator-color: #ef4444;
    font-family: inherit;
}

.dark .fc {
    --fc-border-color: rgba(51, 65, 85, 0.5);
    --fc-today-bg-color: rgba(99, 102, 241, 0.08);
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: transparent;
}

/* Header UI */
.fc .fc-col-header-cell {
    padding: 24px 0 !important;
    background: #f8fafc;
}
.dark .fc .fc-col-header-cell { background: #0f172a; }

.fc-col-header-cell-cushion {
    font-size: 0.7rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #64748b;
    text-decoration: none !important;
}
.dark .fc-col-header-cell-cushion { color: #94a3b8; }

/* Grid & Time Labels */
.fc-timegrid-slot-label-cushion {
    font-size: 0.75rem !important;
    font-weight: 800 !important;
    color: #94a3b8 !important;
    text-transform: uppercase;
}

.fc-timegrid-slot {
    height: 4.5rem !important; /* Spacieux pour le confort tactile/souris */
    border-bottom: 1px dashed var(--fc-border-color) !important;
}

/* Medical Events Style */
.fc-event-medical {
    border: none !important;
    border-radius: 14px !important;
    margin: 3px !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
}

.fc-event-medical:hover {
    transform: scale(1.02) translateY(-2px);
    z-index: 10;
}

.fc-event-main {
    padding: 10px !important;
    font-weight: 800 !important;
    font-size: 0.8rem !important;
}

/* Statuts Dynamiques */
.status-confirmed { background: #f0fdf4 !important; border-left: 6px solid #10b981 !important; color: #166534 !important; }
.status-pending { background: #fffbeb !important; border-left: 6px solid #f59e0b !important; color: #92400e !important; }
.status-cancelled { background: #fef2f2 !important; border-left: 6px solid #ef4444 !important; color: #991b1b !important; }

.dark .status-confirmed { background: rgba(16, 185, 129, 0.12) !important; color: #34d399 !important; }
.dark .status-pending { background: rgba(245, 158, 11, 0.12) !important; color: #fbbf24 !important; }
.dark .status-cancelled { background: rgba(239, 68, 68, 0.12) !important; color: #f87171 !important; }

/* Navigation Buttons */
.fc-button-primary {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #475569 !important;
    border-radius: 16px !important;
    font-weight: 900 !important;
    font-size: 0.75rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    padding: 12px 20px !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
}

.dark .fc-button-primary {
    background: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #cbd5e1 !important;
}

.fc-button-primary:hover {
    background: #f1f5f9 !important;
}

.dark .fc-button-primary:hover {
    background: #334155 !important;
}

.fc-button-active {
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: white !important;
    box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4) !important;
}

/* Aujourd'hui (Bouton et indicateur) */
.fc-today-button { opacity: 1 !important; }
.fc-now-indicator { border-color: #ef4444 !important; border-width: 2px !important; }

/* Borders & Grid Layout Cleanup */
.fc-scrollgrid { border: none !important; border-radius: 2.5rem !important; overflow: hidden !important; }
.fc-theme-standard td, .fc-theme-standard th { border-color: var(--fc-border-color) !important; }
</style>
