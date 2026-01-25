<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full px-4 py-3 rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm transition-all duration-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring focus:ring-indigo-500/20 dark:focus:ring-indigo-400/20 placeholder:text-slate-400 dark:placeholder:text-slate-600"
        v-model="model"
        ref="input"
    />
</template>

<style scoped>
/* Supprime l'anneau de focus par défaut du navigateur pour utiliser le nôtre en Tailwind */
input:focus {
    outline: none;
}
</style>
