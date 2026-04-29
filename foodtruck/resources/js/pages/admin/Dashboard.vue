<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import MapLocation from '@/components/MapLocation.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Clock, Package, ShoppingCart, TrendingUp, Users, MapPin, Check } from 'lucide-vue-next';
import { ref, watch } from 'vue';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    stats: {
        total_orders: number;
        pending_orders: number;
        total_products: number;
        total_clients: number;
        revenue: number;
    };
    recent_orders: Array<{
        id: number;
        user: string;
        total_price: number;
        status: string;
        created_at: string;
    }>;
    today_location: {
        id: number;
        name: string;
        latitude: number;
        longitude: number;
        date: string;
        start_time: string;
        end_time: string;
    } | null;
}>();

const statusColor: Record<string, string> = {
    pending:   'bg-yellow-500/10 text-yellow-400 ring-1 ring-inset ring-yellow-500/20',
    confirmed: 'bg-blue-500/10 text-blue-400 ring-1 ring-inset ring-blue-500/20',
    preparing: 'bg-orange-500/10 text-orange-400 ring-1 ring-inset ring-orange-500/20',
    ready:     'bg-green-500/10 text-green-400 ring-1 ring-inset ring-green-500/20',
    delivered: 'bg-slate-500/10 text-slate-400 ring-1 ring-inset ring-slate-500/30',
    cancelled: 'bg-red-500/10 text-red-400 ring-1 ring-inset ring-red-500/20',
};

const statusLabel: Record<string, string> = {
    pending:   'Pendiente',
    confirmed: 'Confirmado',
    preparing: 'Preparando',
    ready:     'Listo',
    delivered: 'Entregado',
    cancelled: 'Cancelado',
};

const loc = props.today_location;
const form = useForm({
    name:       loc?.name        ?? '',
    latitude:   Number(loc?.latitude)  || 40.4153,
    longitude:  Number(loc?.longitude) || -3.7074,
    start_time: loc?.start_time?.slice(0, 5) ?? '12:00',
    end_time:   loc?.end_time?.slice(0, 5)   ?? '22:00',
});

const mapLat = ref(form.latitude);
const mapLng = ref(form.longitude);

watch(() => [form.latitude, form.longitude] as [number, number], ([lat, lng]) => {
    mapLat.value = lat;
    mapLng.value = lng;
});

function saveLocation() {
    form.post('/dashboard/location');
}
</script>

