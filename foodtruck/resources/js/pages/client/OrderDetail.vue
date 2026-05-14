<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { useI18n, td } from '@/composables/useI18n';
import type { MessageKey } from '@/composables/useI18n';
import { Head, Link, router } from '@inertiajs/vue3';

defineOptions({ layout: ClientLayout });

const props = defineProps<{
    order: {
        id: number;
        total_price: number;
        status: string;
        created_at: string;
        updated_at: string;
        items: Array<{
            id: number;
            quantity: number;
            price: number;
            product: { name: Record<string, string>; image: string | null };
        }>;
        payment: { status: string; transaction_id: string | null } | null;
    };
}>();

const { t } = useI18n();

const statusConfig: Record<string, { label: string; classes: string; dot: string }> = {
    pending:   { label: 'status.pending',   classes: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',  dot: 'bg-yellow-400' },
    confirmed: { label: 'status.confirmed', classes: 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',         dot: 'bg-blue-400' },
    preparing: { label: 'status.preparing', classes: 'bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400', dot: 'bg-orange-400' },
    ready:     { label: 'status.ready',     classes: 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',     dot: 'bg-green-400' },
    delivered: { label: 'status.delivered', classes: 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',            dot: 'bg-gray-400' },
    cancelled: { label: 'status.cancelled', classes: 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',             dot: 'bg-red-400' },
};

function getStatus(s: string) {
    return statusConfig[s] ?? { label: s, classes: 'bg-gray-100 text-gray-600', dot: 'bg-gray-400' };
}

function cancelOrder() {
    if (confirm(t('orderDetail.cancelConfirm'))) {
        router.delete(`/orders/${props.order.id}`);
    }
}
</script>

<template>
    <Head title="Detalle del pedido · FoodTruck" />

    <div class="mx-auto max-w-2xl px-4 py-10">

        <!-- Back -->
        <Link href="/orders" class="mb-6 inline-flex items-center gap-2 text-sm text-gray-500 transition hover:text-amber-500 dark:text-gray-400">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ t('orderDetail.back') }}
        </Link>

        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                    {{ t('orderDetail.title') }}<span class="text-amber-500">{{ order.id }}</span>
                </h1>
                <p class="mt-0.5 text-sm text-gray-400">{{ order.created_at }}</p>
            </div>
            <!-- Status badge -->
            <span :class="['inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-semibold', getStatus(order.status).classes]">
                <span :class="['h-2 w-2 rounded-full', getStatus(order.status).dot]"></span>
                {{ t(getStatus(order.status).label as MessageKey) }}
            </span>
        </div>

        <!-- Products card -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700">

            <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                <h2 class="text-sm font-semibold uppercase tracking-widest text-gray-400">{{ t('orderDetail.products') }}</h2>
            </div>

            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                <li v-for="item in order.items" :key="item.id" class="flex items-center gap-4 px-6 py-4">
                    <!-- Product image -->
                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-700">
                        <img
                            v-if="item.product.image"
                            :src="item.product.image"
                            :alt="td(item.product.name)"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="flex h-full w-full items-center justify-center text-2xl">🍔</div>
                    </div>

                    <!-- Name + qty -->
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-semibold text-gray-800 dark:text-white">{{ td(item.product.name) }}</p>
                        <p class="mt-0.5 text-sm text-gray-400">
                            {{ item.quantity }} × {{ parseFloat(String(item.price)).toFixed(2) }} €
                        </p>
                    </div>

                    <!-- Line total -->
                    <p class="flex-shrink-0 font-bold text-gray-800 dark:text-white">
                        {{ (parseFloat(String(item.price)) * item.quantity).toFixed(2) }} €
                    </p>
                </li>
            </ul>

            <!-- Total -->
            <div class="flex items-center justify-between border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/60">
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ t('orderDetail.total') }}</span>
                <span class="text-xl font-extrabold text-amber-500">{{ parseFloat(String(order.total_price)).toFixed(2) }} €</span>
            </div>
        </div>

        <!-- Payment info -->
        <div v-if="order.payment" class="mt-4 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                <h2 class="text-sm font-semibold uppercase tracking-widest text-gray-400">{{ t('orderDetail.payment') }}</h2>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="font-medium">Stripe</span>
                </div>
                <span v-if="order.payment.transaction_id" class="font-mono text-xs text-gray-400">{{ order.payment.transaction_id }}</span>
            </div>
        </div>

        <!-- Cancel -->
        <div v-if="order.status === 'pending'" class="mt-4">
            <button
                class="w-full rounded-2xl border border-red-200 py-3 text-sm font-semibold text-red-500 transition hover:bg-red-50 dark:border-red-500/20 dark:text-red-400 dark:hover:bg-red-500/10"
                @click="cancelOrder"
            >
                {{ t('orderDetail.cancel') }}
            </button>
        </div>

    </div>
</template>
