<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router, Head } from '@inertiajs/vue3'

defineProps({ invitations: Array })

const confirm = (id) => {
    router.patch(route('medecin.appointments.confirm', id), {}, { preserveScroll: true })
}

const cancel = (id) => {
    if (confirm('Refuser ce rendez-vous ?')) {
        router.patch(route('medecin.appointments.cancel', id), {}, { preserveScroll: true })
    }
}

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString('fr-FR', {
        weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit'
    })
}

// Calculer si la demande est récente (moins de 24h)
const isRecent = (createdAt) => {
    const diff = new Date() - new Date(createdAt)
    return diff < 24 * 60 * 60 * 1000
}
</script>

<template>
    <Head title="Invitations Patients" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/20">📩</div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">Demandes en attente</h2>
                    <p class="text-sm text-slate-500 font-bold uppercase tracking-tighter">À valider pour votre agenda</p>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto p-6">
            <div v-if="invitations.length > 0" class="grid gap-6">
                <div v-for="invite in invitations" :key="invite.id"
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm flex flex-col md:flex-row items-center gap-8 transition-all hover:shadow-xl group">

                    <div class="flex items-center gap-5 flex-1 w-full">
                        <div class="relative">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition duration-500">👤</div>
                            <span v-if="isRecent(invite.created_at)" class="absolute -top-2 -right-2 bg-rose-500 text-[8px] font-black text-white px-2 py-1 rounded-full uppercase tracking-tighter animate-bounce">Nouveau</span>
                        </div>
                        <div>
                            <h3 class="font-black text-xl text-slate-800 dark:text-white">{{ invite.patient.name }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-indigo-500 font-black text-[10px] uppercase tracking-widest">{{ invite.type }}</span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <span class="text-slate-500 dark:text-slate-400 font-bold text-[10px]">⏱ {{ invite.duration }} min</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left space-y-1 w-full bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Créneau demandé</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ formatDate(invite.appointment_at) }}</p>
                        <p class="text-xs text-slate-400 italic mt-2">"{{ invite.reason ?? 'Sans motif particulier' }}"</p>
                    </div>

                    <div class="flex gap-3 w-full md:w-auto">
                        <button @click="confirm(invite.id)"
                            class="flex-1 md:flex-none px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm transition shadow-lg shadow-emerald-500/20 active:scale-95">
                            Accepter
                        </button>
                        <button @click="cancel(invite.id)"
                            class="flex-1 md:flex-none px-8 py-3 bg-white dark:bg-slate-800 border border-rose-200 dark:border-rose-900 text-rose-600 rounded-xl font-black text-sm hover:bg-rose-50 transition">
                            Refuser
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-20 bg-white dark:bg-slate-900 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800 shadow-inner">
                <p class="text-6xl mb-6">✨</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white italic">Boîte de réception vide</h3>
                <p class="text-slate-500 font-medium">Tous les rendez-vous ont été traités.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
