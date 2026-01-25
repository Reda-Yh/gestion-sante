<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { ref, watch, onMounted, onUnmounted } from 'vue'

// 🔔 Notifications depuis Inertia
const notifications = ref(usePage().props.notifications || [])

// 🔄 Sync si Inertia change
watch(
    () => usePage().props.notifications,
    (newVal) => {
        notifications.value = [...newVal]
    }
)

// ✅ Marquer comme lu (optimistic UI)
const markAsRead = (id) => {
    // Retirer immédiatement côté UI
    notifications.value = notifications.value.filter(n => n.id !== id)

    // Appel backend
    router.post(route('notifications.read', id), {}, {
        preserveScroll: true,
        preserveState: true,
    })
}

// 🔁 POLLING : récupérer nouvelles notifications automatiquement
let intervalId = null

onMounted(() => {
    intervalId = setInterval(() => {
        router.reload({
            only: ['notifications'], // 👈 IMPORTANT
            preserveScroll: true,
            preserveState: true,
        })
    }, 5000) // ⏱ toutes les 5 secondes
})

onUnmounted(() => {
    clearInterval(intervalId)
})
</script>

<template>
    <div v-if="notifications.length" class="space-y-2 mb-4">
        <div
            v-for="n in notifications"
            :key="n.id"
            class="bg-blue-50 border border-blue-200 p-3 rounded flex justify-between text-sm"
        >
            <span>🔔 {{ n.data.message }}</span>
            <button
                @click="markAsRead(n.id)"
                class="text-xs text-blue-600 underline"
            >
                Marquer comme lu
            </button>
        </div>
    </div>

    <p v-else class="text-sm text-gray-500">
        Aucune notification
    </p>
</template>
