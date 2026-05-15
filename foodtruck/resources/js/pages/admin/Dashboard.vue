<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Filler,
} from 'chart.js';
import {
    Package,
    ShoppingCart,
    TrendingUp,
    Users,
    MapPin,
    Check,
} from 'lucide-vue-next';
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';

const isDark = ref(document.documentElement.classList.contains('dark'));
const darkObserver = new MutationObserver(() => {
    isDark.value = document.documentElement.classList.contains('dark');
});
import { Bar, Line } from 'vue-chartjs';
import MapLocation from '@/components/MapLocation.vue';
import { useI18n, td } from '@/composables/useI18n';
import type { MessageKey } from '@/composables/useI18n';
import AdminLayout from '@/layouts/AdminLayout.vue';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Filler,
);

defineOptions({ layout: AdminLayout });

const { t } = useI18n();

const props = defineProps<{
    stats: {
        total_orders: number;
        confirmed_orders: number;
        total_products: number;
        total_clients: number;
        revenue: number;
    };
    revenue_chart?: {
        labels: string[];
        data: number[];
    };
    top_products?: Array<{ name: Record<string, string>; total_sold: number }>;
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

// ── Chart configs ────────────────────────────────────────────────────────────

const revenueData = computed(() => ({
    labels: props.revenue_chart?.labels ?? [],
    datasets: [
        {
            label: t('admin.dash.revenueDataset'),
            data: props.revenue_chart?.data ?? [],
            fill: true,
            backgroundColor: 'rgba(245,158,11,0.15)',
            borderColor: 'rgba(245,158,11,1)',
            borderWidth: 2,
            pointRadius: 3,
            pointBackgroundColor: 'rgba(245,158,11,1)',
            tension: 0.4,
        },
    ],
}));

const revenueOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            callbacks: { label: (ctx: any) => ` ${ctx.parsed.y.toFixed(2)} €` },
        },
    },
    scales: {
        x: {
            grid: { color: 'rgba(255,255,255,0.05)' },
            ticks: { color: '#94a3b8', font: { size: 11 } },
        },
        y: {
            grid: { color: 'rgba(255,255,255,0.05)' },
            ticks: {
                color: '#94a3b8',
                font: { size: 11 },
                callback: (v: any) => v + ' €',
            },
            beginAtZero: true,
        },
    },
};

const topProductsData = computed(() => ({
    labels: props.top_products?.map((p) => td(p.name)) ?? [],
    datasets: [
        {
            label: t('admin.dash.topDataset'),
            data: props.top_products?.map((p) => p.total_sold) ?? [],
            backgroundColor: [
                'rgba(245,158,11,0.8)',
                'rgba(251,191,36,0.8)',
                'rgba(252,211,77,0.8)',
                'rgba(253,230,138,0.8)',
                'rgba(234,88,12,0.8)',
                'rgba(249,115,22,0.8)',
                'rgba(251,146,60,0.8)',
                'rgba(253,186,116,0.8)',
            ],
            borderRadius: 6,
            borderWidth: 0,
        },
    ],
}));

const topProductsOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: 'y' as const,
    plugins: { legend: { display: false } },
    scales: {
        x: {
            grid: { color: 'rgba(255,255,255,0.05)' },
            ticks: { color: '#94a3b8', font: { size: 11 } },
            beginAtZero: true,
        },
        y: {
            grid: { display: false },
            ticks: {
                color: isDark.value ? '#c7d5e0' : '#000000',
                font: { size: 12 },
            },
        },
    },
}));

// ── Status helpers ───────────────────────────────────────────────────────────

const statusColor: Record<string, string> = {
    confirmed:
        'bg-blue-100 text-blue-700 ring-1 ring-inset ring-blue-300 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20',
    preparing:
        'bg-orange-100 text-orange-700 ring-1 ring-inset ring-orange-300 dark:bg-orange-500/10 dark:text-orange-400 dark:ring-orange-500/20',
    ready: 'bg-green-100 text-green-700 ring-1 ring-inset ring-green-300 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
    delivered:
        'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-300 dark:bg-slate-500/10 dark:text-slate-400 dark:ring-slate-500/30',
    cancelled: 'bg-red-100 text-red-700 ring-1 ring-inset ring-red-300 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20',
};

function statusLabel(status: string): string {
    return t(`status.${status}` as MessageKey);
}

// ── Auto-refresh ─────────────────────────────────────────────────────────────
const lastRefresh = ref(new Date());
let refreshTimer: ReturnType<typeof setInterval> | null = null;

