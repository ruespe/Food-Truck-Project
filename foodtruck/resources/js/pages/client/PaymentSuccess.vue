<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({ layout: ClientLayout });

defineProps<{
    order: { id: number; total_price: number };
}>();
</script>

<template>
    <Head title="Pago completado · FoodTruck" />
    <div class="flex min-h-[60vh] items-center justify-center px-4">
        <div class="rounded-3xl bg-white p-10 text-center shadow-xl">
            <div class="mb-4 text-6xl">🎉</div>
            <h1 class="mb-2 text-2xl font-bold text-gray-800">¡Pago completado!</h1>
            <p class="mb-1 text-gray-500">Pedido #{{ order.id }}</p>
            <p class="mb-6 text-xl font-bold text-amber-600">
                {{ parseFloat(String(order.total_price)).toFixed(2) }} €
            </p>
            <a href="/orders" class="rounded-full bg-amber-500 px-8 py-3 font-bold text-white hover:bg-amber-600">
                Ver mis pedidos
            </a>
        </div>
    </div>
</template>
