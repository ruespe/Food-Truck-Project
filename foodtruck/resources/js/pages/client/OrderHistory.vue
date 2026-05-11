<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useI18n, td } from '@/composables/useI18n';
import type { MessageKey } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

defineProps<{
    orders: Array<{
        id: number;
        total_price: number;
        status: string;
        payment_method: string;
        created_at: string;
        items: Array<{
            id: number;
            quantity: number;
            price: number;
            product: { name: Record<string, string> };
        }>;
    }>;
}>();

const { t } = useI18n();

const statusColor: Record<string, string> = {
    confirmed:
        'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
    preparing:
        'bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400',
    ready: 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
    delivered: 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
    cancelled: 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
};

function statusLabel(status: string): string {
    const key = `status.${status}` as MessageKey;

    return t(key);
}
</script>

<template>
    <Head title="Mis pedidos · FoodTruck" />
    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800 dark:text-white">
            {{ t('orders.title') }}
        </h1>

        <div
            v-if="orders.length === 0"
            class="rounded-2xl bg-white p-10 text-center text-gray-500 shadow dark:bg-gray-800 dark:text-gray-400"
        >
            {{ t('orders.empty') }}
            <a href="/menu" class="text-amber-500 underline">{{
                t('orders.seeMenu')
            }}</a>
        </div>

        <ul v-else class="space-y-4">
            <li v-for="order in orders" :key="order.id">
                <Link
                    :href="`/orders/${order.id}`"
                    class="group block rounded-2xl bg-white p-5 shadow transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-2 hover:shadow-amber-500/10 hover:ring-amber-400/40 dark:bg-gray-800 dark:hover:shadow-amber-500/5 dark:hover:ring-amber-500/30"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p
                                class="text-sm text-gray-400 transition-colors duration-300 group-hover:text-amber-500"
                            >
                                # {{ order.id }}
                            </p>
                            <p
                                class="font-semibold text-gray-800 transition-colors duration-300 group-hover:text-amber-600 dark:text-white"
                            >
                                {{ order.created_at }}
                            </p>
                        </div>
                        <span
                            :class="[
                                'rounded-full px-3 py-1 text-xs font-semibold',
                                statusColor[order.status] ??
                                    'bg-gray-100 text-gray-600',
                            ]"
                        >
                            {{ statusLabel(order.status) }}
                        </span>
                    </div>
                    <ul
                        class="mt-3 space-y-1 text-sm text-gray-600 dark:text-gray-400"
                    >
                        <li v-for="item in order.items" :key="item.id">
                            {{ item.quantity }} × {{ td(item.product.name) }} —
                            {{
                                (
                                    parseFloat(String(item.price)) *
                                    item.quantity
                                ).toFixed(2)
                            }}
                            €
                        </li>
                    </ul>
                    <div
                        class="mt-3 origin-right text-right text-lg font-bold text-amber-600 transition-all duration-300 group-hover:scale-[1.02] group-hover:text-amber-500"
                    >
                        {{ t('orders.total') }}
                        {{ parseFloat(String(order.total_price)).toFixed(2) }} €
                    </div>
                </Link>
            </li>
        </ul>
    </div>
</template>
