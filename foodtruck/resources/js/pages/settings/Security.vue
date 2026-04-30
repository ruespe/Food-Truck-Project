<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ShieldCheck, Lock, CheckCircle } from 'lucide-vue-next';
import { onUnmounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { edit as editProfile } from '@/routes/profile';
import { edit } from '@/routes/security';
import { edit as editAppearance } from '@/routes/appearance';
import { disable, enable } from '@/routes/two-factor';

defineOptions({ layout: ClientLayout });

type Props = {
    canManageTwoFactor?: boolean;
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

withDefaults(defineProps<Props>(), {
    canManageTwoFactor: false,
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

onUnmounted(() => clearTwoFactorAuthData());
</script>

<template>
    <Head title="Seguridad" />

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
            <Link :href="edit()" class="flex-1 rounded-lg py-2 text-center text-sm font-semibold bg-white shadow text-amber-600 dark:bg-gray-700 dark:text-amber-400">
                Seguridad
            </Link>
            <Link :href="editAppearance()" class="flex-1 rounded-lg py-2 text-center text-sm font-medium text-gray-600 transition hover:bg-white/60 dark:text-gray-300 dark:hover:bg-gray-700/60">
                Apariencia
            </Link>
        </div>

        <!-- Change password card -->
        <div class="rounded-2xl border border-amber-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center gap-3 border-b border-amber-100 px-6 py-4 dark:border-gray-700">
                <div class="rounded-lg bg-amber-100 p-1.5 dark:bg-amber-900/30">
                    <Lock class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Cambiar contraseña</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Usa una contraseña larga y segura</p>
                </div>
            </div>

            <div class="px-6 py-6">
                <Form
                    v-bind="SecurityController.update.form()"
                    :options="{ preserveScroll: true }"
                    reset-on-success
                    :reset-on-error="['password', 'password_confirmation', 'current_password']"
                    class="space-y-5"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div>
                        <label for="current_password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña actual</label>
                        <PasswordInput
                            id="current_password"
                            name="current_password"
                            autocomplete="current-password"
                            placeholder="Contraseña actual"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        />
                        <InputError :message="errors.current_password" />
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva contraseña</label>
                        <PasswordInput
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Nueva contraseña"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar contraseña</label>
                        <PasswordInput
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Repite la nueva contraseña"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="recentlySuccessful" class="flex items-center gap-1.5 text-sm font-medium text-green-600 dark:text-green-400">
                                <CheckCircle class="h-4 w-4" /> Contraseña actualizada
                            </p>
                        </Transition>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="ml-auto rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 disabled:opacity-50"
                        >
                            Guardar contraseña
                        </button>
                    </div>
                </Form>
            </div>
        </div>

        <!-- 2FA card -->
        <div v-if="canManageTwoFactor" class="mt-6 rounded-2xl border border-amber-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center gap-3 border-b border-amber-100 px-6 py-4 dark:border-gray-700">
                <div class="rounded-lg bg-amber-100 p-1.5 dark:bg-amber-900/30">
                    <ShieldCheck class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Autenticación de dos factores</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Protege tu cuenta con 2FA</p>
                </div>
                <span v-if="twoFactorEnabled" class="ml-auto rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">Activado</span>
                <span v-else class="ml-auto rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-400">Desactivado</span>
            </div>

            <div class="px-6 py-6">
                <div v-if="!twoFactorEnabled" class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Al activar 2FA, se te pedirá un código seguro al iniciar sesión, generado desde una app TOTP (Google Authenticator, Authy, etc.).
                    </p>
                    <Form v-if="!hasSetupData" v-bind="enable.form()" @success="showSetupModal = true" #default="{ processing }">
                        <button type="submit" :disabled="processing" class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 disabled:opacity-50">
                            Activar 2FA
                        </button>
                    </Form>
                    <button v-else @click="showSetupModal = true" class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                        Continuar configuración
                    </button>
                </div>

                <div v-else class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Tu cuenta está protegida con autenticación de dos factores.
                    </p>
                    <TwoFactorRecoveryCodes />
                    <Form v-bind="disable.form()" #default="{ processing }">
                        <button type="submit" :disabled="processing" class="rounded-xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100 disabled:opacity-50 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
                            Desactivar 2FA
                        </button>
                    </Form>
                </div>
            </div>
        </div>

        <TwoFactorSetupModal
            v-if="canManageTwoFactor"
            v-model:isOpen="showSetupModal"
            :requiresConfirmation="requiresConfirmation"
            :twoFactorEnabled="twoFactorEnabled"
        />

    </div>
</template>


type Props = {
    canManageTwoFactor?: boolean;
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

withDefaults(defineProps<Props>(), {
    canManageTwoFactor: false,
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

onUnmounted(() => clearTwoFactorAuthData());
</script>
