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
    <template #header><h1 class="text-lg font-semibold text-gray-800">Productos</h1></template>

    <div class="mb-4 flex justify-end">
        <Link href="/admin/products/create" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600">
            + Nuevo producto
        </Link>
    </div>

    <div class="rounded-2xl bg-white shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500">
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Categoría</th>
                    <th class="px-4 py-3">Precio</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Disponible</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ product.name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ product.category?.name }}</td>
                    <td class="px-4 py-3 font-semibold text-amber-600">{{ parseFloat(String(product.price)).toFixed(2) }} €</td>
                    <td class="px-4 py-3">{{ product.stock }}</td>
                    <td class="px-4 py-3">
                        <span :class="product.available ? 'text-green-600' : 'text-red-400'">
                            {{ product.available ? '✓ Sí' : '✕ No' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <Link :href="`/admin/products/${product.id}/edit`" class="rounded px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200">Editar</Link>
                            <button class="rounded px-3 py-1 text-xs font-medium bg-red-100 text-red-600 hover:bg-red-200" @click="destroy(product.id)">Eliminar</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
