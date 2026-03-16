<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({ layout: ClientLayout });

defineProps<{
    order: { id: number };
}>();
</script>

<template>
    <Head title="Pago cancelado · FoodTruck" />
    <div class="flex min-h-[60vh] items-center justify-center px-4">
        <div class="rounded-3xl bg-white p-10 text-center shadow-xl">
            <div class="mb-4 text-6xl">😔</div>
            <h1 class="mb-2 text-2xl font-bold text-gray-800">Pago cancelado</h1>
            <p class="mb-6 text-gray-500">El pedido #{{ order.id }} no ha sido pagado.</p>
            <div class="flex justify-center gap-3">
                <a :href="`/checkout/${order.id}`" class="rounded-full bg-amber-500 px-6 py-3 font-bold text-white hover:bg-amber-600">
                    Reintentar pago
                </a>
                <a href="/menu" class="rounded-full border border-amber-500 px-6 py-3 font-bold text-amber-500 hover:bg-amber-50">
                    Volver al menú
                </a>
            </div>
        </div>
    </div>
</template>
