<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { CheckCircle, Lock, Mail, Trash2, User } from 'lucide-vue-next';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { useI18n } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { send } from '@/routes/verification';

defineOptions({ layout: ClientLayout });

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

const { t } = useI18n();
const page = usePage();
const user = computed(
    () =>
        page.props.auth.user as {
            name: string;
            email: string;
            email_verified_at: string | null;
        },
);
</script>

<template>
    <Head :title="t('settings.profile.pageTitle')" />

    <div class="min-h-screen bg-orange-50 dark:bg-gray-950">
    <div class="mx-auto max-w-3xl px-4 py-10">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ t('settings.profile.heading') }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ t('settings.profile.subheading') }}
            </p>
        </div>

        <!-- Profile card -->
        <div
            class="rounded-2xl border border-amber-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
        >
            <!-- Avatar section -->
            <div
                class="flex items-center gap-5 border-b border-amber-100 px-6 py-5 dark:border-gray-700"
            >
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-500 text-2xl font-bold text-white shadow-md"
                >
                    {{ user.name?.charAt(0).toUpperCase() }}
                </div>
                <div>
                    <p
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        {{ user.name }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ user.email }}
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="px-6 py-6">
                <h2
                    class="mb-5 flex items-center gap-2 text-sm font-semibold tracking-wide text-amber-600 uppercase dark:text-amber-400"
                >
                    <User class="h-4 w-4" /> {{ t('settings.profile.sectionTitle') }}
                </h2>

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-5"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div>
                        <label
                            for="name"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >{{ t('settings.profile.nameLabel') }}</label
                        >
                        <input
                            id="name"
                            name="name"
                            :value="user.name"
                            required
                            autocomplete="name"
                            :placeholder="t('settings.profile.namePlaceholder')"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 transition focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-amber-400"
                        />
                        <InputError class="mt-1.5" :message="errors.name" />
                    </div>

                    <div>
                        <label
                            for="email"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >{{ t('settings.profile.emailLabel') }}</label
                        >
                        <div class="relative">
                            <Mail
                                class="absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-gray-400"
                            />
                            <input
                                id="email"
                                name="email"
                                type="email"
                                :value="user.email"
                                required
                                autocomplete="username"
                                :placeholder="t('settings.profile.emailPlaceholder')"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pr-4 pl-10 text-gray-900 placeholder-gray-400 transition focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-amber-400"
                            />
                        </div>
                        <InputError class="mt-1.5" :message="errors.email" />
                    </div>

                    <div
                        v-if="mustVerifyEmail && !user.email_verified_at"
                        class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800 dark:border-yellow-700/50 dark:bg-yellow-900/20 dark:text-yellow-400"
                    >
                        {{ t('settings.profile.unverified') }}
                        <Link
                            :href="send()"
                            as="button"
                            class="ml-1 font-semibold underline hover:text-yellow-900 dark:hover:text-yellow-300"
                        >
                            {{ t('settings.profile.resend') }}
                        </Link>
                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-1 flex items-center gap-1 font-medium text-green-600 dark:text-green-400"
                        >
                            <CheckCircle class="h-4 w-4" /> {{ t('settings.profile.linkSent') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="flex items-center gap-1.5 text-sm font-medium text-green-600 dark:text-green-400"
                            >
                                <CheckCircle class="h-4 w-4" /> {{ t('settings.profile.savedMsg') }}
                            </p>
                        </Transition>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="ml-auto rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 disabled:opacity-50"
                        >
                            {{ t('settings.profile.saveBtn') }}
                        </button>
                    </div>
                </Form>
            </div>
        </div>

        <!-- Change password card -->
        <div
            class="mt-6 rounded-2xl border border-amber-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
        >
            <div
                class="flex items-center gap-3 border-b border-amber-100 px-6 py-4 dark:border-gray-700"
            >
                <div class="rounded-lg bg-amber-100 p-1.5 dark:bg-amber-900/30">
                    <Lock class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <h2
                        class="text-sm font-semibold text-gray-900 dark:text-white"
                    >
                        {{ t('settings.password.title') }}
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ t('settings.password.subtitle') }}
                    </p>
                </div>
            </div>
            <div class="px-6 py-6">
                <Form
                    v-bind="SecurityController.update.form()"
                    :options="{ preserveScroll: true }"
                    reset-on-success
                    :reset-on-error="[
                        'password',
                        'password_confirmation',
                        'current_password',
                    ]"
                    class="space-y-5"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div>
                        <label
                            for="current_password"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >{{ t('settings.password.current') }}</label
                        >
                        <PasswordInput
                            id="current_password"
                            name="current_password"
                            autocomplete="current-password"
                            :placeholder="t('settings.password.current')"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        />
                        <InputError :message="errors.current_password" />
                    </div>
                    <div>
                        <label
                            for="password"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >{{ t('settings.password.new') }}</label
                        >
                        <PasswordInput
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            :placeholder="t('settings.password.new')"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        />
                        <InputError :message="errors.password" />
                    </div>
                    <div>
                        <label
                            for="password_confirmation"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >{{ t('settings.password.confirm') }}</label
                        >
                        <PasswordInput
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            :placeholder="t('settings.password.confirm')"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="flex items-center gap-1.5 text-sm font-medium text-green-600 dark:text-green-400"
                            >
                                <CheckCircle class="h-4 w-4" />
                                {{ t('settings.password.saved') }}
                            </p>
                        </Transition>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="ml-auto rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 disabled:opacity-50"
                        >
                            {{ t('settings.password.save') }}
                        </button>
                    </div>
                </Form>
            </div>
        </div>

        <!-- Delete account card -->
        <div
            class="mt-6 rounded-2xl border border-red-100 bg-white shadow-sm dark:border-red-900/40 dark:bg-gray-900"
        >
            <div
                class="flex items-center gap-3 border-b border-red-100 px-6 py-4 dark:border-red-900/40"
            >
                <div class="rounded-lg bg-red-100 p-1.5 dark:bg-red-900/30">
                    <Trash2 class="h-4 w-4 text-red-500 dark:text-red-400" />
                </div>
                <div>
                    <h2
                        class="text-sm font-semibold text-red-600 dark:text-red-400"
                    >
                        {{ t('settings.delete.danger') }}
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ t('settings.delete.irreversible') }}
                    </p>
                </div>
            </div>
            <div class="px-6 py-5">
                <DeleteUser />
            </div>
        </div>
    </div>
    </div>
</template>
