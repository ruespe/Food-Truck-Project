<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { User, Mail, Trash2, CheckCircle } from 'lucide-vue-next';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { edit as editSecurity } from '@/routes/security';
import { edit as editAppearance } from '@/routes/appearance';

defineOptions({ layout: ClientLayout });

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

const page = usePage();
const user = computed(() => page.props.auth.user as { name: string; email: string; email_verified_at: string | null });
</script>

<template>
    <Head title="Mi perfil" />

    <div class="mx-auto max-w-3xl px-4 py-10">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajustes de cuenta</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gestiona tu perfil y preferencias</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6 flex gap-1 rounded-xl bg-amber-100 p-1 dark:bg-gray-800">
            <Link :href="edit()" class="flex-1 rounded-lg py-2 text-center text-sm font-semibold bg-white shadow text-amber-600 dark:bg-gray-700 dark:text-amber-400">
                Perfil
            </Link>
            <Link :href="editSecurity()" class="flex-1 rounded-lg py-2 text-center text-sm font-medium text-gray-600 transition hover:bg-white/60 dark:text-gray-300 dark:hover:bg-gray-700/60">
                Seguridad
            </Link>
            <Link :href="editAppearance()" class="flex-1 rounded-lg py-2 text-center text-sm font-medium text-gray-600 transition hover:bg-white/60 dark:text-gray-300 dark:hover:bg-gray-700/60">
                Apariencia
            </Link>
        </div>

        <!-- Profile card -->
        <div class="rounded-2xl border border-amber-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <!-- Avatar section -->
            <div class="flex items-center gap-5 border-b border-amber-100 px-6 py-5 dark:border-gray-700">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-500 text-2xl font-bold text-white shadow-md">
                    {{ user.name?.charAt(0).toUpperCase() }}
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ user.name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                </div>
            </div>

            <!-- Form -->
            <div class="px-6 py-6">
                <h2 class="mb-5 flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                    <User class="h-4 w-4" /> Información personal
                </h2>

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-5"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre completo</label>
                        <input
                            id="name"
                            name="name"
                            :value="user.name"
                            required
                            autocomplete="name"
                            placeholder="Tu nombre"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 transition focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-amber-400"
                        />
                        <InputError class="mt-1.5" :message="errors.name" />
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Correo electrónico</label>
                        <div class="relative">
                            <Mail class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                            <input
                                id="email"
                                name="email"
                                type="email"
                                :value="user.email"
                                required
                                autocomplete="username"
                                placeholder="tu@email.com"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-gray-900 placeholder-gray-400 transition focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-amber-400"
                            />
                        </div>
                        <InputError class="mt-1.5" :message="errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at" class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800 dark:border-yellow-700/50 dark:bg-yellow-900/20 dark:text-yellow-400">
                        Tu correo no está verificado.
                        <Link :href="send()" as="button" class="ml-1 font-semibold underline hover:text-yellow-900 dark:hover:text-yellow-300">
                            Reenviar verificación
                        </Link>
                        <div v-if="status === 'verification-link-sent'" class="mt-1 flex items-center gap-1 font-medium text-green-600 dark:text-green-400">
                            <CheckCircle class="h-4 w-4" /> Enlace enviado.
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="recentlySuccessful" class="flex items-center gap-1.5 text-sm font-medium text-green-600 dark:text-green-400">
                                <CheckCircle class="h-4 w-4" /> Guardado correctamente
                            </p>
                        </Transition>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="ml-auto rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 disabled:opacity-50"
                        >
                            Guardar cambios
                        </button>
                    </div>
                </Form>
            </div>
        </div>

        <!-- Delete account card -->
        <div class="mt-6 rounded-2xl border border-red-100 bg-white shadow-sm dark:border-red-900/40 dark:bg-gray-900">
            <div class="flex items-center gap-3 border-b border-red-100 px-6 py-4 dark:border-red-900/40">
                <div class="rounded-lg bg-red-100 p-1.5 dark:bg-red-900/30">
                    <Trash2 class="h-4 w-4 text-red-500 dark:text-red-400" />
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-red-600 dark:text-red-400">Zona de peligro</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Esta acción es irreversible</p>
                </div>
            </div>
            <div class="px-6 py-5">
                <DeleteUser />
            </div>
        </div>

    </div>
</template>
