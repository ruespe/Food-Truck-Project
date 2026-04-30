<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Palette } from 'lucide-vue-next';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import { edit } from '@/routes/appearance';

defineOptions({ layout: ClientLayout });
</script>

<template>
    <Head title="Apariencia" />

    <div class="mx-auto max-w-3xl px-4 py-10">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajustes de cuenta</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gestiona tu perfil y preferencias</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6 flex gap-1 rounded-xl bg-amber-100 p-1 dark:bg-gray-800">
            <Link :href="editProfile()" class="flex-1 rounded-lg py-2 text-center text-sm font-medium text-gray-600 transition hover:bg-white/60 dark:text-gray-300 dark:hover:bg-gray-700/60">
                Perfil
            </Link>
            <Link :href="editSecurity()" class="flex-1 rounded-lg py-2 text-center text-sm font-medium text-gray-600 transition hover:bg-white/60 dark:text-gray-300 dark:hover:bg-gray-700/60">
                Seguridad
            </Link>
            <Link :href="edit()" class="flex-1 rounded-lg py-2 text-center text-sm font-semibold bg-white shadow text-amber-600 dark:bg-gray-700 dark:text-amber-400">
                Apariencia
            </Link>
        </div>

        <!-- Appearance card -->
        <div class="rounded-2xl border border-amber-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center gap-3 border-b border-amber-100 px-6 py-4 dark:border-gray-700">
                <div class="rounded-lg bg-amber-100 p-1.5 dark:bg-amber-900/30">
                    <Palette class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Tema de la interfaz</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Elige entre modo claro, oscuro o el del sistema</p>
                </div>
            </div>
            <div class="px-6 py-6">
                <AppearanceTabs />
            </div>
        </div>

    </div>
</template>