function refreshData() {
    router.reload({
        only: ['stats', 'revenue_chart', 'top_products', 'recent_orders'],
    });
    lastRefresh.value = new Date();
}

onMounted(() => {
    refreshTimer = setInterval(refreshData, 30_000);
    darkObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
onUnmounted(() => {
    if (refreshTimer) {
        clearInterval(refreshTimer);
    }

    darkObserver.disconnect();
});

// ── Location form ─────────────────────────────────────────────────────────────

const DEFAULT_LAT = 41.5336796;
const DEFAULT_LNG = 2.4377341;

// Valid truck hours: 19, 20, 21, 22, 23, 0-7
const truckHourOptions = [19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7];
const truckMinuteOptions = [0, 15, 30, 45];

const loc = props.today_location;

const parseHour = (t: string) => parseInt(t?.split(':')[0] ?? '19', 10);
const parseMinute = (t: string) => parseInt(t?.split(':')[1] ?? '0', 10);
const pad = (n: number) => String(n).padStart(2, '0');

const startHour = ref<number>(parseHour(loc?.start_time ?? '19:00'));
const startMinute = ref<number>(parseMinute(loc?.start_time ?? '19:00'));
const endHour = ref<number>(parseHour(loc?.end_time ?? '07:00'));
const endMinute = ref<number>(parseMinute(loc?.end_time ?? '07:00'));

const form = useForm({
    name: loc?.name ?? '',
    latitude: Number(loc?.latitude) || DEFAULT_LAT,
    longitude: Number(loc?.longitude) || DEFAULT_LNG,
    start_time: loc?.start_time?.slice(0, 5) ?? '19:00',
    end_time: loc?.end_time?.slice(0, 5) ?? '07:00',
});

watch([startHour, startMinute], ([h, m]) => {
    form.start_time = `${pad(h)}:${pad(m)}`;
});

watch([endHour, endMinute], ([h, m]) => {
    form.end_time = `${pad(h)}:${pad(m)}`;
});

const mapLat = ref<number>(form.latitude);
const mapLng = ref<number>(form.longitude);

watch(
    () => [form.latitude, form.longitude] as [number, number],
    ([lat, lng]) => {
        mapLat.value = lat;
        mapLng.value = lng;
    },
);

function saveLocation() {
    form.post('/dashboard/location');
}
</script>

<template>
    <Head title="Dashboard · Admin" />

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ t('admin.dash.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ t('admin.dash.subtitle') }}
            </p>
        </div>
        <button
            class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs text-slate-500 transition hover:border-amber-400 hover:text-amber-600 dark:border-[#66c0f4] dark:bg-slate-800 dark:text-slate-400 dark:hover:text-amber-400"
            @click="refreshData"
        >
            <TrendingUp class="h-3.5 w-3.5" />
            {{ t('admin.dash.refresh') }} ·
            {{
                lastRefresh.toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                })
            }}
        </button>
    </div>

    <!-- Stat cards -->
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
        >
            <div class="mb-3 flex items-center justify-between">
                <p
                    class="text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                >
                    {{ t('admin.dash.totalOrders') }}
                </p>
                <div class="rounded-lg bg-amber-500/10 p-1.5">
                    <ShoppingCart
                        class="h-4 w-4 text-amber-500 dark:text-amber-400"
                    />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ stats.total_orders }}
            </p>
        </div>
        <div
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
        >
            <div class="mb-3 flex items-center justify-between">
                <p
                    class="text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                >
                    {{ t('admin.dash.confirmed') }}
                </p>
                <div class="rounded-lg bg-blue-500/10 p-1.5">
                    <Check class="h-4 w-4 text-blue-500 dark:text-blue-400" />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">
                {{ stats.confirmed_orders }}
            </p>
        </div>
        <div
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
        >
            <div class="mb-3 flex items-center justify-between">
                <p
                    class="text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                >
                    {{ t('admin.dash.products') }}
                </p>
                <div class="rounded-lg bg-blue-500/10 p-1.5">
                    <Package class="h-4 w-4 text-blue-500 dark:text-blue-400" />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ stats.total_products }}
            </p>
        </div>
        <div
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
        >
            <div class="mb-3 flex items-center justify-between">
                <p
                    class="text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                >
                    {{ t('admin.dash.clients') }}
                </p>
                <div class="rounded-lg bg-purple-500/10 p-1.5">
                    <Users
                        class="h-4 w-4 text-purple-500 dark:text-purple-400"
                    />
                </div>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ stats.total_clients }}
            </p>
        </div>
        <div
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
        >
            <div class="mb-3 flex items-center justify-between">
                <p
                    class="text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                >
                    {{ t('admin.dash.revenue') }}
                </p>
                <div class="rounded-lg bg-green-500/10 p-1.5">
                    <TrendingUp
                        class="h-4 w-4 text-green-500 dark:text-green-400"
                    />
                </div>
            </div>
            <p
                class="text-3xl font-extrabold text-green-600 dark:text-green-400"
            >
                {{ parseFloat(String(stats.revenue)).toFixed(2) }} €
            </p>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="mb-8 grid gap-6 lg:grid-cols-5">
        <!-- Ingresos últimos 14 días -->
        <div
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-3 dark:border-[#66c0f4]/50 dark:bg-slate-800"
        >
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        {{ t('admin.dash.dailyRevenue') }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ t('admin.dash.last14') }}
                    </p>
                </div>
                <TrendingUp class="h-5 w-5 text-amber-500" />
            </div>
            <div style="height: 220px">
                <Line :data="revenueData" :options="revenueOptions" />
            </div>
        </div>

        <!-- Productos más vendidos -->
        <div
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2 dark:border-[#66c0f4]/50 dark:bg-slate-800"
        >
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        {{ t('admin.dash.bestSellers') }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ t('admin.dash.top8') }}
                    </p>
                </div>
                <Package class="h-5 w-5 text-amber-500" />
            </div>
            <div
                v-if="top_products && top_products.length > 0"
                style="height: 220px"
            >
                <Bar :data="topProductsData" :options="topProductsOptions" />
            </div>
            <div
                v-else
                class="flex h-[220px] items-center justify-center text-sm text-slate-400 dark:text-slate-500"
            >
                {{ t('admin.dash.noSalesData') }}
            </div>
        </div>
    </div>

    <!-- Ubicacion del dia -->
    <div
        class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
    >
        <div
            class="flex items-center gap-3 border-b border-slate-200 px-6 py-4 dark:border-[#66c0f4]"
        >
            <div class="rounded-lg bg-amber-500/10 p-1.5">
                <MapPin class="h-4 w-4 text-amber-500 dark:text-amber-400" />
            </div>
            <div>
                <h2 class="font-semibold text-slate-900 dark:text-white">
                    {{ t('admin.dash.truckLocation') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ t('admin.dash.locationHint') }}
                </p>
            </div>
            <span
                v-if="today_location"
                class="ml-auto flex items-center gap-1 rounded-full bg-green-500/10 px-3 py-1 text-xs font-medium text-green-600 ring-1 ring-green-500/20 ring-inset dark:text-green-400"
            >
                <Check class="h-3 w-3" /> {{ t('admin.dash.published') }}
            </span>
            <span
                v-else
                class="ml-auto rounded-full bg-yellow-500/10 px-3 py-1 text-xs font-medium text-yellow-600 ring-1 ring-yellow-500/20 ring-inset dark:text-yellow-400"
            >
                {{ t('admin.dash.unpublished') }}
            </span>
        </div>

        <div class="grid lg:grid-cols-2">
            <!-- Mapa -->
            <div
                style="
                    height: 400px;
                    min-height: 400px;
                    position: relative;
                    isolation: isolate;
                "
            >
                <MapLocation
                    :lat="mapLat"
                    :lng="mapLng"
                    :draggable="true"
                    :zoom="14"
                    @update:lat="
                        (v) => {
                            form.latitude = v;
                            mapLat = v;
                        }
                    "
                    @update:lng="
                        (v) => {
                            form.longitude = v;
                            mapLng = v;
                        }
                    "
                />
            </div>

            <!-- Form -->
            <div class="flex flex-col gap-4 p-6">
                <div>
                    <label
                        class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400"
                        >{{ t('admin.dash.locationName') }}</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        :placeholder="t('admin.dash.locationPlaceholder')"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700/50 dark:text-white dark:placeholder-slate-500"
                    />
                    <p
                        v-if="form.errors.name"
                        class="mt-1 text-xs text-red-500 dark:text-red-400"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400"
                            >{{ t('admin.dash.latitude') }}</label
                        >
                        <input
                            v-model="form.latitude"
                            type="number"
                            step="0.0000001"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 font-mono text-sm text-slate-900 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700/50 dark:text-white"
                        />
                    </div>
                    <div>
                        <label
                            class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400"
                            >{{ t('admin.dash.longitude') }}</label
                        >
                        <input
                            v-model="form.longitude"
                            type="number"
                            step="0.0000001"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 font-mono text-sm text-slate-900 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700/50 dark:text-white"
                        />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400"
                            >{{ t('admin.dash.openTime') }}</label
                        >
                        <div class="flex items-center gap-1">
                            <select
                                v-model.number="startHour"
                                class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-slate-900 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700/50 dark:text-white"
                            >
                                <option
                                    v-for="h in truckHourOptions"
                                    :key="h"
                                    :value="h"
                                >
                                    {{ pad(h) }}
                                </option>
                            </select>
                            <span
                                class="font-bold text-slate-500 dark:text-slate-400"
                                >:</span
                            >
                            <select
                                v-model.number="startMinute"
                                class="w-20 rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-slate-900 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700/50 dark:text-white"
                            >
                                <option
                                    v-for="m in truckMinuteOptions"
                                    :key="m"
                                    :value="m"
                                >
                                    {{ pad(m) }}
                                </option>
                            </select>
                        </div>
                        <p
                            v-if="form.errors.start_time"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.start_time }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400"
                            >{{ t('admin.dash.closeTime') }}</label
                        >
                        <div class="flex items-center gap-1">
                            <select
                                v-model.number="endHour"
                                class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-slate-900 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700/50 dark:text-white"
                            >
                                <option
                                    v-for="h in truckHourOptions"
                                    :key="h"
                                    :value="h"
                                >
                                    {{ pad(h) }}
                                </option>
                            </select>
                            <span
                                class="font-bold text-slate-500 dark:text-slate-400"
                                >:</span
                            >
                            <select
                                v-model.number="endMinute"
                                class="w-20 rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-slate-900 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700/50 dark:text-white"
                            >
                                <option
                                    v-for="m in truckMinuteOptions"
                                    :key="m"
                                    :value="m"
                                >
                                    {{ pad(m) }}
                                </option>
                            </select>
                        </div>
                        <p
                            v-if="form.errors.end_time"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.end_time }}
                        </p>
                    </div>
                </div>
                <button
                    :disabled="form.processing"
                    @click="saveLocation"
                    class="mt-auto flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 font-semibold text-white transition hover:bg-amber-600 disabled:opacity-50"
                >
                    <MapPin class="h-4 w-4" />
                    {{
                        today_location
                            ? t('admin.dash.updateLocation')
                            : t('admin.dash.publishLocation')
                    }}
                </button>
            </div>
        </div>
    </div>

    <!-- Recent orders -->
    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
    >
        <div class="border-b border-slate-200 px-6 py-4 dark:border-[#66c0f4]">
            <h2 class="font-semibold text-slate-900 dark:text-white">
                {{ t('admin.dash.recentOrders') }}
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-[#66c0f4]">
                        <th
                            class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                        >
                            #
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                        >
                            {{ t('admin.dash.colClient') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                        >
                            {{ t('admin.dash.colTotal') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                        >
                            {{ t('admin.dash.colStatus') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                        >
                            {{ t('admin.dash.colDate') }}
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-slate-100 dark:divide-slate-700/50"
                >
                    <tr
                        v-for="order in recent_orders"
                        :key="order.id"
                        class="transition hover:bg-slate-50 dark:hover:bg-slate-700/30"
                    >
                        <td
                            class="px-6 py-4 font-mono text-xs text-slate-500 dark:text-slate-400"
                        >
                            #{{ order.id }}
                        </td>
                        <td
                            class="px-6 py-4 font-medium text-slate-900 dark:text-white"
                        >
                            {{ order.user }}
                        </td>
                        <td
                            class="px-6 py-4 font-semibold text-amber-600 dark:text-amber-400"
                        >
                            {{
                                parseFloat(String(order.total_price)).toFixed(2)
                            }}
                            EUR
                        </td>
                        <td class="px-6 py-4">
                            <span
                                :class="[
                                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                                    statusColor[order.status] ??
                                        'bg-slate-500/10 text-slate-500 dark:text-slate-400',
                                ]"
                            >
                                {{ statusLabel(order.status) ?? order.status }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 text-slate-500 dark:text-slate-400"
                        >
                            {{ order.created_at }}
                        </td>
                    </tr>
                    <tr v-if="recent_orders.length === 0">
                        <td
                            colspan="5"
                            class="px-6 py-10 text-center text-slate-400 dark:text-slate-500"
                        >
                            {{ t('admin.dash.noRecentOrders') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
