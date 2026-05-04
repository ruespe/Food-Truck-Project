<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ImagePlaceholder from '@/components/ImagePlaceholder.vue';
import { td } from '@/composables/useI18n';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    order: {
        id: number;
        total_price: number;
        status: string;
        payment_method: string;
        created_at: string;
        updated_at: string;
        user: { id: number; name: string; email: string };
        items: Array<{
            id: number;
            quantity: number;
            price: number;
            product: { id: number; name: string; image: string | null; description: string };
        }>;
        payment: { status: string; transaction_id: string | null } | null;
    };
    statuses: string[];
}>();

const statusLabel: Record<string, string> = {
    pending:   'Pendiente',
    confirmed: 'Confirmado',
    preparing: 'Preparando',
    ready:     'Listo',
    delivered: 'Entregado',
    cancelled: 'Cancelado',
};

const statusColor: Record<string, string> = {
    pending:   'bg-yellow-500/10 text-yellow-500 ring-1 ring-yellow-500/30',
    confirmed: 'bg-blue-500/10 text-blue-400 ring-1 ring-blue-500/30',
    preparing: 'bg-orange-500/10 text-orange-400 ring-1 ring-orange-500/30',
    ready:     'bg-green-500/10 text-green-400 ring-1 ring-green-500/30',
    delivered: 'bg-slate-500/10 text-slate-400 ring-1 ring-slate-500/30',
    cancelled: 'bg-red-500/10 text-red-400 ring-1 ring-red-500/30',
};

const paymentStatusColor: Record<string, string> = {
    paid:    'bg-green-500/10 text-green-400',
    unpaid:  'bg-yellow-500/10 text-yellow-500',
    failed:  'bg-red-500/10 text-red-400',
};

function updateStatus(status: string) {
    router.patch(`/admin/orders/${props.order.id}/status`, { status }, { preserveScroll: true });
}
</script>

<template>
    <Head :title="`Pedido #${order.id} · Admin`" />

    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
        <Link href="/admin/orders" class="hover:text-amber-500 transition">Pedidos</Link>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-medium">#{{ order.id }}</span>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        <!-- LEFT: items -->
        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900 dark:text-white">Productos del pedido</h2>
                    <span class="text-xs text-slate-400">{{ order.items.length }} líneas</span>
                </div>

                <ul class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <li v-for="item in order.items" :key="item.id" class="flex items-center gap-4 px-6 py-4">
                        <!-- Product image -->
                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-700">
                            <img
                                v-if="item.product.image"
                                :src="item.product.image?.startsWith('http') ? item.product.image : `/storage/${item.product.image}`"
                                :alt="item.product.name"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center"><ImagePlaceholder class="h-8 w-8 text-amber-300 dark:text-amber-700" /></div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 dark:text-white truncate">{{ item.product.name }}</p>
                            <p class="mt-0.5 text-xs text-slate-400 line-clamp-1">{{ td(item.product.description) }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                {{ parseFloat(String(item.price)).toFixed(2) }} € × {{ item.quantity }}
                            </p>
                        </div>

                        <!-- Subtotal -->
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-amber-600 dark:text-amber-400">
                                {{ (item.price * item.quantity).toFixed(2) }} €
                            </p>
                        </div>
                    </li>
                </ul>

                <!-- Total -->
                <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Total del pedido</span>
                    <span class="text-xl font-extrabold text-amber-600 dark:text-amber-400">
                        {{ parseFloat(String(order.total_price)).toFixed(2) }} €
                    </span>
                </div>
            </div>
        </div>

        <!-- RIGHT: meta + actions -->
        <div class="space-y-4">

            <!-- Estado -->
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 shadow-sm p-5">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Estado</h3>
                <span :class="['rounded-full px-3 py-1 text-sm font-semibold', statusColor[order.status] ?? '']">
                    {{ statusLabel[order.status] ?? order.status }}
                </span>

                <div class="mt-4">
                    <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400">Cambiar estado</label>
                    <select
                        :value="order.status"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500"
                        @change="updateStatus(($event.target as HTMLSelectElement).value)"
                    >
                        <option v-for="s in statuses" :key="s" :value="s">{{ statusLabel[s] }}</option>
                    </select>
                </div>
            </div>

            <!-- Cliente -->
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 shadow-sm p-5">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Cliente</h3>
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-500/15 text-sm font-bold text-amber-600 dark:text-amber-400">
                        {{ order.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">{{ order.user.name }}</p>
                        <p class="text-xs text-slate-400">{{ order.user.email }}</p>
                    </div>
                </div>
            </div>

            <!-- Pago -->
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 shadow-sm p-5">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Pago</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Método</span>
                        <span class="font-medium capitalize text-slate-900 dark:text-white">{{ order.payment_method }}</span>
                    </div>
                    <div v-if="order.payment" class="flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Estado</span>
                        <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold', paymentStatusColor[order.payment.status] ?? 'bg-slate-500/10 text-slate-400']">
                            {{ order.payment.status }}
                        </span>
                    </div>
                    <div v-if="order.payment?.transaction_id" class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">ID transacción</span>
                        <span class="font-mono text-xs text-slate-500 truncate max-w-[130px]">{{ order.payment.transaction_id }}</span>
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 shadow-sm p-5">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Fechas</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Creado</span>
                        <span class="text-slate-900 dark:text-white">{{ order.created_at }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Actualizado</span>
                        <span class="text-slate-900 dark:text-white">{{ order.updated_at }}</span>
                    </div>
                </div>
            </div>

            <Link
                href="/admin/orders"
                class="flex w-full items-center justify-center rounded-xl border border-slate-200 dark:border-slate-700 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-400 hover:border-amber-500 hover:text-amber-600 dark:hover:text-amber-400 transition"
            >
                ← Volver a pedidos
            </Link>
        </div>
    </div>
</template>
