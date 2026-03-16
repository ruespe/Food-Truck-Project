<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { useCart } from '@/composables/useCart';
import { Head, router } from '@inertiajs/vue3';

defineOptions({ layout: ClientLayout });

const { items, total, remove, updateQty, clear } = useCart();

function checkout() {
    router.post('/orders', {
        items: items.value.map((i) => ({ id: i.id, quantity: i.quantity })),
    }, {
        onSuccess: () => clear(),
    });
}
</script>

<template>
    <Head title="Carrito · FoodTruck" />
    <div class="mx-auto max-w-2xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Tu carrito</h1>

        <div v-if="items.length === 0" class="rounded-2xl bg-white p-10 text-center text-gray-500 shadow">
            El carrito está vacío. <a href="/menu" class="text-amber-500 underline">Ver menú</a>
        </div>

        <div v-else>
            <ul class="mb-6 divide-y rounded-2xl bg-white shadow">
                <li v-for="item in items" :key="item.id" class="flex items-center gap-4 p-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-2xl">
                        <img v-if="item.image" :src="item.image" class="h-full w-full rounded-xl object-cover" :alt="item.name" />
                        <span v-else>🍽️</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ item.name }}</p>
                        <p class="text-sm text-amber-600">{{ parseFloat(String(item.price)).toFixed(2) }} € / ud</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="rounded-full bg-gray-100 px-2 py-1 text-sm font-bold hover:bg-gray-200" @click="updateQty(item.id, item.quantity - 1)">−</button>
                        <span class="w-6 text-center text-sm font-semibold">{{ item.quantity }}</span>
                        <button class="rounded-full bg-gray-100 px-2 py-1 text-sm font-bold hover:bg-gray-200" @click="updateQty(item.id, item.quantity + 1)">+</button>
                    </div>
                    <p class="w-16 text-right font-bold text-gray-700">{{ (parseFloat(String(item.price)) * item.quantity).toFixed(2) }} €</p>
                    <button class="ml-2 text-red-400 hover:text-red-600" @click="remove(item.id)">✕</button>
                </li>
            </ul>

            <div class="flex items-center justify-between rounded-2xl bg-white p-5 shadow">
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-2xl font-extrabold text-amber-600">{{ parseFloat(String(total)).toFixed(2) }} €</p>
                </div>
                <button
                    class="rounded-full bg-amber-500 px-8 py-3 font-bold text-white shadow hover:bg-amber-600"
                    @click="checkout"
                >
                    Pagar con Stripe →
                </button>
            </div>
        </div>
    </div>
</template>
