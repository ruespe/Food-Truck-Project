<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Iniciar sesión"
        description="Introduce tu email y contraseña para acceder"
    >
        <Head title="Iniciar sesión" />

        <div
            v-if="status"
            class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-center text-sm font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="email" class="text-gray-700 dark:text-gray-300">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="tu@email.com"
                        class="rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-gray-700 dark:text-gray-300">Contraseña</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-sm text-amber-600 hover:text-amber-700"
                            :tabindex="5"
                        >
                            ¿Olvidaste tu contraseña?
                        </TextLink>
                    </div>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Contraseña"
                        class="rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center">
                    <Label for="remember" class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 dark:text-gray-400">
                        <Checkbox id="remember" name="remember" :tabindex="3" />
                        <span>Recordarme</span>
                    </Label>
                </div>

                <button
                    type="submit"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                    class="mt-2 flex w-full items-center justify-center gap-2 rounded-full bg-amber-500 px-6 py-3 font-bold text-white shadow transition hover:bg-amber-600 disabled:opacity-60"
                >
                    <Spinner v-if="processing" />
                    Entrar
                </button>
            </div>

            <div
                class="text-center text-sm text-gray-500 dark:text-gray-400"
                v-if="canRegister"
            >
                ¿No tienes cuenta?
                <TextLink :href="register()" :tabindex="5" class="font-semibold text-amber-600 hover:text-amber-700">Regístrate</TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
