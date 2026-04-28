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

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">{{ product ? 'Editar producto' : 'Nuevo producto' }}</h1>
        <p class="mt-1 text-sm text-slate-400">{{ product ? 'Modifica los datos del producto' : 'Añade un nuevo producto al catálogo' }}</p>
    </div>

    <form class="mx-auto max-w-2xl rounded-2xl bg-slate-800 border border-slate-700/50 p-8" @submit.prevent="submit">
        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-slate-300">Nombre</label>
            <input v-model="form.name" type="text" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white placeholder-slate-400 focus:border-amber-500 focus:outline-none" />
            <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-slate-300">Descripción</label>
            <textarea v-model="form.description" rows="3" class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white placeholder-slate-400 focus:border-amber-500 focus:outline-none" />
        </div>

        <div class="mb-5 grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-300">Precio (€)</label>
                <input v-model="form.price" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white placeholder-slate-400 focus:border-amber-500 focus:outline-none" />
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-300">Stock</label>
                <input v-model="form.stock" type="number" min="0" class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white placeholder-slate-400 focus:border-amber-500 focus:outline-none" />
            </div>
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-slate-300">Categoría</label>
            <select v-model="form.category_id" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white focus:border-amber-500 focus:outline-none">
                <option value="" disabled class="text-slate-400">Selecciona categoría</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="mb-1.5 block text-sm font-medium text-slate-300">Imagen</label>
            <input type="file" accept="image/*" class="w-full text-sm text-slate-300 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-amber-600" @change="(e: Event) => form.image = (e.target as HTMLInputElement).files?.[0] ?? null" />
        </div>

        <div class="mb-6 flex items-center gap-3">
            <input id="available" v-model="form.available" type="checkbox" class="h-4 w-4 rounded border-slate-600 bg-slate-700 accent-amber-500" />
            <label for="available" class="text-sm font-medium text-slate-300">Disponible para la venta</label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="/admin/products" class="rounded-xl border border-slate-600 px-5 py-2 text-sm text-slate-300 hover:bg-slate-700 transition">Cancelar</a>
            <button type="submit" :disabled="form.processing" class="rounded-xl bg-amber-500 px-6 py-2 text-sm font-bold text-white hover:bg-amber-600 disabled:opacity-50 transition">
                {{ product ? 'Guardar cambios' : 'Crear producto' }}
            </button>
        </div>
    </form>
</template>
