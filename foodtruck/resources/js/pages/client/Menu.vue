<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { useCart } from '@/composables/useCart';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineOptions({ layout: ClientLayout });

const props = defineProps<{
    categories: Array<{ id: number; name: string }>;
    products: Array<{
        id: number;
        name: string;
        description: string;
        price: number;
        image: string | null;
        available: boolean;
        category_id: number;
    }>;
}>();

const { add, count } = useCart();
const selectedCategory = ref<number | null>(null);

const filtered = computed(() =>
    selectedCategory.value
        ? props.products.filter((p) => p.category_id === selectedCategory.value)
        : props.products,
);

const added = ref<Set<number>>(new Set());
function addToCart(product: typeof props.products[number]) {
    add({ id: product.id, name: product.name, price: product.price, image: product.image });
    added.value.add(product.id);
    setTimeout(() => added.value.delete(product.id), 1500);
}
</script>

<template>
    <Head title="Menú · FoodTruck" />
    <div class="mx-auto max-w-6xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Nuestro menú</h1>

        <!-- Filtro categorías -->
        <div class="mb-8 flex flex-wrap gap-2">
            <button
                :class="['rounded-full px-4 py-1.5 text-sm font-medium transition', selectedCategory === null ? 'bg-amber-500 text-white' : 'bg-white text-gray-600 border hover:border-amber-400']"
                @click="selectedCategory = null"
            >
                Todos
            </button>
            <button
                v-for="cat in categories"
                :key="cat.id"
                :class="['rounded-full px-4 py-1.5 text-sm font-medium transition', selectedCategory === cat.id ? 'bg-amber-500 text-white' : 'bg-white text-gray-600 border hover:border-amber-400']"
                @click="selectedCategory = cat.id"
            >
                {{ cat.name }}
            </button>
        </div>

        <!-- Grid productos -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="product in filtered"
                :key="product.id"
                class="overflow-hidden rounded-2xl bg-white shadow-md"
            >
                <div class="flex h-44 items-center justify-center bg-amber-100 text-5xl">
                    <img v-if="product.image" :src="product.image" class="h-full w-full object-cover" :alt="product.name" />
                    <span v-else>🍽️</span>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800">{{ product.name }}</h3>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ product.description }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-bold text-amber-600">{{ parseFloat(String(product.price)).toFixed(2) }} €</span>
                        <button
                            v-if="product.available"
                            class="rounded-full px-4 py-1.5 text-sm font-semibold transition"
                            :class="added.has(product.id) ? 'bg-green-500 text-white' : 'bg-amber-500 text-white hover:bg-amber-600'"
                            @click="addToCart(product)"
                        >
                            {{ added.has(product.id) ? '✓ Añadido' : 'Añadir' }}
                        </button>
                        <span v-else class="text-sm text-gray-400">No disponible</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ir al carrito -->
        <div v-if="count > 0" class="fixed bottom-6 right-6">
            <a href="/cart" class="flex items-center gap-2 rounded-full bg-amber-500 px-6 py-3 font-bold text-white shadow-xl hover:bg-amber-600">
                🛒 Ver carrito ({{ count }})
            </a>
        </div>
    </div>
</template>
