<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import Toast from '@/Components/Toast.vue'

const page = usePage()
const showingNavigationDropdown = ref(false)

// 👤 RÔLES
const user = computed(() => page.props.auth.user)
const isPatient = computed(() => user.value?.role === 'patient')
const isMedecin = computed(() => user.value?.role === 'medecin')
const isAdmin = computed(() => user.value?.role === 'admin')

// 🌙 DARK MODE
const isDark = ref(false)
const applyTheme = (dark) => {
    document.documentElement.classList.toggle('dark', dark)
}
onMounted(() => {
    const savedTheme = localStorage.getItem('theme')
    isDark.value = savedTheme === 'dark'
    applyTheme(isDark.value)
})
const toggleDarkMode = () => {
    isDark.value = !isDark.value
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
    applyTheme(isDark.value)
}

// 🔔 NOTIFICATIONS
const notifications = ref(page.props.notifications || [])
watch(() => page.props.notifications, (newVal) => {
    notifications.value = [...newVal]
})
const markAsRead = (id) => {
    notifications.value = notifications.value.filter(n => n.id !== id)
    router.post(route('notifications.read', id), {}, {
        preserveScroll: true,
        preserveState: true,
    })
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300 font-sans">

        <nav class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">

                    <div class="flex items-center gap-10">
                        <Link :href="route('dashboard')" class="flex items-center gap-2 group">
                            <div class="bg-indigo-600 p-1.5 rounded-lg transition-transform group-hover:scale-105 shadow-lg shadow-indigo-500/20">
                                <ApplicationLogo class="h-6 w-6 text-white fill-current" />
                            </div>
                            <span class="font-black text-xl tracking-tighter text-slate-800 dark:text-white">
                                Gestion<span class="text-indigo-600 dark:text-indigo-400 font-black">Santé</span>
                            </span>
                        </Link>

                        <div class="hidden md:flex items-center gap-2">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="dark:text-slate-300 dark:hover:text-white">
                                Dashboard
                            </NavLink>

                            <template v-if="isAdmin">
                                <NavLink :href="route('admin.users.index')" :active="route().current('admin.users.*')" class="dark:text-slate-300 dark:hover:text-white">
                                    Utilisateurs
                                </NavLink>
                            </template>

                            <template v-if="isMedecin">
                                <NavLink :href="route('medecin.appointments.index')" :active="route().current('medecin.appointments.*')" class="dark:text-slate-300 dark:hover:text-white">
                                    Rendez-vous
                                </NavLink>
                                <NavLink :href="route('medecin.calendar')" :active="route().current('medecin.calendar')" class="dark:text-slate-300 dark:hover:text-white">
                                    Agenda
                                </NavLink>
                            </template>

                            <template v-if="isPatient">
                                <NavLink :href="route('patient.appointments.index')" :active="route().current('patient.appointments.*')" class="dark:text-slate-300 dark:hover:text-white">
                                    Mes RDV
                                </NavLink>
                                <NavLink :href="route('patient.calendar')" :active="route().current('patient.calendar')" class="dark:text-slate-300 dark:hover:text-white">
                                    Calendrier
                                </NavLink>
                                <NavLink :href="route('patient.invoices.index')" :active="route().current('patient.invoices.*')" class="dark:text-slate-300 dark:hover:text-white">
                                    Factures
                                </NavLink>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-4">
                        <Link
                            v-if="isMedecin"
                            :href="route('medecin.appointments.invitations')"
                            class="relative p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all active:scale-90 group"
                            title="Invitations en attente"
                        >
                            <svg class="w-6 h-6 group-hover:-rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="absolute top-2 right-2 flex h-2 w-2">
                                <span class="animate-ping absolute h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                        </Link>

                        <button @click="toggleDarkMode" class="p-2.5 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 dark:text-slate-400 transition-all active:scale-90">
                            <span v-if="isDark" class="text-xl">☀️</span>
                            <span v-else class="text-xl">🌙</span>
                        </button>

                        <Dropdown align="right" width="80">
                            <template #trigger>
                                <button class="relative p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all group active:scale-90">
                                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span v-if="notifications.length" class="absolute top-2.5 right-2.5 flex h-2 w-2">
                                        <span class="animate-ping absolute h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                </button>
                            </template>
                            <template #content>
                                <div class="w-80 p-2 bg-white dark:bg-slate-900 shadow-2xl rounded-2xl border border-slate-100 dark:border-slate-800">
                                    <div class="px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-50 dark:border-slate-800/50 mb-2">Notifications</div>
                                    <div v-if="notifications.length" class="max-h-80 overflow-y-auto custom-scrollbar">
                                        <div v-for="n in notifications" :key="n.id" class="p-4 mb-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-snug font-medium">{{ n.data.message }}</p>
                                            <div class="flex justify-between items-center mt-3">
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ n.created_at }}</span>
                                                <button @click="markAsRead(n.id)" class="text-[10px] bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2 py-1 rounded-md font-black opacity-0 group-hover:opacity-100 transition">MARQUER LU</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="py-10 text-center text-slate-400 text-sm italic font-medium">Aucun nouveau message</div>
                                </div>
                            </template>
                        </Dropdown>

                        <div class="hidden sm:block h-6 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

                        <Dropdown align="right" width="56">
                            <template #trigger>
                                <button class="flex items-center gap-3 p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all text-left group">
                                    <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-black shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition">
                                        {{ user.name.charAt(0) }}
                                    </div>
                                    <div class="hidden lg:block leading-none">
                                        <p class="text-sm font-black text-slate-800 dark:text-white">{{ user.name }}</p>
                                        <span class="text-[9px] font-black uppercase tracking-tighter text-slate-400">{{ user.role }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </template>
                            <template #content>
                                <div class="p-1.5 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-100 dark:border-slate-800">
                                    <DropdownLink :href="route('profile.edit')" class="dark:text-slate-300 dark:hover:text-white rounded-lg">👤 Mon Profil</DropdownLink>
                                    <hr class="my-1.5 border-slate-100 dark:border-slate-800" />
                                    <DropdownLink :href="route('logout')" method="post" as="button" class="text-red-500 font-black rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">🚪 Déconnexion</DropdownLink>
                                </div>
                            </template>
                        </Dropdown>

                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="md:hidden p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2.5" stroke-linecap="round"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="showingNavigationDropdown" class="md:hidden bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 p-4 space-y-1">
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="dark:text-white">Dashboard</ResponsiveNavLink>
                <template v-if="isAdmin">
                    <ResponsiveNavLink :href="route('admin.users.index')" :active="route().current('admin.users.*')" class="dark:text-white">Utilisateurs</ResponsiveNavLink>
                </template>
                <template v-if="isMedecin">
                    <ResponsiveNavLink :href="route('medecin.appointments.index')" class="dark:text-white">Rendez-vous</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('medecin.calendar')" class="dark:text-white">Agenda</ResponsiveNavLink>
                </template>
                <template v-if="isPatient">
                    <ResponsiveNavLink :href="route('patient.appointments.index')" class="dark:text-white">Mes RDV</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('patient.calendar')" class="dark:text-white">Calendrier</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('patient.invoices.index')" class="dark:text-white">Factures</ResponsiveNavLink>
                </template>
                <hr class="my-2 border-slate-100 dark:border-slate-800" />
                <ResponsiveNavLink :href="route('profile.edit')" class="dark:text-white">Paramètres</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-red-500 font-bold">Déconnexion</ResponsiveNavLink>
            </div>
        </nav>

        <header v-if="$slots.header" class="pt-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm border border-slate-200 dark:border-slate-800/60 rounded-[2.5rem] p-10 shadow-sm transition-all hover:shadow-xl hover:shadow-indigo-500/5 group">
                    <div class="relative z-10">
                        <slot name="header" />
                    </div>
                    <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/5 dark:bg-indigo-400/10 rounded-full blur-[100px] group-hover:bg-indigo-500/10 transition-colors duration-700"></div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <slot />
        </main>

        <footer class="mt-20 border-t border-slate-200 dark:border-slate-900 py-12 bg-white dark:bg-slate-950 transition-colors">
            <div class="mx-auto max-w-7xl px-4 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600/10 p-2 rounded-lg">
                        <ApplicationLogo class="h-5 w-5 fill-current text-indigo-500" />
                    </div>
                    <span class="font-black tracking-tight text-slate-800 dark:text-white">GestionSanté <span class="text-slate-400 font-medium">© 2026</span></span>
                </div>
                <div class="flex gap-8 font-bold text-sm text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Support</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Confidentialité</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">CGU</a>
                </div>
            </div>
        </footer>

        <Toast />
    </div>
</template>

<style>
/* Custom Scrollbar pour le menu notifications */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #1e293b;
}
</style>
