<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl shadow-xl shadow-indigo-500/30">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <h2 class="font-black text-3xl text-slate-800 dark:text-white tracking-tight">Admin Panel</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Gestion globale du système</p>
          </div>
        </div>
      </div>
    </template>

    <div class="py-8 space-y-8">

      <div class="relative overflow-hidden bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-500/20">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
          <div class="max-w-md text-center md:text-left">
            <h1 class="text-4xl font-black mb-4 leading-tight">Bienvenue sur votre interface de pilotage.</h1>
            <p class="text-indigo-100 text-lg mb-6">Contrôlez les accès, surveillez les statistiques et gérez la base utilisateurs en toute simplicité.</p>
            <Link :href="route('admin.users.index')" class="inline-flex items-center gap-3 px-8 py-3 bg-white text-indigo-600 rounded-xl font-bold transition-all hover:bg-indigo-50 hover:scale-105 active:scale-95 shadow-lg">
              Accéder aux Utilisateurs
            </Link>
          </div>
          <div class="w-full max-w-[320px] drop-shadow-2xl">

          </div>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-64 h-64 bg-indigo-900/20 rounded-full blur-2xl"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard title="Total Utilisateurs" :value="stats.total" icon="👥" color="indigo" class="hover:border-indigo-500/50 transition-colors" />
        <StatCard title="Médecins" :value="stats.medecins" icon="👨‍⚕️" color="emerald" class="hover:border-emerald-500/50 transition-colors" />
        <StatCard title="Patients" :value="stats.patients" icon="🤕" color="blue" class="hover:border-blue-500/50 transition-colors" />
        <StatCard title="Comptes Restreints" :value="stats.banned" icon="🚫" color="red" class="hover:border-red-500/50 transition-colors" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
          <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3">
            <span class="w-2.5 h-6 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
            Répartition des Rôles
          </h3>
          <div class="relative h-80">
            <canvas id="roleChart"></canvas>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
          <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3">
            <span class="w-2.5 h-6 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
            Statut des Comptes
          </h3>
          <div class="relative h-80">
            <canvas id="statusChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import StatCard from '@/Components/StatCard.vue'
import { Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({ stats: Object })

onMounted(() => {
  const isDark = document.documentElement.classList.contains('dark');
  const textColor = isDark ? '#94a3b8' : '#64748b';

  // Chart 1: Rôles
  new Chart(document.getElementById('roleChart'), {
    type: 'doughnut',
    data: {
      labels: ['Patients', 'Médecins', 'Admins'],
      datasets: [{
        data: [props.stats.patients, props.stats.medecins, props.stats.admins],
        backgroundColor: ['#6366f1', '#10b981', '#f59e0b'],
        hoverOffset: 20,
        borderWidth: isDark ? 0 : 4,
        borderColor: '#ffffff',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { padding: 30, font: { family: 'Inter', weight: '600', size: 13 }, color: textColor }
        }
      },
      cutout: '70%'
    }
  });

  // Chart 2: Statuts
  new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
      labels: ['Actifs', 'Bannis'],
      datasets: [{
        data: [props.stats.active, props.stats.banned],
        backgroundColor: ['#10b981', '#ef4444'],
        borderRadius: 15,
        maxBarThickness: 60,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: textColor },
          grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }
        },
        x: {
          ticks: { color: textColor, font: { weight: 'bold' } },
          grid: { display: false }
        }
      },
      plugins: { legend: { display: false } }
    }
  });
})
</script>

<style scoped>
canvas {
  filter: drop-shadow(0 10px 8px rgba(0, 0, 0, 0.04));
}
</style>
