<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { td } from '@/composables/useI18n';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    product?: {
        id: number;
        name: Record<string, string>;
        description: Record<string, string> | string;
        price: number;
        stock: boolean;
        available: boolean;
        category_id: number;
        image: string | null;
    };
    categories: Array<{ id: number; name: Record<string, string> }>;
}>();

const form = useForm({
    name: {
        es: (props.product?.name as any)?.es ?? '',
        ca: (props.product?.name as any)?.ca ?? '',
        en: (props.product?.name as any)?.en ?? '',
    },
    description: {
        es: (props.product?.description as any)?.es ?? '',
        ca: (props.product?.description as any)?.ca ?? '',
        en: (props.product?.description as any)?.en ?? '',
    },
    price: props.product?.price ?? '',
    stock: props.product?.stock ?? true,
    available: props.product?.available ?? true,
    category_id: props.product?.category_id ?? '',
    image: null as File | null,
});

function submit() {
    if (props.product) {
        form.put(`/admin/products/${props.product.id}`, {
            forceFormData: true,
        } as any);
    } else {
        form.post('/admin/products', { forceFormData: true } as any);
    }
}
</script>

<template>
    <Head
        :title="product ? 'Editar producto · Admin' : 'Nuevo producto · Admin'"
    />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            {{ product ? 'Editar producto' : 'Nuevo producto' }}
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{
                product
                    ? 'Modifica los datos del producto'
                    : 'Añade un nuevo producto al catálogo'
            }}
        </p>
    </div>

    <form
        class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
        @submit.prevent="submit"
    >
        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >Nombre</label
            >
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="w-6 shrink-0 text-center text-xs font-bold text-slate-400">ES</span>
                    <input
                        v-model="form.name.es"
                        type="text"
                        required
                        placeholder="Español"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-6 shrink-0 text-center text-xs font-bold text-slate-400">CA</span>
                    <input
                        v-model="form.name.ca"
                        type="text"
                        placeholder="Català"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-6 shrink-0 text-center text-xs font-bold text-slate-400">EN</span>
                    <input
                        v-model="form.name.en"
                        type="text"
                        placeholder="English"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                    />
                </div>
            </div>
            <p v-if="form.errors['name.es']" class="mt-1 text-xs text-red-400">
                {{ form.errors['name.es'] }}
            </p>
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >Descripción</label
            >
            <div class="space-y-2">
                <div class="flex items-start gap-2">
                    <span
                        class="mt-2.5 w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                        >ES</span
                    >
                    <textarea
                        v-model="form.description.es"
                        rows="2"
                        placeholder="Español"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                    />
                </div>
                <div class="flex items-start gap-2">
                    <span
                        class="mt-2.5 w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                        >CA</span
                    >
                    <textarea
                        v-model="form.description.ca"
                        rows="2"
                        placeholder="Català"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                    />
                </div>
                <div class="flex items-start gap-2">
                    <span
                        class="mt-2.5 w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                        >EN</span
                    >
                    <textarea
                        v-model="form.description.en"
                        rows="2"
                        placeholder="English"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                    />
                </div>
            </div>
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >Precio (€)</label
            >
            <input
                v-model="form.price"
                type="number"
                step="0.01"
                min="0"
                required
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
            />
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >Categoría</label
            >
            <select
                v-model="form.category_id"
                required
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white"
            >
                <option value="" disabled class="text-slate-400">
                    Selecciona categoría
                </option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ td(cat.name) }}
                </option>
            </select>
        </div>

        <div class="mb-6">
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >Imagen</label
            >
            <input
                type="file"
                accept="image/*"
                class="w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-amber-600 dark:text-slate-300"
                @change="
                    (e: Event) =>
                        (form.image =
                            (e.target as HTMLInputElement).files?.[0] ?? null)
                "
            />
        </div>

        <div class="mb-4 flex items-center gap-3">
            <input
                id="stock"
                v-model="form.stock"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 accent-amber-500 dark:border-slate-600 dark:bg-slate-700"
            />
            <label for="stock" class="text-sm font-medium text-slate-700 dark:text-slate-300"
                >En stock</label
            >
        </div>

        <div class="mb-6 flex items-center gap-3">
            <input
                id="available"
                v-model="form.available"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 accent-amber-500 dark:border-slate-600 dark:bg-slate-700"
            />
            <label for="available" class="text-sm font-medium text-slate-700 dark:text-slate-300"
                >Disponible para la venta</label
            >
        </div>

        <div class="flex justify-end gap-3">
            <a
                href="/admin/products"
                class="rounded-xl border border-slate-200 px-5 py-2 text-sm text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700"
                >Cancelar</a
            >
            <button
                type="submit"
                :disabled="form.processing"
                class="rounded-xl bg-amber-500 px-6 py-2 text-sm font-bold text-white transition hover:bg-amber-600 disabled:opacity-50"
            >
                {{ product ? 'Guardar cambios' : 'Crear producto' }}
            </button>
        </div>
    </form>
</template>
