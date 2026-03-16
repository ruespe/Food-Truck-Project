<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

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
    pending: 'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-blue-100 text-blue-700',
    preparing: 'bg-orange-100 text-orange-700',
    ready: 'bg-green-100 text-green-700',
    delivered: 'bg-gray-100 text-gray-600',
    cancelled: 'bg-red-100 text-red-700',
};
</script>

<template>
    <Head title="Dashboard · Admin" />
    <template #header><h1 class="text-lg font-semibold text-gray-800">Dashboard</h1></template>

    <!-- Stat cards -->
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Pedidos totales</p>
            <p class="text-3xl font-extrabold text-gray-800">{{ stats.total_orders }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Pedidos pendientes</p>
            <p class="text-3xl font-extrabold text-yellow-600">{{ stats.pending_orders }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Productos</p>
            <p class="text-3xl font-extrabold text-gray-800">{{ stats.total_products }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Clientes</p>
            <p class="text-3xl font-extrabold text-gray-800">{{ stats.total_clients }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Ingresos</p>
            <p class="text-3xl font-extrabold text-amber-600">{{ parseFloat(String(stats.revenue)).toFixed(2) }} €</p>
        </div>
    </div>

    <!-- Últimos pedidos -->
    <div class="rounded-2xl bg-white p-6 shadow">
        <h2 class="mb-4 text-lg font-semibold text-gray-800">Últimos pedidos</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-400">
                    <th class="pb-2">#</th>
                    <th class="pb-2">Cliente</th>
                    <th class="pb-2">Total</th>
                    <th class="pb-2">Estado</th>
                    <th class="pb-2">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr v-for="order in recent_orders" :key="order.id">
                    <td class="py-2 font-medium">{{ order.id }}</td>
                    <td class="py-2">{{ order.user }}</td>
                    <td class="py-2 font-semibold text-amber-600">{{ parseFloat(String(order.total_price)).toFixed(2) }} €</td>
                    <td class="py-2">
                        <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold', statusColor[order.status] ?? 'bg-gray-100 text-gray-600']">
                            {{ order.status }}
                        </span>
                    </td>
                    <td class="py-2 text-gray-400">{{ order.created_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
