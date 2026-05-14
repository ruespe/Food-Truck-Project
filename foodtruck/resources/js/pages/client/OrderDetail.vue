<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';
import { useCart } from '@/composables/useCart';
import { useI18n, td } from '@/composables/useI18n';
import type { MessageKey } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

const props = defineProps<{
    order: {
        id: number;
        ref: string;
        total_price: number;
        status: string;
        payment_method: string;
        created_at: string;
        updated_at: string;
        items: Array<{
            id: number;
            quantity: number;
            price: number;
            product: {
                id: number;
                name: Record<string, string>;
                image: string | null;
                price: number;
            };
        }>;
        payment: { status: string; transaction_id: string | null } | null;
    };
}>();

const { t } = useI18n();
const { add } = useCart();

const statusConfig: Record<
    string,
    { label: string; classes: string; dot: string }
> = {
    pending: {
        label: 'status.pending',
        classes:
            'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
        dot: 'bg-yellow-400',
    },
    confirmed: {
        label: 'status.confirmed',
        classes:
            'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
        dot: 'bg-blue-400',
    },
    preparing: {
        label: 'status.preparing',
        classes:
            'bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400',
        dot: 'bg-orange-400',
    },
    ready: {
        label: 'status.ready',
        classes:
            'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
        dot: 'bg-green-400',
    },
    delivered: {
        label: 'status.delivered',
        classes:
            'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
        dot: 'bg-gray-400',
    },
    cancelled: {
        label: 'status.cancelled',
        classes: 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
        dot: 'bg-red-400',
    },
};

function getStatus(s: string) {
    return (
        statusConfig[s] ?? {
            label: s,
            classes: 'bg-gray-100 text-gray-600',
            dot: 'bg-gray-400',
        }
    );
}

function cancelOrder() {
    if (confirm(t('orderDetail.cancelConfirm'))) {
        router.delete(`/orders/${props.order.id}`);
    }
}

function reorder() {
    props.order.items.forEach((item) => {
        add(
            {
                id: item.product.id,
                name: item.product.name,
                price: item.price,
                image: item.product.image,
            },
            item.quantity,
        );
    });
    router.visit('/cart');
}

function printReceipt() {
    window.print();
}

// Polling: refresh every 20s while order is in active state
const ACTIVE_STATUSES = ['pending', 'confirmed', 'preparing'];
let pollInterval: ReturnType<typeof setInterval> | null = null;

function startPolling() {
    if (ACTIVE_STATUSES.includes(props.order.status)) {
        pollInterval = setInterval(() => {
            router.reload({ only: ['order'] });
        }, 20_000);
    }
}

onMounted(startPolling);
onUnmounted(() => {
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});
</script>

