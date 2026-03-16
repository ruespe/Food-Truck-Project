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
    pending: 'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-blue-100 text-blue-700',
    preparing: 'bg-orange-100 text-orange-700',
    ready: 'bg-green-100 text-green-700',
    delivered: 'bg-gray-100 text-gray-600',
    cancelled: 'bg-red-100 text-red-700',
};

function updateStatus(orderId: number, status: string) {
    router.patch(`/admin/orders/${orderId}/status`, { status });
}
</script>

<template>
    <Head title="Pedidos · Admin" />
    <template #header><h1 class="text-lg font-semibold text-gray-800">Pedidos</h1></template>

    <div class="rounded-2xl bg-white shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Productos</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ order.id }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium">{{ order.user.name }}</p>
                        <p class="text-xs text-gray-400">{{ order.user.email }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-500">
                        <span v-for="(item, i) in order.items" :key="i">
                            {{ item.quantity }}× {{ item.product.name }}<span v-if="i < order.items.length - 1">, </span>
                        </span>
                    </td>
                    <td class="px-4 py-3 font-bold text-amber-600">{{ parseFloat(String(order.total_price)).toFixed(2) }} €</td>
                    <td class="px-4 py-3">
                        <select
                            :value="order.status"
                            :class="['rounded-full border-0 px-2 py-1 text-xs font-semibold', statusColor[order.status] ?? 'bg-gray-100']"
                            @change="updateStatus(order.id, ($event.target as HTMLSelectElement).value)"
                        >
                            <option v-for="s in statuses" :key="s" :value="s">{{ statusLabel[s] }}</option>
                        </select>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ order.created_at }}</td>
                </tr>
                <tr v-if="orders.length === 0">
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">No hay pedidos aún</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
