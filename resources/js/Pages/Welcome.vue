<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Bienvenue - Gestion Santé Premium" />

    <div class="min-h-screen bg-white text-slate-900 font-sans selection:bg-blue-600 selection:text-white">

        <nav class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600 p-2.5 rounded-xl shadow-lg shadow-blue-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-2xl font-black text-slate-800 tracking-tight">Gestion<span class="text-blue-600">Santé</span></span>
                    </div>

                    <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                        <a href="#features" class="hover:text-blue-600 transition">Services</a>
                        <a href="#stats" class="hover:text-blue-600 transition">Impact</a>
                        <a href="#testimonials" class="hover:text-blue-600 transition">Témoignages</a>
                    </div>

                    <div v-if="canLogin" class="flex items-center gap-4">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="bg-slate-900 text-white px-6 py-2.5 rounded-full font-bold hover:bg-slate-800 transition shadow-lg">
                            Accéder au Dashboard
                        </Link>

                        <template v-else>
                            <Link :href="route('login')" class="text-slate-600 hover:text-blue-600 font-bold transition px-2">Connexion</Link>
                            <Link v-if="canRegister" :href="route('register')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-bold transition shadow-xl shadow-blue-100">
                                Inscription gratuite
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <section class="relative pt-16 pb-24 lg:pt-32 lg:pb-40 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-16 items-center">
                    <div class="z-10 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-bold mb-8">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                            </span>
                            Nouveau : Module de téléconsultation disponible
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-8">
                            La santé connectée, <span class="text-blue-600">réinventée.</span>
                        </h1>
                        <p class="text-xl text-slate-600 leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                            Centralisez vos dossiers patients, optimisez votre temps médical et sécurisez vos données avec la plateforme n°1 choisie par les praticiens.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                            <Link :href="route('register')" class="px-10 py-5 bg-blue-600 text-white rounded-2xl font-black text-lg hover:bg-blue-700 transition shadow-2xl shadow-blue-200 flex items-center justify-center gap-2">
                                Démarrer l'essai gratuit
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </Link>
                        </div>
                    </div>

                    <div class="relative group">
                        <div class="absolute -inset-4 bg-gradient-to-tr from-blue-100 to-indigo-100 rounded-[3rem] blur-2xl opacity-50 group-hover:opacity-80 transition duration-500"></div>
                        <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white transform hover:scale-[1.02] transition duration-500">
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=1200" alt="Médecin moderne" class="w-full h-[500px] object-cover" />
                        </div>
                        <div class="absolute top-10 -right-10 bg-white p-4 rounded-2xl shadow-2xl border border-slate-50 animate-bounce-slow">
                            <div class="flex items-center gap-3">
                                <div class="bg-orange-100 p-2 rounded-lg text-orange-600">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Temps gagné</p>
                                    <p class="text-lg font-black text-slate-800">-2h / jour</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="stats" class="py-16 bg-slate-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        <div v-for="(stat, index) in [['15k+', 'Praticiens'], ['2M+', 'Patients'], ['99.9%', 'Uptime'], ['24/7', 'Support']]" :key="index">
                            <p class="text-4xl font-black text-blue-400 mb-2">{{ stat[0] }}</p>
                            <p class="text-slate-400 font-medium">{{ stat[1] }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="py-24 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-blue-600 font-black tracking-widest uppercase text-sm mb-4">Fonctionnalités Clés</h2>
                    <h3 class="text-4xl lg:text-5xl font-black text-slate-900 mb-20 leading-tight">Tout ce dont vous avez besoin<br/>dans une seule interface.</h3>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-white p-10 rounded-[2rem] shadow-sm hover:shadow-xl transition-all border border-slate-100 group">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <h4 class="text-2xl font-black mb-4">Agenda Synchronisé</h4>
                            <p class="text-slate-500 leading-relaxed">Prise de rendez-vous en ligne, rappels SMS automatiques et gestion multi-cabinets.</p>
                        </div>

                        <div class="bg-white p-10 rounded-[2rem] shadow-sm hover:shadow-xl transition-all border border-slate-100 group">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h4 class="text-2xl font-black mb-4">Dossier Médical HDS</h4>
                            <p class="text-slate-500 leading-relaxed">Dossiers sécurisés, prescriptions biométriques et archivage certifié de santé.</p>
                        </div>

                        <div class="bg-white p-10 rounded-[2rem] shadow-sm hover:shadow-xl transition-all border border-slate-100 group">
                            <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /></svg>
                            </div>
                            <h4 class="text-2xl font-black mb-4">Analyses Précises</h4>
                            <p class="text-slate-500 leading-relaxed">Visualisez la croissance de votre patientèle et gérez votre comptabilité en un clic.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-white border-t border-slate-100 pt-24 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <div class="col-span-1 md:col-span-1">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-blue-600 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <span class="text-xl font-bold">GestionSanté</span>
                        </div>
                        <p class="text-slate-500 leading-relaxed mb-6">La plateforme de référence pour la gestion médicale sécurisée et innovante.</p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                            <a href="#" class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-black mb-6 uppercase tracking-widest text-xs text-slate-400">Plateforme</h5>
                        <ul class="space-y-4 font-bold text-slate-600">
                            <li><a href="#" class="hover:text-blue-600 transition">Fonctionnalités</a></li>
                            <li><a href="#" class="hover:text-blue-600 transition">Téléconsultation</a></li>
                            <li><a href="#" class="hover:text-blue-600 transition">Tarification</a></li>
                        </ul>
                    </div>

                    <div>
                        <h5 class="font-black mb-6 uppercase tracking-widest text-xs text-slate-400">Ressources</h5>
                        <ul class="space-y-4 font-bold text-slate-600">
                            <li><a href="#" class="hover:text-blue-600 transition">Centre d'aide</a></li>
                            <li><a href="#" class="hover:text-blue-600 transition">Guides Pratiques</a></li>
                            <li><a href="#" class="hover:text-blue-600 transition">Sécurité des données</a></li>
                        </ul>
                    </div>

                    <div>
                        <h5 class="font-black mb-6 uppercase tracking-widest text-xs text-slate-400">Restez informé</h5>
                        <p class="text-sm text-slate-500 mb-4 font-medium">Recevez nos dernières mises à jour médicales.</p>
                        <form class="flex flex-col gap-3">
                            <input type="email" placeholder="Votre email" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                            <button class="bg-slate-900 text-white font-black py-3 rounded-xl hover:bg-slate-800 transition">S'abonner</button>
                        </form>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-slate-400 text-sm font-medium">© 2026 GestionSanté. Tous droits réservés. Certifié HDS & RGPD.</p>
                    <div class="flex gap-8 text-sm font-bold text-slate-600">
                        <a href="#" class="hover:text-blue-600 transition">Confidentialité</a>
                        <a href="#" class="hover:text-blue-600 transition">Mentions Légales</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.font-sans {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

@keyframes bounce-slow {
    0%, 100% { transform: translateY(-5%); }
    50% { transform: translateY(0); }
}
.animate-bounce-slow {
    animation: bounce-slow 4s infinite ease-in-out;
}
</style>
