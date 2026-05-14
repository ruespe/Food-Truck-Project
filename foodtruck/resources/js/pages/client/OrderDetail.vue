<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useI18n, td } from '@/composables/useI18n';
import type { MessageKey } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

const props = defineProps<{
    order: {
        id: number;
        total_price: number;
        status: string;
        items: Array<{
            id: number;
            quantity: number;
            price: number;
            product: { name: Record<string, string> };
        }>;
        payment: { status: string; transaction_id: string | null } | null;
    };
}>();

const { t } = useI18n();
function statusLabel(status: string): string {
    return t(`status.${status}` as MessageKey);
}

function cancelOrder() {
    if (confirm(t('orderDetail.cancelConfirm'))) {
        router.delete(`/orders/${props.order.id}`);
    }
}
</script>

<template>
    <Head title="Detalle del pedido · FoodTruck" />
    <div class="mx-auto max-w-xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800 dark:text-white">
            {{ t('orderDetail.title') }}{{ order.id }}
        </h1>
        <div class="rounded-2xl bg-white p-6 shadow dark:bg-gray-800">
            <ul class="mb-4 divide-y dark:divide-gray-700">
                <li
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex justify-between py-3 text-sm dark:text-gray-300"
                >
                    <span
                        >{{ item.quantity }} × {{ td(item.product.name) }}</span
                    >
                    <span class="font-semibold"
                        >{{
                            (
                                parseFloat(String(item.price)) * item.quantity
                            ).toFixed(2)
                        }}
                        €</span
                    >
                </li>
            </ul>
            <div
                class="mt-3 flex justify-between rounded-xl bg-orange-50 px-3 py-3 text-lg font-bold dark:bg-orange-900/10 dark:text-white"
            >
                <span>{{ t('orderDetail.total') }}</span>
                <span class="text-orange-600"
                    >{{
                        parseFloat(String(order.total_price)).toFixed(2)
                    }}
                    €</span
                >
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                {{ t('orderDetail.status') }}:
                <span
                    class="font-medium text-gray-700 capitalize dark:text-gray-200"
                    >{{ statusLabel(order.status) }}</span
                >
            </div>

            <!-- Cancelar pedido (solo si está pendiente) -->
            <div
                v-if="order.status === 'pending'"
                class="mt-5 border-t pt-4 dark:border-gray-700"
            >
                <button
                    class="w-full rounded-xl border border-red-300 py-2 text-sm font-semibold text-red-500 transition hover:bg-red-50 dark:border-red-500/30 dark:text-red-400 dark:hover:bg-red-500/10"
                    @click="cancelOrder"
                >
                    {{ t('orderDetail.cancel') }}
                </button>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="/orders" class="text-amber-500 underline">{{
                t('orderDetail.back')
            }}</a>
        </div>
    </div>
</template>
