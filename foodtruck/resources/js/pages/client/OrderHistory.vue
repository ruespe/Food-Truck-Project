<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useI18n, td } from '@/composables/useI18n';
import type { MessageKey } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

interface OrderItem {
    id: number;
    quantity: number;
    price: number;
    product_name: Record<string, string>;
    product: {
        id: number;
        name: Record<string, string>;
        price: number;
        image: string | null;
    } | null;
}

interface Order {
    id: number;
    ref: string;
    total_price: number;
    status: string;
    payment_method: string;
    created_at: string;
    items: OrderItem[];
}

interface Paginator {
    data: Order[];
    current_page: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
    links: Array<{ url: string | null; label: string; active: boolean }>;
}

const props = defineProps<{
    orders: Paginator;
    filter: string;
}>();

const { t } = useI18n();
const { add } = useCart();

const statusColor: Record<string, string> = {
    pending:
        'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
    confirmed:
        'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
    preparing:
        'bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400',
    ready: 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
    delivered: 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
    cancelled: 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
};

function statusLabel(status: string): string {
    return t(`status.${status}` as MessageKey);
}

function setFilter(f: string) {
    router.get('/orders', f !== 'all' ? { filter: f } : {}, {
        preserveScroll: true,
    });
}

function reorder(order: Order) {
    order.items.forEach((item) => {
        if (!item.product) return; // product deleted, skip
        add(
            {
                id: item.product.id,
                name: item.product.name,
                price: item.price,
                image: item.product.image ?? null,
            },
            item.quantity,
        );
    });
    router.visit('/cart');
}
</script>

<template>
    <Head title="Mis pedidos · FoodTruck" />
    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800 dark:text-white">
            {{ t('orders.title') }}
        </h1>

        <!-- Filter tabs -->
        <div class="mb-6 flex gap-2">
            <button
                v-for="f in ['all', 'active', 'done']"
                :key="f"
                @click="setFilter(f)"
                :class="[
                    'rounded-full px-4 py-1.5 text-sm font-medium transition-colors',
                    props.filter === f
                        ? 'bg-orange-500 text-white shadow'
                        : 'bg-white text-gray-600 shadow hover:bg-orange-50 dark:bg-gray-800 dark:text-gray-300',
                ]"
            >
                {{
                    t(
                        ('orders.filter' +
                            f.charAt(0).toUpperCase() +
                            f.slice(1)) as MessageKey,
                    )
                }}
            </button>
        </div>

        <div
            v-if="orders.data.length === 0"
            class="rounded-2xl bg-white p-10 text-center text-gray-500 shadow dark:bg-gray-800 dark:text-gray-400"
        >
            {{ t('orders.empty') }}
            <a href="/menu" class="text-amber-500 underline">{{
                t('orders.seeMenu')
            }}</a>
        </div>

        <ul v-else class="space-y-4">
            <li v-for="order in orders.data" :key="order.id">
                <div class="rounded-2xl bg-white shadow dark:bg-gray-800">
                    <Link
                        :href="`/orders/${order.id}`"
                        class="group block rounded-t-2xl p-5 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:ring-2 hover:shadow-amber-500/10 hover:ring-amber-400/40 dark:hover:ring-amber-500/30"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="font-mono text-sm font-semibold text-orange-500"
                                >
                                    #{{ order.ref }}
                                </p>
                                <p
                                    class="font-semibold text-gray-800 dark:text-white"
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
                                {{ item.quantity }} ×
                                {{ item.product ? td(item.product.name) : td(item.product_name) }} —
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
                            class="mt-3 text-right text-lg font-bold text-amber-600 dark:text-amber-400"
                        >
                            {{ t('orders.total') }}
                            {{
                                parseFloat(String(order.total_price)).toFixed(2)
                            }}
                            €
                        </div>
                    </Link>

                    <!-- Reorder button -->
                    <div
                        class="border-t border-gray-100 px-5 py-3 dark:border-gray-700"
                    >
                        <button
                            @click.stop="reorder(order)"
                            class="inline-flex items-center gap-1 text-sm font-medium text-orange-500 transition-colors hover:text-orange-600"
                        >
                            ↺ {{ t('orders.reorder') }}
                        </button>
                    </div>
                </div>
            </li>
        </ul>

        <!-- Pagination -->
        <div
            v-if="orders.last_page > 1"
            class="mt-8 flex flex-wrap justify-center gap-1"
        >
            <template v-for="link in orders.links" :key="link.label">
                <button
                    v-if="link.url"
                    @click="router.visit(link.url, { preserveScroll: true })"
                    :class="[
                        'rounded-lg px-3 py-1.5 text-sm font-medium transition-colors',
                        link.active
                            ? 'bg-orange-500 text-white'
                            : 'bg-white text-gray-600 hover:bg-orange-50 dark:bg-gray-800 dark:text-gray-300',
                    ]"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="rounded-lg px-3 py-1.5 text-sm text-gray-400"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
