<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router } from '@inertiajs/vue3'

defineProps({
    invoices: Array,
})

// Variable réactive pour contrôler l'affichage de l'alerte
const confirmingPayment = ref(null)

const openConfirmModal = (invoice) => {
    confirmingPayment.value = invoice
}

const closeConfirmModal = () => {
    confirmingPayment.value = null
}

const payInvoice = () => {
    if (!confirmingPayment.value) return

    router.patch(
        route('patient.invoices.pay', confirmingPayment.value.id),
        {}, // ici tu passes les données si besoin, sinon {}
        {
            preserveScroll: true,
            onSuccess: () => {
                closeConfirmModal()
            },
        }
    )
}

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="space-y-1">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    💳 Mes factures
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Consultez et réglez vos paiements en toute sécurité
                </p>
            </div>
        </template>

        <div class="p-6 space-y-8">

            <div class="flex flex-col md:flex-row gap-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl p-6 shadow-xl">
                <div class="flex-1">
                    <p class="text-sm opacity-80 uppercase font-bold tracking-tight">Factures totales</p>
                    <p class="text-3xl font-black italic">{{ invoices.length }}</p>
                </div>
                <div class="flex-1">
                    <p class="text-sm opacity-80 uppercase font-bold tracking-tight">Impayées</p>
                    <p class="text-3xl font-black italic text-red-200">
                        {{ invoices.filter(i => i.status === 'unpaid').length }}
                    </p>
                </div>
                <div class="flex-1">
                    <p class="text-sm opacity-80 uppercase font-bold tracking-tight">Payées</p>
                    <p class="text-3xl font-black italic text-green-200">
                        {{ invoices.filter(i => i.status === 'paid').length }}
                    </p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="invoice in invoices"
                    :key="invoice.id"
                    class="relative group bg-white dark:bg-gray-900 rounded-2xl p-6 border border-transparent hover:border-indigo-500 transition-all duration-300 shadow-lg"
                >
                    <div class="relative space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">🧾 Facture #{{ invoice.id }}</h3>
                            <span
                                :class="invoice.status === 'unpaid'
                                    ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'
                                    : 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'"
                                class="px-3 py-1 rounded-full text-xs font-black uppercase"
                            >
                                {{ invoice.status === 'unpaid' ? 'Impayée' : 'Payée' }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <p>📅 RDV : <span class="font-bold text-gray-700 dark:text-gray-200">{{ invoice.appointment.appointment_at }}</span></p>
                            <p>💰 Montant : <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ invoice.amount }} €</span></p>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button
                                v-if="invoice.status === 'unpaid'"
                                @click="openConfirmModal(invoice)"
                                class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold shadow-lg transition active:scale-95"
                            >
                                💳 Payer
                            </button>
                            <a
                                :href="route('patient.invoices.pdf', invoice.id)"
                                target="_blank"
                                class="flex-1 bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2.5 rounded-xl font-bold transition text-center"
                            >
                                📄 PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <Transition name="fade">
                <div v-if="confirmingPayment" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="closeConfirmModal"></div>

                    <div class="relative bg-white dark:bg-gray-900 w-full max-w-md rounded-[2rem] p-8 shadow-2xl border border-gray-100 dark:border-gray-800">
                        <div class="text-center space-y-4">
                            <div class="mx-auto w-16 h-16 bg-green-100 dark:bg-green-500/10 rounded-full flex items-center justify-center text-2xl">
                                💳
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight italic">Confirmer le paiement</h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                Voulez-vous régler la facture <span class="font-bold text-indigo-600">#{{ confirmingPayment.id }}</span> ?<br>
                                Le montant de <span class="font-black text-gray-900 dark:text-white">{{ confirmingPayment.amount }} €</span> sera validé.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 mt-8">
                            <button
                                @click="payInvoice"
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-green-500/20 transition active:scale-95"
                            >
                                ✅ Oui, payer maintenant
                            </button>
                            <button
                                @click="closeConfirmModal"
                                class="w-full text-gray-400 font-bold text-sm py-2 hover:text-gray-600 dark:hover:text-gray-300 transition"
                            >
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Animations fluides pour l'alerte */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.fade-enter-active .relative { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.fade-enter-from .relative { transform: scale(0.9) translateY(10px); }
</style>
