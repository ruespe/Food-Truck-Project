<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

defineProps<{
    orders: Array<{
        id: number;
        total_price: number;
        status: string;
        payment_method: string;
        created_at: string;
        user: { name: string; email: string };
        items: Array<{ quantity: number; product: { name: string } }>;
    }>;
}>();

const statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];

const statusLabel: Record<string, string> = {
    pending: 'Pendiente',
    confirmed: 'Confirmado',
    preparing: 'Preparando',
    ready: 'Listo',
    delivered: 'Entregado',
    cancelled: 'Cancelado',
};

const statusColor: Record<string, string> = {
    pending: 'bg-yellow-500/20 text-yellow-300',
    confirmed: 'bg-blue-500/20 text-blue-300',
    preparing: 'bg-orange-500/20 text-orange-300',
    ready: 'bg-green-500/20 text-green-300',
    delivered: 'bg-slate-600 text-slate-300',
    cancelled: 'bg-red-500/20 text-red-300',
};

function updateStatus(orderId: number, status: string) {
    router.patch(`/admin/orders/${orderId}/status`, { status });
}
</script>

<template>
    <Head title="Pedidos · Admin" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Pedidos</h1>
        <p class="mt-1 text-sm text-slate-400">Gestiona y actualiza el estado de los pedidos</p>
    </div>

    <div class="rounded-2xl bg-slate-800 border border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Productos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <tr v-for="order in orders" :key="order.id" class="transition hover:bg-slate-700/30">
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ order.id }}</td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-white">{{ order.user.name }}</p>
                            <p class="text-xs text-slate-500">{{ order.user.email }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-400 max-w-xs">
                            <span v-for="(item, i) in order.items" :key="i">
                                {{ item.quantity }}× {{ item.product.name }}<span v-if="i < order.items.length - 1">, </span>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-amber-400">{{ parseFloat(String(order.total_price)).toFixed(2) }} €</td>
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
                    </tr>
                    <tr v-if="orders.length === 0">
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">No hay pedidos aún</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
