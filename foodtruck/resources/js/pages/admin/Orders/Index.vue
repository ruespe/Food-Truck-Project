<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

type Order = {
    id: number;
    total_price: number;
    status: string;
    payment_method: string;
    created_at: string;
    user: { name: string; email: string };
    items: Array<{ quantity: number; product: { name: string } }>;
};

type Paginator = {
    data: Order[];
    current_page: number;
    last_page: number;
    total: number;
    links: Array<{ url: string | null; label: string; active: boolean }>;
};

defineProps<{ orders: Paginator }>();

const statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];

const statusLabel: Record<string, string> = {
    pending:   'Pendiente',
    confirmed: 'Confirmado',
    preparing: 'Preparando',
    ready:     'Listo',
    delivered: 'Entregado',
    cancelled: 'Cancelado',
};

const statusColor: Record<string, string> = {
    pending:   'bg-yellow-500/20 text-yellow-300',
    confirmed: 'bg-blue-500/20 text-blue-300',
    preparing: 'bg-orange-500/20 text-orange-300',
    ready:     'bg-green-500/20 text-green-300',
    delivered: 'bg-slate-600 text-slate-300',
    cancelled: 'bg-red-500/20 text-red-300',
};

function updateStatus(orderId: number, status: string) {
    router.patch(`/admin/orders/${orderId}/status`, { status }, { preserveScroll: true });
}
</script>

<template>
    <Head title="Pedidos · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pedidos</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ orders.total }} pedidos en total
            </p>
        </div>
    </div>

    <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Productos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Fecha</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <tr v-for="order in orders.data" :key="order.id" class="transition hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ order.id }}</td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900 dark:text-white">{{ order.user.name }}</p>
                            <p class="text-xs text-slate-500">{{ order.user.email }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                            <span v-for="(item, i) in order.items" :key="i">
                                {{ item.quantity }}× {{ item.product.name }}<span v-if="i < order.items.length - 1">, </span>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-amber-600 dark:text-amber-400">{{ parseFloat(String(order.total_price)).toFixed(2) }} €</td>
                        <td class="px-6 py-4">
                            <select
                                :value="order.status"
                                :class="['rounded-lg border-0 px-2.5 py-1.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500', statusColor[order.status] ?? 'bg-slate-700 text-slate-300']"
                                @change="updateStatus(order.id, ($event.target as HTMLSelectElement).value)"
                            >
                                <option v-for="s in statuses" :key="s" :value="s" class="bg-slate-800 text-white">{{ statusLabel[s] }}</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400">{{ order.created_at }}</td>
                        <td class="px-6 py-4">
                            <Link
                                :href="`/admin/orders/${order.id}`"
                                class="rounded-lg bg-slate-100 dark:bg-slate-700 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-amber-500/10 hover:text-amber-600 dark:hover:text-amber-400 transition whitespace-nowrap"
                            >
                                Ver detalle →
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="orders.data.length === 0">
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">No hay pedidos aún</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="orders.last_page > 1" class="flex items-center justify-between border-t border-slate-200 dark:border-slate-700 px-6 py-4">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Página {{ orders.current_page }} de {{ orders.last_page }}
            </p>
            <div class="flex gap-1">
                <template v-for="link in orders.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-scroll
                        :class="[
                            'flex h-8 min-w-8 items-center justify-center rounded-lg px-2 text-xs font-medium transition',
                            link.active
                                ? 'bg-amber-500 text-white'
                                : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-amber-500/10 hover:text-amber-600 dark:hover:text-amber-400',
                        ]"
                        v-html="link.label"
                    />
                    <span
                        v-else
                        class="flex h-8 min-w-8 items-center justify-center rounded-lg px-2 text-xs font-medium text-slate-300 dark:text-slate-600"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>
    </div>
</template>