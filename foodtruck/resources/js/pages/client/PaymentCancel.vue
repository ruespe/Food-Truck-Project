<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

defineProps<{
    order: { id: number };
}>();

const { t } = useI18n();
</script>

<template>
    <Head title="Pago cancelado · FoodTruck" />
    <div class="flex min-h-[60vh] items-center justify-center px-4">
        <div
            class="rounded-3xl bg-white p-10 text-center shadow-xl dark:bg-gray-800"
        >
            <div class="mb-4 flex justify-center">
                <span class="text-6xl">😔</span>
            </div>
            <h1 class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
                {{ t('payment.cancel.title') }}
            </h1>
            <p class="mb-6 text-gray-500 dark:text-gray-400">
                {{ t('payment.cancel.text') }}
            </p>
            <div class="flex justify-center gap-3">
                <a
                    :href="`/checkout/${order.id}`"
                    class="rounded-full bg-amber-500 px-6 py-3 font-bold text-white hover:bg-amber-600"
                >
                    {{ t('payment.cancel.retry') }}
                </a>
                <a
                    href="/menu"
                    class="rounded-full border border-amber-500 px-6 py-3 font-bold text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                >
                    {{ t('payment.cancel.backMenu') }}
                </a>
            </div>
        </div>
    </div>
</template>
