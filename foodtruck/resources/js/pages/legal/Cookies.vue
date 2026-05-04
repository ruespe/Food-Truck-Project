<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });
const { t } = useI18n();
</script>

<template>
    <Head :title="`${t('cookies.title')} · FoodTruck`" />
    <div class="mx-auto max-w-3xl px-4 py-14">
        <h1 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">
            {{ t('cookies.title') }}
        </h1>
        <p class="mb-8 text-sm text-gray-400">{{ t('legal.updated') }}</p>

        <div class="space-y-6 text-gray-700 dark:text-gray-300">
            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('cookies.s1.title') }}
                </h2>
                <p>{{ t('cookies.s1.text') }}</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('cookies.s2.title') }}
                </h2>
                <div class="overflow-x-auto">
                    <table
                        class="w-full overflow-hidden rounded-lg border border-gray-200 text-sm dark:border-gray-700"
                    >
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200"
                                >
                                    {{ t('cookies.s2.col.name') }}
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200"
                                >
                                    {{ t('cookies.s2.col.type') }}
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200"
                                >
                                    {{ t('cookies.s2.col.duration') }}
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200"
                                >
                                    {{ t('cookies.s2.col.purpose') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">
                                    foodtruck_session
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.essential') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.durSession') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.pSession') }}
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">
                                    XSRF-TOKEN
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.security') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.durSession') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.pXsrf') }}
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">
                                    foodtruck_locale
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.preference') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.durYear') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.pLocale') }}
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">
                                    appearance
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.preference') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.durYear') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.pAppearance') }}
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">
                                    __stripe_*
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.thirdParty') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.durVariable') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ t('cookies.s2.pStripe') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('cookies.s3.title') }}
                </h2>
                <p>
                    {{ t('cookies.s3.pre') }} <strong>Stripe</strong>
                    {{ t('cookies.s3.mid') }}
                    <a
                        href="https://stripe.com/es/cookie-settings"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-amber-600 hover:underline"
                        >stripe.com/es/cookie-settings</a
                    >.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('cookies.s4.title') }}
                </h2>
                <p>{{ t('cookies.s4.text') }}</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    <li>
                        <a
                            href="https://support.google.com/chrome/answer/95647"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-amber-600 hover:underline"
                            >Chrome</a
                        >
                    </li>
                    <li>
                        <a
                            href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-amber-600 hover:underline"
                            >Firefox</a
                        >
                    </li>
                    <li>
                        <a
                            href="https://support.apple.com/es-es/guide/safari/sfri11471/mac"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-amber-600 hover:underline"
                            >Safari</a
                        >
                    </li>
                </ul>
            </section>
        </div>

        <div class="mt-10">
            <a href="/" class="text-amber-500 hover:underline">{{
                t('legal.back')
            }}</a>
        </div>
    </div>
</template>
