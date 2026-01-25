<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const form = useForm({ password: '' });

const confirmUserDeletion = () => { confirmingUserDeletion.value = true; nextTick(() => passwordInput.value.focus()); };
const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
const closeModal = () => { confirmingUserDeletion.value = false; form.clearErrors(); form.reset(); };
</script>

<template>
    <section>
        <header class="flex items-center gap-4 mb-6">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-500/20 rounded-xl flex items-center justify-center text-red-600 font-bold">⚠️</div>
            <div>
                <h2 class="text-xl font-black text-red-600 italic uppercase tracking-tighter">Zone de Danger</h2>
                <p class="text-sm text-slate-500 dark:text-red-400/60">Une fois supprimé, votre compte et toutes ses données (rendez-vous, factures) seront perdus.</p>
            </div>
        </header>

        <DangerButton @click="confirmUserDeletion">Supprimer définitivement le compte</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-8 bg-white dark:bg-slate-900">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white italic">Confirmer la suppression ?</h2>
                <p class="mt-4 text-slate-500">Pour valider cette action irréversible, merci de saisir votre mot de passe.</p>

                <div class="mt-6">
                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password" class="block w-full" placeholder="Mot de passe" @keyup.enter="deleteUser" />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal" class="rounded-xl">Annuler</SecondaryButton>
                    <DangerButton class="ms-3 rounded-xl" :disabled="form.processing" @click="deleteUser">Supprimer mon compte</DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
