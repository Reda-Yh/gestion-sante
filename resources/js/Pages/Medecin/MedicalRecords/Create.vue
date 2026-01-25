
<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
    appointment: Object,
})

const form = useForm({
    diagnostic: '',
    traitement: '',
    notes: '',
    amount: '',
    signature: '',
})

// ✍️ Signature
const canvas = ref(null)
const drawing = ref(false)

const startDraw = (e) => {
    const ctx = canvas.value.getContext('2d')
    ctx.beginPath()
    ctx.moveTo(e.offsetX, e.offsetY)
    drawing.value = true
}

const draw = (e) => {
    if (!drawing.value) return
    const ctx = canvas.value.getContext('2d')
    ctx.lineTo(e.offsetX, e.offsetY)
    ctx.stroke()
}

const stopDraw = () => {
    drawing.value = false
    form.signature = canvas.value.toDataURL()
}

const clearSignature = () => {
    const ctx = canvas.value.getContext('2d')
    ctx.clearRect(0, 0, canvas.value.width, canvas.value.height)
    form.signature = ''
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="space-y-1">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    🩺 Création du dossier médical
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Diagnostic, traitement, facturation et signature
                </p>
            </div>
        </template>

        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 p-6">

            <!-- 🖼️ VISUEL -->
            <div
                class="hidden md:flex flex-col justify-center
                       bg-blue-50 dark:bg-blue-900/30
                       rounded-3xl p-10 space-y-4"
            >
                <h3 class="text-2xl font-semibold text-blue-700 dark:text-blue-300">
                    Consultation médicale
                </h3>
                <p class="text-sm text-blue-600 dark:text-blue-400">
                    Document officiel visible par le patient et utilisé pour la facturation.
                </p>

                <img
                    src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=700&auto=format&fit=crop"
                    class="rounded-2xl shadow-xl"
                />
            </div>

            <!-- 🧾 FORM -->
            <div
                class="bg-white/80 dark:bg-gray-800/80
                       backdrop-blur
                       rounded-3xl shadow-2xl p-10 space-y-8"
            >
                <div class="bg-gray-100 dark:bg-gray-900 rounded-xl p-4 text-sm">
                    <p><strong>👤 Patient :</strong> {{ appointment.patient.name }}</p>
                    <p><strong>📅 RDV :</strong> {{ appointment.appointment_at }}</p>
                </div>

                <form
                    @submit.prevent="form.post(route('medecin.medical-records.store', appointment.id))"
                    class="space-y-6"
                >
                    <div>
                        <label class="label">🩺 Diagnostic</label>
                        <textarea v-model="form.diagnostic" rows="4" class="input" />
                        <p v-if="form.errors.diagnostic" class="error">{{ form.errors.diagnostic }}</p>
                    </div>

                    <div>
                        <label class="label">💊 Traitement</label>
                        <textarea v-model="form.traitement" rows="3" class="input" />
                    </div>

                    <div>
                        <label class="label">📝 Notes</label>
                        <textarea v-model="form.notes" rows="3" class="input" />
                    </div>

                    <!-- 💳 PRIX -->
                    <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl p-4">
                        <label class="label">💳 Montant (€)</label>
                        <input type="number" v-model="form.amount" class="input" />
                        <p v-if="form.errors.amount" class="error">{{ form.errors.amount }}</p>
                    </div>

                    <!-- ✍️ SIGNATURE -->
                    <div class="border-l-4 border-indigo-500 p-4 rounded-xl bg-white dark:bg-gray-900">
                        <label class="label">✍️ Signature du médecin</label>

                        <canvas
                            ref="canvas"
                            width="500"
                            height="150"
                            class="border rounded-xl bg-white dark:bg-gray-800 cursor-crosshair"
                            @mousedown="startDraw"
                            @mousemove="draw"
                            @mouseup="stopDraw"
                            @mouseleave="stopDraw"
                        ></canvas>

                        <div class="flex justify-between mt-2 text-sm">
                            <span v-if="form.signature" class="text-green-600">✔️ Signé</span>
                            <button type="button" @click="clearSignature" class="text-red-600 underline">
                                Effacer
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl shadow-lg"
                        >
                            💾 Enregistrer le dossier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.label {
    @apply block text-sm font-medium text-gray-700 dark:text-gray-300;
}
.input {
    @apply mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500;
}
.error {
    @apply text-red-600 text-sm mt-1;
}
</style>
