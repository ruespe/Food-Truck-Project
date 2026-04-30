<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
</script>

<template>
    <AuthBase
        title="Crear cuenta"
        description="Rellena tus datos para registrarte"
    >
        <Head title="Registro" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="name" class="text-gray-700 dark:text-gray-300">Nombre completo</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Tu nombre"
                        class="rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-gray-700 dark:text-gray-300">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="tu@email.com"
                        class="rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-gray-700 dark:text-gray-300">Contraseña</Label>
                    <PasswordInput
                        id="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Contraseña"
                        class="rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" class="text-gray-700 dark:text-gray-300">Confirmar contraseña</Label>
                    <PasswordInput
                        id="password_confirmation"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Repite la contraseña"
                        class="rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <button
                    type="submit"
                    class="mt-2 flex w-full items-center justify-center gap-2 rounded-full bg-amber-500 px-6 py-3 font-bold text-white shadow transition hover:bg-amber-600 disabled:opacity-60"
                    tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Crear cuenta
                </button>
            </div>

            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                ¿Ya tienes cuenta?
                <TextLink
                    :href="login()"
                    class="font-semibold text-amber-600 hover:text-amber-700"
                    :tabindex="6"
                    >Iniciar sesión</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
