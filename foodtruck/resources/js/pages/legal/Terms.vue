<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });
const { t } = useI18n();
</script>

<template>
    <Head :title="`${t('terms.title')} · FoodTruck`" />
    <div class="mx-auto max-w-3xl px-4 py-14">
        <h1 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">
            {{ t('terms.title') }}
        </h1>
        <p class="mb-8 text-sm text-gray-400">{{ t('legal.updated') }}</p>

        <div class="space-y-6 text-gray-700 dark:text-gray-300">
            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('terms.s1.title') }}
                </h2>
                <p>{{ t('terms.s1.text') }}</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('terms.s2.title') }}
                </h2>
                <p>{{ t('terms.s2.text') }}</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('terms.s3.title') }}
                </h2>
                <ul class="list-disc space-y-1 pl-5">
                    <li>{{ t('terms.s3.item1') }}</li>
                    <li>{{ t('terms.s3.item2') }}</li>
                    <li>{{ t('terms.s3.item3') }}</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('terms.s4.title') }}
                </h2>
                <ul class="list-disc space-y-1 pl-5">
                    <li>{{ t('terms.s4.item1') }}</li>
                    <li>{{ t('terms.s4.item2') }}</li>
                    <li>{{ t('terms.s4.item3') }}</li>
                    <li>{{ t('terms.s4.item4') }}</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('terms.s5.title') }}
                </h2>
                <p>{{ t('terms.s5.text') }}</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('terms.s6.title') }}
                </h2>
                <p>{{ t('terms.s6.text') }}</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('terms.s7.title') }}
                </h2>
                <p>{{ t('terms.s7.text') }}</p>
            </section>
        </div>

        <div class="mt-10">
            <a href="/" class="text-amber-500 hover:underline">{{
                t('legal.back')
            }}</a>
        </div>
    </div>
</template>
