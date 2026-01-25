<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-slate-50 dark:bg-gray-900 pt-6 sm:justify-center sm:pt-0">

        <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
            <Link href="/">
                <ApplicationLogo class="h-16 w-16 fill-current text-indigo-600" />
            </Link>
        </div>

        <div class="w-full sm:max-w-5xl overflow-hidden px-4 sm:px-0">
            <slot />
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">
                &copy; 2026 Votre Plateforme Médicale. Sécurisé et confidentiel.
            </p>
        </div>
    </div>
</template>
