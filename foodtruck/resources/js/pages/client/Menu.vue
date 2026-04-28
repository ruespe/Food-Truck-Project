<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { useCart } from '@/composables/useCart';
import { useI18n } from '@/composables/useI18n';
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
const { t } = useI18n();
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
        <h1 class="mb-6 text-3xl font-bold text-gray-800 dark:text-white">{{ t('menu.title') }}</h1>

        <!-- Filtro categorías -->
        <div class="mb-8 flex flex-wrap gap-2">
            <button
                :class="['rounded-full px-4 py-1.5 text-sm font-medium transition', selectedCategory === null ? 'bg-amber-500 text-white' : 'border bg-white text-gray-600 hover:border-amber-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200']"
                @click="selectedCategory = null"
            >
                {{ t('menu.all') }}
            </button>
            <button
                v-for="cat in categories"
                :key="cat.id"
                :class="['rounded-full px-4 py-1.5 text-sm font-medium transition', selectedCategory === cat.id ? 'bg-amber-500 text-white' : 'border bg-white text-gray-600 hover:border-amber-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200']"
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
                class="overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800"
            >
                <div class="flex h-44 items-center justify-center bg-amber-100 dark:bg-amber-900/30">
                    <img v-if="product.image" :src="product.image" class="h-full w-full object-cover" :alt="product.name" />
                    <span v-else class="text-5xl">🍽️</span>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ product.name }}</h3>
                    <p class="mt-1 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">{{ product.description }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-bold text-amber-600">{{ parseFloat(String(product.price)).toFixed(2) }} €</span>
                        <button
                            v-if="product.available"
                            class="rounded-full px-4 py-1.5 text-sm font-semibold transition"
                            :class="added.has(product.id) ? 'bg-green-500 text-white' : 'bg-amber-500 text-white hover:bg-amber-600'"
                            @click="addToCart(product)"
                        >
                            {{ added.has(product.id) ? t('menu.added') : t('menu.add') }}
                        </button>
                        <span v-else class="text-sm text-gray-400 dark:text-gray-500">{{ t('menu.notAvailable') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ir al carrito -->
        <div v-if="count > 0" class="fixed bottom-6 right-6">
            <a href="/cart" class="flex items-center gap-2 rounded-full bg-amber-500 px-6 py-3 font-bold text-white shadow-xl hover:bg-amber-600">
                🛒 {{ t('menu.seeCart') }} ({{ count }})
            </a>
        </div>
    </div>
</template>
