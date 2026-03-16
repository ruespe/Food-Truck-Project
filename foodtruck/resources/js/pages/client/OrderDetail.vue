<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({ layout: ClientLayout });

defineProps<{
    order: {
        id: number;
        total_price: number;
        status: string;
        items: Array<{ id: number; quantity: number; price: number; product: { name: string } }>;
        payment: { status: string; transaction_id: string | null } | null;
    };
}>();
</script>

<template>
    <Head title="Detalle del pedido · FoodTruck" />
    <div class="mx-auto max-w-xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Pedido #{{ order.id }}</h1>
        <div class="rounded-2xl bg-white p-6 shadow">
            <ul class="mb-4 divide-y">
                <li v-for="item in order.items" :key="item.id" class="flex justify-between py-3 text-sm">
                    <span>{{ item.quantity }} × {{ item.product.name }}</span>
                    <span class="font-semibold">{{ (parseFloat(String(item.price)) * item.quantity).toFixed(2) }} €</span>
                </li>
            </ul>
            <div class="flex justify-between border-t pt-3 text-lg font-bold">
                <span>Total</span>
                <span class="text-amber-600">{{ parseFloat(String(order.total_price)).toFixed(2) }} €</span>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Estado: <span class="font-medium capitalize text-gray-700">{{ order.status }}</span>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="/orders" class="text-amber-500 underline">← Volver a mis pedidos</a>
        </div>
    </div>
</template>
