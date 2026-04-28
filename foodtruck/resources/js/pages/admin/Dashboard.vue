<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Clock, Package, ShoppingCart, TrendingUp, Users } from 'lucide-vue-next';

defineOptions({ layout: AdminLayout });

defineProps<{
    stats: {
        total_orders: number;
        pending_orders: number;
        total_products: number;
        total_clients: number;
        revenue: number;
    };
    recent_orders: Array<{
        id: number;
        user: string;
        total_price: number;
        status: string;
        created_at: string;
    }>;
}>();

const statusColor: Record<string, string> = {
    pending: 'bg-yellow-500/10 text-yellow-400 ring-1 ring-inset ring-yellow-500/20',
    confirmed: 'bg-blue-500/10 text-blue-400 ring-1 ring-inset ring-blue-500/20',
    preparing: 'bg-orange-500/10 text-orange-400 ring-1 ring-inset ring-orange-500/20',
    ready: 'bg-green-500/10 text-green-400 ring-1 ring-inset ring-green-500/20',
    delivered: 'bg-slate-500/10 text-slate-400 ring-1 ring-inset ring-slate-500/30',
    cancelled: 'bg-red-500/10 text-red-400 ring-1 ring-inset ring-red-500/20',
};

const statusLabel: Record<string, string> = {
    pending: 'Pendiente',
    confirmed: 'Confirmado',
    preparing: 'Preparando',
    ready: 'Listo',
    delivered: 'Entregado',
    cancelled: 'Cancelado',
};
</script>

<template>
    <Head title="Dashboard · Admin" />

    <!-- Page heading -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-400">Resumen del negocio en tiempo real</p>
    </div>

    <!-- Stat cards -->
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Pedidos totales</p>
                <div class="rounded-lg bg-amber-500/10 p-1.5">
                    <ShoppingCart class="h-4 w-4 text-amber-400" />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ stats.total_orders }}</p>
        </div>

        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Pendientes</p>
                <div class="rounded-lg bg-yellow-500/10 p-1.5">
                    <Clock class="h-4 w-4 text-yellow-400" />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-yellow-400">{{ stats.pending_orders }}</p>
        </div>

        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Productos</p>
                <div class="rounded-lg bg-blue-500/10 p-1.5">
                    <Package class="h-4 w-4 text-blue-400" />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ stats.total_products }}</p>
        </div>

        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Clientes</p>
                <div class="rounded-lg bg-purple-500/10 p-1.5">
                    <Users class="h-4 w-4 text-purple-400" />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ stats.total_clients }}</p>
        </div>

        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Ingresos</p>
                <div class="rounded-lg bg-green-500/10 p-1.5">
                    <TrendingUp class="h-4 w-4 text-green-400" />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-green-400">{{ parseFloat(String(stats.revenue)).toFixed(2) }} €</p>
        </div>
    </div>

    <!-- Recent orders -->
    <div class="rounded-2xl bg-slate-800 border border-slate-700/50 overflow-hidden">
        <div class="border-b border-slate-700 px-6 py-4">
            <h2 class="font-semibold text-white">Últimos pedidos</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <tr v-for="order in recent_orders" :key="order.id" class="transition hover:bg-slate-700/30">
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ order.id }}</td>
                        <td class="px-6 py-4 font-medium text-white">{{ order.user }}</td>
                        <td class="px-6 py-4 font-semibold text-amber-400">{{ parseFloat(String(order.total_price)).toFixed(2) }} €</td>
                        <td class="px-6 py-4">
                            <span :class="['rounded-full px-2.5 py-1 text-xs font-semibold', statusColor[order.status] ?? 'bg-slate-500/10 text-slate-400']">
                                {{ statusLabel[order.status] ?? order.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ order.created_at }}</td>
                    </tr>
                    <tr v-if="recent_orders.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">No hay pedidos recientes</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
