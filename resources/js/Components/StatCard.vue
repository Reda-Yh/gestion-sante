<template>
  <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
    <div class="flex items-center justify-between">
      <div :class="[
        'w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner transition-transform group-hover:scale-110',
        color === 'indigo' ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' : '',
        color === 'emerald' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : '',
        color === 'blue' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '',
        color === 'red' ? 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '',
      ]">
        {{ icon }}
      </div>
      <div class="text-right">
        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-1">{{ title }}</p>
        <p class="text-3xl font-black text-slate-900 dark:text-white">{{ value }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: Number,
  icon: String,
  color: String
})
</script>
