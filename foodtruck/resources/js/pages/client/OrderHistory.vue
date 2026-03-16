<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({ layout: ClientLayout });

defineProps<{
    orders: Array<{
        id: number;
        total_price: number;
        status: string;
        payment_method: string;
        created_at: string;
        items: Array<{ id: number; quantity: number; price: number; product: { name: string } }>;
    }>;
}>();

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
</script>

<template>
    <Head title="Mis pedidos · FoodTruck" />
    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Mis pedidos</h1>

        <div v-if="orders.length === 0" class="rounded-2xl bg-white p-10 text-center text-gray-500 shadow">
            Aún no has realizado ningún pedido. <a href="/menu" class="text-amber-500 underline">Ver menú</a>
        </div>

        <ul v-else class="space-y-4">
            <li v-for="order in orders" :key="order.id" class="rounded-2xl bg-white p-5 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Pedido #{{ order.id }}</p>
                        <p class="font-semibold text-gray-800">{{ order.created_at }}</p>
                    </div>
                    <span :class="['rounded-full px-3 py-1 text-xs font-semibold', statusColor[order.status] ?? 'bg-gray-100 text-gray-600']">
                        {{ statusLabel[order.status] ?? order.status }}
                    </span>
                </div>
                <ul class="mt-3 space-y-1 text-sm text-gray-600">
                    <li v-for="item in order.items" :key="item.id">
                        {{ item.quantity }} × {{ item.product.name }} — {{ (parseFloat(String(item.price)) * item.quantity).toFixed(2) }} €
                    </li>
                </ul>
                <div class="mt-3 text-right text-lg font-bold text-amber-600">
                    Total: {{ parseFloat(String(order.total_price)).toFixed(2) }} €
                </div>
            </li>
        </ul>
    </div>
</template>
