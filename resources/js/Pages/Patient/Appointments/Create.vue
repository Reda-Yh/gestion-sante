<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    medecins: Array,
    appointment_at: String, // 👈 depuis le calendrier
})

const form = useForm({
    medecin_id: '',
    appointment_at: props.appointment_at ?? '',
    reason: '',
    type: 'presentiel',
    duration: 30,
    notes_patient: '',
})
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="space-y-1">
                <h2 class="text-3xl font-black text-gray-800 dark:text-white">
                    ➕ Prendre un rendez-vous
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Planifiez votre consultation en quelques secondes
                </p>
            </div>
        </template>

        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 p-6">

            <!-- LEFT INFO -->
            <div
                class="hidden md:flex flex-col justify-center
                       bg-gradient-to-br from-indigo-50 to-white
                       dark:from-indigo-900/20 dark:to-slate-900/40
                       rounded-[2.5rem] p-10 space-y-6 shadow-xl"
            >
                <h3 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400">
                    Votre santé avant tout 💙
                </h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Choisissez votre médecin, la date et le type de consultation.
                </p>

                <img
                    src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=700"
                    class="rounded-2xl shadow-2xl"
                    alt="Medical"
                />
            </div>

            <!-- FORM -->
            <div
                class="bg-white dark:bg-slate-900
                       rounded-[2.5rem] shadow-2xl p-8 space-y-6"
            >
                <form
                    @submit.prevent="form.post(route('patient.appointments.store'))"
                    class="space-y-5"
                >

                    <!-- Médecin -->
                    <div>
                        <label class="label">👨‍⚕️ Médecin</label>
                        <select v-model="form.medecin_id" class="input">
                            <option value="">-- Choisir un médecin --</option>
                            <option v-for="m in medecins" :key="m.id" :value="m.id">
                                {{ m.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.medecin_id" class="error">
                            {{ form.errors.medecin_id }}
                        </p>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="label">📅 Date & heure</label>
                        <input
                            type="datetime-local"
                            v-model="form.appointment_at"
                            :min="new Date().toISOString().slice(0,16)"
                            class="input"
                        />
                        <p v-if="form.errors.appointment_at" class="error">
                            {{ form.errors.appointment_at }}
                        </p>
                    </div>

                    <!-- Motif -->
                    <div>
                        <label class="label">🩺 Motif</label>
                        <input
                            v-model="form.reason"
                            placeholder="Ex : consultation de suivi"
                            class="input"
                        />
                        <p v-if="form.errors.reason" class="error">
                            {{ form.errors.reason }}
                        </p>
                    </div>

                    <!-- Type + durée -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">🔄 Type</label>
                            <select v-model="form.type" class="input">
                                <option value="presentiel">Présentiel</option>
                                <option value="en_ligne">En ligne</option>
                            </select>
                        </div>

                        <div>
                            <label class="label">⏱️ Durée (min)</label>
                            <input type="number" v-model="form.duration" class="input" />
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="label">📝 Message (optionnel)</label>
                        <textarea
                            v-model="form.notes_patient"
                            rows="3"
                            class="input"
                        />
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn-primary"
                        >
                            🚀 Confirmer
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.label {
    @apply block text-xs font-bold uppercase tracking-widest text-gray-500 mb-1;
}
.input {
    @apply w-full rounded-xl border-gray-300 dark:border-slate-700
           dark:bg-slate-950 dark:text-white py-3 px-4;
}
.error {
    @apply text-sm text-red-500 mt-1;
}
.btn-primary {
    @apply bg-indigo-600 hover:bg-indigo-700 text-white
           font-bold px-8 py-3 rounded-xl
           disabled:opacity-50;
}
</style>