<template>
    <Head title="Detalle del pedido · FoodTruck" />

    <div class="mx-auto max-w-2xl px-4 py-10">
        <!-- Back (hidden on print) -->
        <Link
            href="/orders"
            class="no-print mb-6 inline-flex items-center gap-2 text-sm text-gray-500 transition hover:text-amber-500 dark:text-gray-400"
        >
            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
            {{ t('orderDetail.back') }}
        </Link>

        <!-- Ready banner -->
        <div
            v-if="order.status === 'ready'"
            class="no-print mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-300"
        >
            <span class="text-2xl">🎉</span>
            <p class="font-semibold">{{ t('orderDetail.readyBanner') }}</p>
        </div>

        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1
                    class="text-2xl font-extrabold text-gray-900 dark:text-white"
                >
                    {{ t('orderDetail.title')
                    }}<span class="font-mono text-orange-500">{{
                        order.ref
                    }}</span>
                </h1>
                <p class="mt-0.5 text-sm text-gray-400">
                    {{ order.created_at }}
                </p>
            </div>
            <!-- Status badge -->
            <span
                :class="[
                    'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-semibold',
                    getStatus(order.status).classes,
                ]"
            >
                <span
                    :class="[
                        'h-2 w-2 rounded-full',
                        getStatus(order.status).dot,
                    ]"
                ></span>
                {{ t(getStatus(order.status).label as MessageKey) }}
            </span>
        </div>

        <!-- Products card -->
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700"
        >
            <div
                class="border-b border-gray-100 px-6 py-4 dark:border-gray-700"
            >
                <h2
                    class="text-sm font-semibold tracking-widest text-gray-400 uppercase"
                >
                    {{ t('orderDetail.products') }}
                </h2>
            </div>

            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                <li
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-center gap-4 px-6 py-4"
                >
                    <div
                        class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-700"
                    >
                        <img
                            v-if="item.product.image"
                            :src="item.product.image"
                            :alt="td(item.product.name)"
                            class="h-full w-full object-cover"
                        />
                        <div
                            v-else
                            class="flex h-full w-full items-center justify-center text-2xl"
                        >
                            🍔
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p
                            class="truncate font-semibold text-gray-800 dark:text-white"
                        >
                            {{ td(item.product.name) }}
                        </p>
                        <p class="mt-0.5 text-sm text-gray-400">
                            {{ item.quantity }} ×
                            {{ parseFloat(String(item.price)).toFixed(2) }} €
                        </p>
                    </div>
                    <p
                        class="flex-shrink-0 font-bold text-gray-800 dark:text-white"
                    >
                        {{
                            (
                                parseFloat(String(item.price)) * item.quantity
                            ).toFixed(2)
                        }}
                        €
                    </p>
                </li>
            </ul>

            <!-- Total -->
            <div
                class="flex items-center justify-between border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/60"
            >
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{
                    t('orderDetail.total')
                }}</span>
                <span class="text-xl font-extrabold text-amber-500"
                    >{{
                        parseFloat(String(order.total_price)).toFixed(2)
                    }}
                    €</span
                >
            </div>
        </div>

        <!-- Payment info -->
        <div
            class="mt-4 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700"
        >
            <div
                class="border-b border-gray-100 px-6 py-4 dark:border-gray-700"
            >
                <h2
                    class="text-sm font-semibold tracking-widest text-gray-400 uppercase"
                >
                    {{ t('orderDetail.payment') }}
                </h2>
            </div>
            <div
                class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 text-sm text-gray-600 dark:text-gray-300"
            >
                <div class="space-y-1">
                    <p class="flex items-center gap-2">
                        <span class="text-gray-400"
                            >{{ t('orderDetail.paymentMethod') }}:</span
                        >
                        <span class="font-medium">Stripe</span>
                    </p>
                    <p
                        v-if="order.payment?.transaction_id"
                        class="font-mono text-xs text-gray-400"
                    >
                        {{ order.payment.transaction_id }}
                    </p>
                </div>
                <span
                    v-if="order.payment"
                    :class="[
                        'rounded-full px-2.5 py-0.5 text-xs font-semibold',
                        order.payment.status === 'paid'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-gray-100 text-gray-600',
                    ]"
                >
                    {{ order.payment.status }}
                </span>
            </div>
        </div>

        <!-- Action buttons (hidden on print) -->
        <div class="no-print mt-6 flex flex-col gap-3 sm:flex-row">
            <button
                @click="reorder"
                class="flex-1 rounded-2xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white shadow transition hover:bg-orange-600"
            >
                ↺ {{ t('orderDetail.reorder') }}
            </button>
            <button
                @click="printReceipt"
                class="flex-1 rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                🖨 {{ t('orderDetail.print') }}
            </button>
        </div>

        <!-- Cancel -->
        <div v-if="order.status === 'pending'" class="no-print mt-4">
            <button
                class="w-full rounded-2xl border border-red-200 py-3 text-sm font-semibold text-red-500 transition hover:bg-red-50 dark:border-red-500/20 dark:text-red-400 dark:hover:bg-red-500/10"
                @click="cancelOrder"
            >
                {{ t('orderDetail.cancel') }}
            </button>
        </div>
    </div>
</template>

<style>
@media print {
    header,
    nav,
    footer,
    .no-print {
        display: none !important;
    }
    body {
        background: white !important;
    }
}
</style>
