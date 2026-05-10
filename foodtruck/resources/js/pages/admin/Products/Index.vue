<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import ImagePlaceholder from '@/components/ImagePlaceholder.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useI18n, td, type MessageKey } from '@/composables/useI18n';

defineOptions({ layout: AdminLayout });

type Product = {
    id: number;
    name: Record<string, string>;
    price: number;
    stock: boolean;
    available: boolean;
    image: string | null;
    category: { name: Record<string, string> };
};

type Paginator = {
    data: Product[];
    current_page: number;
    last_page: number;
    total: number;
    links: Array<{ url: string | null; label: string; active: boolean }>;
};

defineProps<{ products: Paginator }>();

const { t } = useI18n();

function destroy(id: number) {
    if (confirm(t('admin.prod.deleteConfirm' as MessageKey))) {
        router.delete(`/admin/products/${id}`);
    }
}

function toggleStock(id: number) {
    router.patch(
        `/admin/products/${id}/toggle-stock`,
        {},
        { preserveScroll: true },
    );
}
</script>

<template>
    <Head title="Productos · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ t('admin.prod.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ products.total }} {{ t('admin.prod.subtitle') }}
            </p>
        </div>
        <Link
            href="/admin/products/create"
            class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-white transition hover:bg-amber-600"
        >
            {{ t('admin.prod.new') }}
        </Link>
    </div>

    <div
        class="overflow-hidden rounded-2xl border border-slate-700/50 bg-slate-800"
    >
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-700">
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-400 uppercase"
                    >
                        {{ t('admin.prod.colImage') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-400 uppercase"
                    >
                        {{ t('admin.prod.colName') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-400 uppercase"
                    >
                        {{ t('admin.prod.colCategory') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-400 uppercase"
                    >
                        {{ t('admin.prod.colPrice') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-400 uppercase"
                    >
                        {{ t('admin.prod.colAvailable') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-400 uppercase"
                    >
                        {{ t('admin.prod.colActions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                <tr
                    v-for="product in products.data"
                    :key="product.id"
                    class="transition hover:bg-slate-700/30"
                >
                    <!-- Thumbnail con overlay rojo si sin stock -->
                    <td class="px-6 py-3">
                        <div
                            :class="[
                                'relative h-12 w-12 overflow-hidden rounded-lg',
                                (product.category?.name as any)?.es === 'Bebidas' ? 'bg-white' : 'bg-slate-700'
                            ]"
                        >
                            <img
                                v-if="product.image"
                                :src="
                                    product.image?.startsWith('http')
                                        ? product.image
                                        : `/storage/${product.image}`
                                "
                                :alt="td(product.name)"
                                :class="[
                                    'h-full w-full',
                                    (product.category?.name as any)?.es === 'Bebidas' ? 'object-contain p-1' : 'object-cover',
                                    !product.stock ? 'brightness-50' : ''
                                ]"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-slate-700"
                                :class="!product.stock ? 'opacity-40' : ''"
                            >
                                <ImagePlaceholder
                                    class="h-8 w-8 text-slate-400"
                                />
                            </div>
                            <!-- Overlay rojo sin stock -->
                            <div
                                v-if="!product.stock"
                                class="absolute inset-0 flex items-center justify-center bg-red-600/60"
                            >
                                <span
                                    class="text-center text-[9px] leading-tight font-bold text-white"
                                    >{{ t('admin.prod.noStock') }}</span
                                >
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-white">
                        {{ td(product.name) }}
                    </td>
                    <td class="px-6 py-4 text-slate-400">
                        {{ td(product.category?.name) }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-amber-400">
                        {{ parseFloat(String(product.price)).toFixed(2) }} €
                    </td>
                    <td class="px-6 py-4">
                        <span
                            :class="
                                product.available
                                    ? 'text-green-400'
                                    : 'text-red-400'
                            "
                        >
                            {{ product.available ? t('admin.prod.yes') : t('admin.prod.no') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <Link
                                :href="`/admin/products/${product.id}/edit`"
                                class="rounded-lg bg-slate-700 px-3 py-1 text-xs font-medium text-slate-200 transition hover:bg-slate-600"
                                >{{ t('admin.prod.edit') }}</Link
                            >
                            <button
                                class="rounded-lg px-3 py-1 text-xs font-medium transition"
                                :class="
                                    !product.stock
                                        ? 'bg-green-500/15 text-green-400 hover:bg-green-500/25'
                                        : 'bg-orange-500/15 text-orange-400 hover:bg-orange-500/25'
                                "
                                @click="toggleStock(product.id)"
                            >
                                {{
                                    !product.stock ? t('admin.prod.restock') : t('admin.prod.outOfStock')
                                }}
                            </button>
                            <button
                                class="rounded-lg bg-red-500/10 px-3 py-1 text-xs font-medium text-red-400 transition hover:bg-red-500/20"
                                @click="destroy(product.id)"
                            >
                                {{ t('admin.prod.delete') }}
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="products.data.length === 0">
                    <td
                        colspan="6"
                        class="px-6 py-10 text-center text-slate-500"
                    >
                        {{ t('admin.prod.empty') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div
            v-if="products.last_page > 1"
            class="flex items-center justify-between border-t border-slate-700 px-6 py-4"
        >
            <p class="text-xs text-slate-400">
                {{ t('admin.prod.page') }} {{ products.current_page }} {{ t('admin.prod.of') }} {{ products.last_page }}
            </p>
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
