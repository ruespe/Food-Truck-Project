<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

type Product = {
    id: number;
    name: string;
    price: number;
    stock: number;
    available: boolean;
    image: string | null;
    category: { name: string };
};

type Paginator = {
    data: Product[];
    current_page: number;
    last_page: number;
    total: number;
    links: Array<{ url: string | null; label: string; active: boolean }>;
};

defineProps<{ products: Paginator }>();

function destroy(id: number) {
    if (confirm('¿Eliminar este producto?')) {
        router.delete(`/admin/products/${id}`);
    }
}

function toggleStock(id: number) {
    router.patch(`/admin/products/${id}/toggle-stock`, {}, { preserveScroll: true });
}
</script>

<template>
    <Head title="Productos · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Productos</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ products.total }} productos en total</p>
        </div>
        <Link href="/admin/products/create" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600 transition">
            + Nuevo producto
        </Link>
    </div>

    <div class="rounded-2xl bg-slate-800 border border-slate-700/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-700">
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Imagen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Disponible</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                <tr v-for="product in products.data" :key="product.id" class="transition hover:bg-slate-700/30">
                    <!-- Thumbnail con overlay rojo si sin stock -->
                    <td class="px-6 py-3">
                        <div class="relative h-12 w-12 overflow-hidden rounded-lg">
                            <img
                                v-if="product.image"
                                :src="product.image?.startsWith('http') ? product.image : `/storage/${product.image}`"
                                :alt="product.name"
                                class="h-full w-full object-cover"
                                :class="product.stock === 0 ? 'brightness-50' : ''"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center bg-slate-700 text-xl"
                                :class="product.stock === 0 ? 'opacity-40' : ''"
                            >🍽️</div>
                            <!-- Overlay rojo sin stock -->
                            <div
                                v-if="product.stock === 0"
                                class="absolute inset-0 flex items-center justify-center bg-red-600/60"
                            >
                                <span class="text-[9px] font-bold leading-tight text-white text-center">SIN<br>STOCK</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-white">{{ product.name }}</td>
                    <td class="px-6 py-4 text-slate-400">{{ product.category?.name }}</td>
                    <td class="px-6 py-4 font-semibold text-amber-400">{{ parseFloat(String(product.price)).toFixed(2) }} €</td>
                    <td class="px-6 py-4">
                        <span :class="product.stock === 0 ? 'text-red-400 font-semibold' : 'text-slate-300'">
                            {{ product.stock === 0 ? 'Sin stock' : product.stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span :class="product.available ? 'text-green-400' : 'text-red-400'">
                            {{ product.available ? '✓ Sí' : '✕ No' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <Link :href="`/admin/products/${product.id}/edit`" class="rounded-lg px-3 py-1 text-xs font-medium bg-slate-700 text-slate-200 hover:bg-slate-600 transition">Editar</Link>
                            <button
                                class="rounded-lg px-3 py-1 text-xs font-medium transition"
                                :class="product.stock === 0
                                    ? 'bg-green-500/15 text-green-400 hover:bg-green-500/25'
                                    : 'bg-orange-500/15 text-orange-400 hover:bg-orange-500/25'"
                                @click="toggleStock(product.id)"
                            >
                                {{ product.stock === 0 ? '↑ Reponer' : '✕ Sin stock' }}
                            </button>
                            <button class="rounded-lg px-3 py-1 text-xs font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 transition" @click="destroy(product.id)">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr v-if="products.data.length === 0">
                    <td colspan="7" class="px-6 py-10 text-center text-slate-500">No hay productos aún</td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="products.last_page > 1" class="flex items-center justify-between border-t border-slate-700 px-6 py-4">
            <p class="text-xs text-slate-400">Página {{ products.current_page }} de {{ products.last_page }}</p>
            <div class="flex gap-1">
                <template v-for="link in products.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-scroll
                        :class="[
                            'flex h-8 min-w-8 items-center justify-center rounded-lg px-2 text-xs font-medium transition',
                            link.active
                                ? 'bg-amber-500 text-white'
                                : 'bg-slate-700 text-slate-300 hover:bg-amber-500/10 hover:text-amber-400',
                        ]"
                        v-html="link.label"
                    />
                    <span
                        v-else
                        class="flex h-8 min-w-8 items-center justify-center rounded-lg px-2 text-xs font-medium text-slate-600"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>
    </div>
</template>