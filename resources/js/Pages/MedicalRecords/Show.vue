<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    medicalRecord: Object,
    role: String,
})

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>

<template>
    <Head title="Dossier Médical" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black tracking-tight text-slate-800 dark:text-slate-100">
                        🩺 Dossier Médical
                    </h2>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Récapitulatif officiel de votre consultation santé
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-widest rounded-2xl ring-1 ring-emerald-500/20">
                        Document Certifié
                    </span>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

            <div class="relative overflow-hidden bg-slate-900 dark:bg-indigo-950 rounded-[2.5rem] p-8 shadow-2xl border border-white/5">
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-1 text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase tracking-[0.2em] mb-4">
                            Informations Protégées
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-white mb-3">
                            Rapport de <span class="text-indigo-400">Consultation</span>
                        </h3>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Ce document fait foi de votre état de santé et des recommandations émises par votre praticien lors de votre visite.
                        </p>
                    </div>

                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                        <img
                            src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=500"
                            alt="Medical Analysis"
                            class="relative rounded-2xl shadow-2xl w-64 h-40 object-cover border border-white/10"
                        />
                    </div>
                </div>
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-slate-900/40 dark:backdrop-blur-md rounded-[2rem] p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h4 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-6">Récapitulatif</h4>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-xl">👤</div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Patient</p>
                                    <p class="font-bold text-slate-800 dark:text-slate-200">{{ medicalRecord.patient.name }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-xl">👨‍⚕️</div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Médecin Praticien</p>
                                    <p class="font-bold text-slate-800 dark:text-slate-200">{{ medicalRecord.medecin.name }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-xl">📅</div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Date de l'acte</p>
                                    <p class="font-bold text-slate-800 dark:text-slate-200">{{ formatDate(medicalRecord.appointment.appointment_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="medicalRecord.signature" class="bg-indigo-50/50 dark:bg-indigo-500/5 rounded-[2rem] p-6 border border-indigo-100 dark:border-indigo-500/10">
                        <h4 class="text-xs font-black text-indigo-400 dark:text-indigo-400 uppercase tracking-widest mb-4">Validation Numérique</h4>
                        <div class="bg-white rounded-2xl p-4 mb-4 shadow-inner">
                            <img :src="medicalRecord.signature" alt="Signature" class="w-full h-auto mix-blend-multiply opacity-80" />
                        </div>
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold text-xs uppercase">
                            <span>✔️ Certifié authentique</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">

                    <div class="group bg-white dark:bg-slate-900/40 dark:backdrop-blur-md rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:border-rose-500/30">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-500/10 text-rose-500 text-xl font-bold">🩺</span>
                            <h3 class="text-lg font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight">Diagnostic</h3>
                        </div>
                        <div class="prose prose-slate dark:prose-invert max-w-none">
                            <p class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line font-medium">
                                {{ medicalRecord.diagnostic }}
                            </p>
                        </div>
                    </div>

                    <div v-if="medicalRecord.traitement" class="group bg-white dark:bg-slate-900/40 dark:backdrop-blur-md rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:border-emerald-500/30">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 text-xl font-bold">💊</span>
                            <h3 class="text-lg font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight">Traitement Préscrit</h3>
                        </div>
                        <div class="prose prose-slate dark:prose-invert max-w-none">
                            <p class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line font-medium">
                                {{ medicalRecord.traitement }}
                            </p>
                        </div>
                    </div>

                    <div v-if="medicalRecord.notes" class="bg-amber-50/30 dark:bg-slate-900/40 rounded-[2.5rem] p-8 border border-amber-100 dark:border-slate-800 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 dark:bg-amber-500/10 text-amber-600 text-xl font-bold">📝</span>
                            <h3 class="text-lg font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight">Notes du praticien</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed italic whitespace-pre-line">
                            {{ medicalRecord.notes }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="pt-10 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-widest">
                    <span>Accès sécurisé pour :</span>
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-slate-600 dark:text-slate-300">{{ role }}</span>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <a
                        :href="route('medical-records.pdf', medicalRecord.id)"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-4 rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none transition-all active:scale-95"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Exporter en PDF
                    </a>
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
</style>