<template>
    <Head title="Dashboard · Admin" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-400">Resumen del negocio en tiempo real</p>
    </div>

    <!-- Stat cards -->
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Pedidos totales</p>
                <div class="rounded-lg bg-amber-500/10 p-1.5"><ShoppingCart class="h-4 w-4 text-amber-400" /></div>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ stats.total_orders }}</p>
        </div>
        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Pendientes</p>
                <div class="rounded-lg bg-yellow-500/10 p-1.5"><Clock class="h-4 w-4 text-yellow-400" /></div>
            </div>
            <p class="text-3xl font-extrabold text-yellow-400">{{ stats.pending_orders }}</p>
        </div>
        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Productos</p>
                <div class="rounded-lg bg-blue-500/10 p-1.5"><Package class="h-4 w-4 text-blue-400" /></div>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ stats.total_products }}</p>
        </div>
        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Clientes</p>
                <div class="rounded-lg bg-purple-500/10 p-1.5"><Users class="h-4 w-4 text-purple-400" /></div>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ stats.total_clients }}</p>
        </div>
        <div class="rounded-2xl bg-slate-800 p-5 border border-slate-700/50">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Ingresos</p>
                <div class="rounded-lg bg-green-500/10 p-1.5"><TrendingUp class="h-4 w-4 text-green-400" /></div>
            </div>
            <p class="text-3xl font-extrabold text-green-400">{{ parseFloat(String(stats.revenue)).toFixed(2) }} €</p>
        </div>
    </div>

    <!-- Ubicacion del dia -->
    <div class="mb-8 rounded-2xl border border-slate-700/50 bg-slate-800 overflow-hidden">
        <div class="flex items-center gap-3 border-b border-slate-700 px-6 py-4">
            <div class="rounded-lg bg-amber-500/10 p-1.5">
                <MapPin class="h-4 w-4 text-amber-400" />
            </div>
            <div>
                <h2 class="font-semibold text-white">Ubicacion del truck hoy</h2>
                <p class="text-xs text-slate-400">Haz clic en el mapa o arrastra el marcador para fijar la posicion</p>
            </div>
            <span v-if="today_location" class="ml-auto flex items-center gap-1 rounded-full bg-green-500/10 px-3 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">
                <Check class="h-3 w-3" /> Publicada
            </span>
            <span v-else class="ml-auto rounded-full bg-yellow-500/10 px-3 py-1 text-xs font-medium text-yellow-400 ring-1 ring-inset ring-yellow-500/20">
                Sin publicar
            </span>
        </div>

        <div class="grid lg:grid-cols-2">
            <!-- Mapa -->
            <div style="height: 400px; min-height: 400px; position: relative;">
                <MapLocation
                    :lat="mapLat"
                    :lng="mapLng"
                    :draggable="true"
                    :zoom="14"
                    @update:lat="(v) => { form.latitude = v; mapLat = v }"
                    @update:lng="(v) => { form.longitude = v; mapLng = v }"
                />
            </div>

            <!-- Form -->
            <div class="p-6 flex flex-col gap-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-400">Nombre del lugar</label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Ej: Plaza Mayor, Madrid"
                        class="w-full rounded-xl border border-slate-600 bg-slate-700/50 px-4 py-2.5 text-white placeholder-slate-500 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-400">Latitud</label>
                        <input v-model="form.latitude" type="number" step="0.0000001"
                            class="w-full rounded-xl border border-slate-600 bg-slate-700/50 px-4 py-2.5 text-white font-mono text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-400">Longitud</label>
                        <input v-model="form.longitude" type="number" step="0.0000001"
                            class="w-full rounded-xl border border-slate-600 bg-slate-700/50 px-4 py-2.5 text-white font-mono text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-400">Hora apertura</label>
                        <input v-model="form.start_time" type="time"
                            class="w-full rounded-xl border border-slate-600 bg-slate-700/50 px-4 py-2.5 text-white focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-400">Hora cierre</label>
                        <input v-model="form.end_time" type="time"
                            class="w-full rounded-xl border border-slate-600 bg-slate-700/50 px-4 py-2.5 text-white focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    </div>
                </div>
                <button :disabled="form.processing" @click="saveLocation"
                    class="mt-auto flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 font-semibold text-white transition hover:bg-amber-600 disabled:opacity-50">
                    <MapPin class="h-4 w-4" />
                    {{ today_location ? 'Actualizar ubicacion' : 'Publicar ubicacion de hoy' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Recent orders -->
    <div class="rounded-2xl bg-slate-800 border border-slate-700/50 overflow-hidden">
        <div class="border-b border-slate-700 px-6 py-4">
            <h2 class="font-semibold text-white">Ultimos pedidos</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <tr v-for="order in recent_orders" :key="order.id" class="transition hover:bg-slate-700/30">
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ order.id }}</td>
                        <td class="px-6 py-4 font-medium text-white">{{ order.user }}</td>
                        <td class="px-6 py-4 font-semibold text-amber-400">{{ parseFloat(String(order.total_price)).toFixed(2) }} EUR</td>
                        <td class="px-6 py-4">
                            <span :class="['rounded-full px-2.5 py-1 text-xs font-semibold', statusColor[order.status] ?? 'bg-slate-500/10 text-slate-400']">
                                {{ statusLabel[order.status] ?? order.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ order.created_at }}</td>
                    </tr>
                    <tr v-if="recent_orders.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">No hay pedidos recientes</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
