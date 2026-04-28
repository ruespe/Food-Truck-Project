<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

defineProps<{
    products: Array<{
        id: number;
        name: string;
        price: number;
        stock: number;
        available: boolean;
        category: { name: string };
    }>;
}>();

function destroy(id: number) {
    if (confirm('¿Eliminar este producto?')) {
        router.delete(`/admin/products/${id}`);
    }
}
</script>

<template>
    <Head title="Productos · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Productos</h1>
            <p class="mt-1 text-sm text-slate-400">Gestiona el catálogo de productos</p>
        </div>
        <Link href="/admin/products/create" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600 transition">
            + Nuevo producto
        </Link>
    </div>

    <div class="rounded-2xl bg-slate-800 border border-slate-700/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-700">
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Disponible</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                <tr v-for="product in products" :key="product.id" class="transition hover:bg-slate-700/30">
                    <td class="px-6 py-4 font-medium text-white">{{ product.name }}</td>
                    <td class="px-6 py-4 text-slate-400">{{ product.category?.name }}</td>
                    <td class="px-6 py-4 font-semibold text-amber-400">{{ parseFloat(String(product.price)).toFixed(2) }} €</td>
                    <td class="px-6 py-4 text-slate-300">{{ product.stock }}</td>
                    <td class="px-6 py-4">
                        <span :class="product.available ? 'text-green-400' : 'text-red-400'">
                            {{ product.available ? '✓ Sí' : '✕ No' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="`/admin/products/${product.id}/edit`" class="rounded-lg px-3 py-1 text-xs font-medium bg-slate-700 text-slate-200 hover:bg-slate-600 transition">Editar</Link>
                            <button class="rounded-lg px-3 py-1 text-xs font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 transition" @click="destroy(product.id)">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr v-if="products.length === 0">
                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">No hay productos aún</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
