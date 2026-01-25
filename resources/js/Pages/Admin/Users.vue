<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-black text-2xl text-slate-800 dark:text-white transition-colors">
        🛡️ Gestion des utilisateurs
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-xl sm:rounded-xl border border-slate-200 dark:border-slate-800 transition-colors">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                  <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Utilisateur</th>
                  <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rôle</th>
                  <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Statut</th>
                  <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <tr v-for="u in users" :key="u.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                  <td class="px-6 py-4">
                    <div class="flex flex-col">
                      <span class="font-semibold text-slate-900 dark:text-white">{{ u.name }}</span>
                      <span class="text-sm text-slate-500 dark:text-slate-400">{{ u.email }}</span>
                    </div>
                  </td>

                  <td class="px-6 py-4">
                    <select
                      @change="updateRole(u.id, $event.target.value)"
                      :value="u.role"
                      class="bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 transition-colors"
                    >
                      <option value="patient">Patient</option>
                      <option value="medecin">Médecin</option>
                      <option value="admin">Admin</option>
                    </select>
                  </td>

                  <td class="px-6 py-4">
                    <span v-if="u.banned" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                      <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                      Banni
                    </span>
                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                      <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                      Actif
                    </span>
                  </td>

                  <td class="px-6 py-4 text-right">
                    <button
                      @click="openModal(u)"
                      :class="[
                        'inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white transition-all focus:outline-none focus:ring-2 focus:ring-offset-2',
                        u.banned ? 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                      ]"
                    >
                      {{ u.banned ? 'Débannir' : 'Bannir' }}
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <Teleport to="body">
      <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
          <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-800">
              <div class="p-6">
                <div class="flex items-center gap-4">
                  <div :class="[
                    'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-inner',
                    selectedUser?.banned ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30' : 'bg-red-100 text-red-600 dark:bg-red-900/30'
                  ]">
                    {{ selectedUser?.banned ? '🔓' : '🚫' }}
                  </div>
                  <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">
                      {{ selectedUser?.banned ? 'Débannir' : 'Bannir' }} l'utilisateur ?
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                      Action sur <span class="font-bold text-indigo-500">{{ selectedUser?.name }}</span>
                    </p>
                  </div>
                </div>
                <p class="mt-4 text-slate-600 dark:text-slate-300 leading-relaxed">
                  {{ selectedUser?.banned
                    ? "Cet utilisateur retrouvera immédiatement l'accès à son compte et aux fonctionnalités de la plateforme."
                    : "L'utilisateur sera déconnecté et ne pourra plus accéder à son espace personnel jusqu'à ce qu'il soit débanni."
                  }}
                </p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                <button
                  @click="confirmToggleBan"
                  :class="[
                    'px-6 py-2.5 rounded-xl font-bold text-white shadow-lg transition-transform active:scale-95',
                    selectedUser?.banned ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'
                  ]"
                >
                  Confirmer la décision
                </button>
                <button
                  @click="closeModal"
                  class="px-6 py-2.5 rounded-xl font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
                >
                  Annuler
                </button>
              </div>
            </div>
          </Transition>
        </div>
      </Transition>
    </Teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()
const users = computed(() => page.props.users || [])

// États de la modale
const isModalOpen = ref(false)
const selectedUser = ref(null)

const openModal = (user) => {
    selectedUser.value = user
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
    setTimeout(() => { selectedUser.value = null }, 300) // Reset après l'animation
}

// Actions
const updateRole = (userId, newRole) => {
    router.post(route('admin.users.updateRole', userId), { role: newRole }, { preserveScroll: true })
}

const confirmToggleBan = () => {
    router.post(route('admin.users.toggleBan', selectedUser.value.id), {}, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    })
}
</script>
