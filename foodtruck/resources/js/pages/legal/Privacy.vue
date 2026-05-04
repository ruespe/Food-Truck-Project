<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });
const { t } = useI18n();
</script>

<template>
    <Head :title="`${t('privacy.title')} · FoodTruck`" />
    <div class="mx-auto max-w-3xl px-4 py-14">
        <h1 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">
            {{ t('privacy.title') }}
        </h1>
        <p class="mb-8 text-sm text-gray-400">{{ t('legal.updated') }}</p>

        <div
            class="prose prose-gray dark:prose-invert max-w-none space-y-6 text-gray-700 dark:text-gray-300"
        >
            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('privacy.s1.title') }}
                </h2>
                <p>
                    {{ t('privacy.s1.pre') }}
                    <a
                        href="mailto:hola@foodtruck.es"
                        class="text-amber-600 hover:underline"
                        >hola@foodtruck.es</a
                    >
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('privacy.s2.title') }}
                </h2>
                <ul class="list-disc space-y-1 pl-5">
                    <li v-html="t('privacy.s2.item1')" />
                    <li v-html="t('privacy.s2.item2')" />
                    <li v-html="t('privacy.s2.item3')" />
                    <li v-html="t('privacy.s2.item4')" />
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('privacy.s3.title') }}
                </h2>
                <ul class="list-disc space-y-1 pl-5">
                    <li v-html="t('privacy.s3.item1')" />
                    <li v-html="t('privacy.s3.item2')" />
                    <li v-html="t('privacy.s3.item3')" />
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('privacy.s4.title') }}
                </h2>
                <p>{{ t('privacy.s4.text') }}</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('privacy.s5.title') }}
                </h2>
                <p>
                    {{ t('privacy.s5.pre') }}
                    <a
                        href="mailto:hola@foodtruck.es"
                        class="text-amber-600 hover:underline"
                        >hola@foodtruck.es</a
                    >. {{ t('privacy.s5.mid') }}
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('privacy.s6.title') }}
                </h2>
                <p>
                    {{ t('privacy.s6.pre') }} <strong>Stripe</strong>.
                    {{ t('privacy.s6.mid') }}
                    <a
                        href="https://stripe.com/es/privacy"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-amber-600 hover:underline"
                        >stripe.com/es/privacy</a
                    >.
                </p>
            </section>
        </div>

        <div class="mt-10">
            <a href="/" class="text-amber-500 hover:underline">{{
                t('legal.back')
            }}</a>
        </div>
    </div>
</template>
