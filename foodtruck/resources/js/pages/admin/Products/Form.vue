<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    product?: {
        id: number;
        name: string;
        description: string;
        price: number;
        stock: number;
        available: boolean;
        category_id: number;
        image: string | null;
    };
    categories: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    name: props.product?.name ?? '',
    description: props.product?.description ?? '',
    price: props.product?.price ?? '',
    stock: props.product?.stock ?? 0,
    available: props.product?.available ?? true,
    category_id: props.product?.category_id ?? '',
    image: null as File | null,
});

function submit() {
    if (props.product) {
        form.post(`/admin/products/${props.product.id}`, { forceFormData: true, _method: 'PUT' } as any);
    } else {
        form.post('/admin/products', { forceFormData: true } as any);
    }
}
</script>

<template>
    <Head :title="product ? 'Editar producto · Admin' : 'Nuevo producto · Admin'" />
    <template #header>
        <h1 class="text-lg font-semibold text-gray-800">{{ product ? 'Editar producto' : 'Nuevo producto' }}</h1>
    </template>

    <form class="mx-auto max-w-2xl rounded-2xl bg-white p-8 shadow" @submit.prevent="submit">
        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-gray-700">Nombre</label>
            <input v-model="form.name" type="text" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
        </div>

        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-gray-700">Descripción</label>
            <textarea v-model="form.description" rows="3" class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Precio (€)</label>
                <input v-model="form.price" type="number" step="0.01" min="0" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Stock</label>
                <input v-model="form.stock" type="number" min="0" class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            </div>
        </div>

        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-gray-700">Categoría</label>
            <select v-model="form.category_id" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none">
                <option value="" disabled>Selecciona categoría</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-gray-700">Imagen</label>
            <input type="file" accept="image/*" class="w-full text-sm" @change="(e: Event) => form.image = (e.target as HTMLInputElement).files?.[0] ?? null" />
        </div>

        <div class="mb-6 flex items-center gap-2">
            <input id="available" v-model="form.available" type="checkbox" class="rounded" />
            <label for="available" class="text-sm font-medium text-gray-700">Disponible para la venta</label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="/admin/products" class="rounded-lg border px-5 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" :disabled="form.processing" class="rounded-lg bg-amber-500 px-6 py-2 text-sm font-bold text-white hover:bg-amber-600 disabled:opacity-50">
                {{ product ? 'Guardar cambios' : 'Crear producto' }}
            </button>
        </div>
    </form>
</template>
