<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    duration: {
        type: Number,
        default: 5000, // Augmenté légèrement pour laisser le temps de lire
    },
})

const page = usePage()
const visible = ref(false)
const message = ref('')
let timeoutId = null

// 🔔 Détecter nouvelle notification
watch(
    () => page.props.notifications,
    (newNotifications, oldNotifications) => {
        if (
            newNotifications &&
            newNotifications.length > (oldNotifications?.length || 0)
        ) {
            const newest = newNotifications[0]
            message.value = newest.data.message
            visible.value = true

            if (timeoutId) clearTimeout(timeoutId)
            timeoutId = setTimeout(() => {
                visible.value = false
            }, props.duration)
        }
    },
    { deep: true }
)
</script>

<template>
    <transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="transform translate-y-10 opacity-0 scale-90"
        enter-to-class="transform translate-y-0 opacity-100 scale-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="visible"
            class="fixed bottom-8 right-8 z-[100] max-w-md w-full sm:w-auto"
        >
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-1000"></div>

                <div class="relative flex items-center gap-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200 dark:border-slate-700/50 p-4 rounded-2xl shadow-2xl">

                    <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <p class="text-xs font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-0.5">Notification</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-tight">
                            {{ message }}
                        </p>
                    </div>

                    <button @click="visible = false" class="p-1 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>
