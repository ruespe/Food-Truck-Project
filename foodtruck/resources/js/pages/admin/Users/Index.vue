<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ShieldCheck, User, UserX, Trash2, RefreshCw } from 'lucide-vue-next';
import { useI18n } from '@/composables/useI18n';

defineOptions({ layout: AdminLayout });

const { t } = useI18n();

const props = defineProps<{
    users: Array<{
        id: number;
        name: string;
        email: string;
        role: 'admin' | 'client';
        active: boolean;
        verified: boolean;
        orders_count: number;
        created_at: string;
    }>;
}>();

const page = usePage();
const flash = computed(() => (page.props as any).flash as { success?: string } | undefined);

const currentUserId = computed(() => (page.props.auth as any)?.user?.id as number);

// ── Role change ──────────────────────────────────────────────────────────────
const changingRole = ref<number | null>(null);

function updateRole(userId: number, role: string) {
    changingRole.value = userId;
    router.patch(`/admin/users/${userId}/role`, { role }, {
        preserveScroll: true,
        onFinish: () => { changingRole.value = null; },
    });
}

// ── Toggle active ─────────────────────────────────────────────────────────────
function toggleActive(userId: number) {
    router.patch(`/admin/users/${userId}/toggle-active`, {}, { preserveScroll: true });
}

// ── Delete ────────────────────────────────────────────────────────────────────
function destroy(userId: number, name: string) {
    if (confirm(`${t('admin.users.deleteConfirmPrefix')} "${name}"${t('admin.users.deleteConfirmSuffix')}`)) {
        router.delete(`/admin/users/${userId}`, { preserveScroll: true });
    }
}

// ── Filtering ─────────────────────────────────────────────────────────────────
const filterRole = ref<'all' | 'admin' | 'client'>('all');
const filterActive = ref<'all' | 'active' | 'inactive'>('all');
const search = ref('');

const filtered = computed(() => props.users.filter((u) => {
    if (filterRole.value !== 'all' && u.role !== filterRole.value) return false;
    if (filterActive.value === 'active' && !u.active) return false;
    if (filterActive.value === 'inactive' && u.active) return false;
    if (search.value && !u.name.toLowerCase().includes(search.value.toLowerCase()) &&
        !u.email.toLowerCase().includes(search.value.toLowerCase())) return false;
    return true;
}));

const totalAdmins  = computed(() => props.users.filter((u) => u.role === 'admin').length);
const totalClients = computed(() => props.users.filter((u) => u.role === 'client').length);
const totalInactive = computed(() => props.users.filter((u) => !u.active).length);
</script>

<template>
    <Head title="Usuarios · Admin" />

    <!-- Flash -->
    <div
        v-if="flash?.success"
        class="mb-6 flex items-center gap-3 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-400"
    >
        <ShieldCheck class="h-4 w-4 flex-shrink-0" />
        {{ flash.success }}
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ t('admin.users.title') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ t('admin.users.subtitle') }}</p>
        </div>

        <!-- Stats chips -->
        <div class="flex flex-wrap gap-2">
            <span class="rounded-full border border-slate-200 dark:border-[#66c0f4] bg-white dark:bg-slate-800 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                {{ users.length }} total
            </span>
            <span class="rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-600 dark:text-amber-400">
                {{ totalAdmins }} admin
            </span>
            <span class="rounded-full border border-blue-500/30 bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-600 dark:text-blue-400">
                {{ totalClients }} {{ t('admin.users.clients') }}
            </span>
            <span v-if="totalInactive > 0" class="rounded-full border border-red-500/30 bg-red-500/10 px-3 py-1 text-xs font-semibold text-red-500">
                {{ totalInactive }} {{ t('admin.users.inactive') }}
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-5 flex flex-wrap gap-3">
        <input
            v-model="search"
            type="search"
            :placeholder="t('admin.users.searchPlaceholder')"
            class="rounded-xl border border-slate-200 dark:border-[#66c0f4] bg-white dark:bg-slate-800 px-4 py-2 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 min-w-[220px]"
        />
        <select
            v-model="filterRole"
            class="rounded-xl border border-slate-200 dark:border-[#66c0f4] bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500"
        >
            <option value="all">{{ t('admin.users.allRoles') }}</option>
            <option value="admin">{{ t('admin.users.roleAdmin') }}</option>
            <option value="client">{{ t('admin.users.roleClient') }}</option>
        </select>
        <select
            v-model="filterActive"
            class="rounded-xl border border-slate-200 dark:border-[#66c0f4] bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500"
        >
            <option value="all">{{ t('admin.users.allStatuses') }}</option>
            <option value="active">{{ t('admin.users.filterActive') }}</option>
            <option value="inactive">{{ t('admin.users.filterInactive') }}</option>
        </select>
    </div>

    <!-- Mobile cards -->
    <div class="sm:hidden space-y-3">
        <div v-if="filtered.length === 0" class="rounded-2xl border border-slate-200 bg-white dark:border-[#66c0f4]/50 dark:bg-slate-800 px-6 py-10 text-center text-slate-400 dark:text-slate-500">
            {{ t('admin.users.empty') }}
        </div>
        <div
            v-for="user in filtered"
            :key="user.id"
            :class="['rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800 p-4 space-y-3', !user.active && 'opacity-60']"
        >
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-amber-500/15 text-sm font-bold text-amber-600 dark:text-amber-400">
                    {{ user.name.charAt(0).toUpperCase() }}
                </div>
                <div class="min-w-0">
                    <p class="font-medium text-slate-900 dark:text-white truncate">
                        {{ user.name }}
                        <span v-if="user.id === currentUserId" class="ml-1 rounded-full bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ t('admin.users.you') }}</span>
                    </p>
                    <p class="text-xs text-slate-400 truncate">{{ user.email }}</p>
                    <span v-if="!user.verified" class="inline-block mt-0.5 rounded-full bg-yellow-500/10 border border-yellow-500/30 px-1.5 py-0.5 text-[10px] font-semibold text-yellow-500">{{ t('admin.users.unverified') }}</span>
                </div>
            </div>
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <select
                        :value="user.role"
                        :disabled="user.id === currentUserId || changingRole === user.id"
                        class="rounded-lg border px-2 py-1 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:cursor-not-allowed disabled:opacity-50 transition"
                        :class="user.role === 'admin'
                            ? 'border-amber-500/40 bg-amber-500/10 text-amber-600 dark:text-amber-400 dark:bg-amber-500/10 dark:border-amber-500/30'
                            : 'border-blue-500/40 bg-blue-500/10 text-blue-600 dark:text-blue-400 dark:bg-blue-500/10 dark:border-blue-500/30'"
                        @change="updateRole(user.id, ($event.target as HTMLSelectElement).value)"
                    >
                        <option value="admin">{{ t('admin.users.roleAdmin') }}</option>
                        <option value="client">{{ t('admin.users.roleClient') }}</option>
                    </select>
                    <RefreshCw v-if="changingRole === user.id" class="h-3 w-3 animate-spin text-amber-500" />
                    <span
                        :class="user.active
                            ? 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/30'
                            : 'bg-red-500/10 text-red-500 border-red-500/30'"
                        class="rounded-full border px-2.5 py-0.5 text-xs font-semibold"
                    >
                        {{ user.active ? t('admin.users.activeStatus') : t('admin.users.inactiveStatus') }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ user.orders_count }} {{ t('admin.users.orders') }}</span>
                    <button
                        :disabled="user.id === currentUserId"
                        :title="user.active ? t('admin.users.deactivate') : t('admin.users.activate')"
                        :class="user.active ? 'border-orange-500/30 text-orange-500 hover:bg-orange-500/10' : 'border-green-500/30 text-green-500 hover:bg-green-500/10'"
                        class="rounded-lg border px-2.5 py-1.5 text-xs font-medium transition disabled:cursor-not-allowed disabled:opacity-40"
                        @click="toggleActive(user.id)"
                    >
                        <UserX v-if="user.active" class="h-3.5 w-3.5" />
                        <User v-else class="h-3.5 w-3.5" />
                    </button>
                    <button
                        :disabled="user.id === currentUserId"
                        :title="t('admin.users.deleteUser')"
                        class="rounded-lg border border-red-500/30 px-2.5 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-500/10 disabled:cursor-not-allowed disabled:opacity-40"
                        @click="destroy(user.id, user.name)"
                    >
                        <Trash2 class="h-3.5 w-3.5" />
                    </button>
                </div>
            </div>
            <p class="text-xs text-slate-400">{{ t('admin.users.colCreated') }}: {{ user.created_at }}</p>
        </div>
    </div>

    <!-- Desktop table -->
    <div class="hidden sm:block rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-[#66c0f4]/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 dark:border-[#66c0f4] text-left">
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ t('admin.users.colUser') }}</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ t('admin.users.colRole') }}</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ t('admin.users.colStatus') }}</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400 text-center">{{ t('admin.users.colOrders') }}</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ t('admin.users.colCreated') }}</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400 text-right">{{ t('admin.users.colActions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                <tr v-for="user in filtered" :key="user.id" :class="['transition hover:bg-slate-50 dark:hover:bg-slate-700/30', !user.active && 'opacity-60']">

                    <!-- Name + email -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-amber-500/15 text-sm font-bold text-amber-600 dark:text-amber-400">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">
                                    {{ user.name }}
                                    <span v-if="user.id === currentUserId" class="ml-1 rounded-full bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ t('admin.users.you') }}</span>
                                </p>
                                <p class="text-xs text-slate-400">{{ user.email }}</p>
                                <span v-if="!user.verified" class="inline-block mt-0.5 rounded-full bg-yellow-500/10 border border-yellow-500/30 px-1.5 py-0.5 text-[10px] font-semibold text-yellow-500">{{ t('admin.users.unverified') }}</span>
                            </div>
                        </div>
                    </td>

                    <!-- Role -->
                    <td class="px-6 py-4">
                        <select
                            :value="user.role"
                            :disabled="user.id === currentUserId || changingRole === user.id"
                            class="rounded-lg border px-2 py-1 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:cursor-not-allowed disabled:opacity-50 transition"
                            :class="user.role === 'admin'
                                ? 'border-amber-500/40 bg-amber-500/10 text-amber-600 dark:text-amber-400 dark:bg-amber-500/10 dark:border-amber-500/30'
                                : 'border-blue-500/40 bg-blue-500/10 text-blue-600 dark:text-blue-400 dark:bg-blue-500/10 dark:border-blue-500/30'"
                            @change="updateRole(user.id, ($event.target as HTMLSelectElement).value)"
                        >
                            <option value="admin">{{ t('admin.users.roleAdmin') }}</option>
                            <option value="client">{{ t('admin.users.roleClient') }}</option>
                        </select>
                        <RefreshCw v-if="changingRole === user.id" class="inline ml-1 h-3 w-3 animate-spin text-amber-500" />
                    </td>

                    <!-- Active status -->
                    <td class="px-6 py-4">
                        <span
                            :class="user.active
                                ? 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/30'
                                : 'bg-red-500/10 text-red-500 border-red-500/30'"
                            class="rounded-full border px-2.5 py-0.5 text-xs font-semibold"
                        >
                            {{ user.active ? t('admin.users.activeStatus') : t('admin.users.inactiveStatus') }}
                        </span>
                    </td>

                    <!-- Orders count -->
                    <td class="px-6 py-4 text-center">
                        <span class="font-semibold text-slate-900 dark:text-white">{{ user.orders_count }}</span>
                    </td>

                    <!-- Created at -->
                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">{{ user.created_at }}</td>

                    <!-- Actions -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Toggle active -->
                            <button
                                :disabled="user.id === currentUserId"
                                :title="user.active ? t('admin.users.deactivate') : t('admin.users.activate')"
                                :class="user.active
                                    ? 'border-orange-500/30 text-orange-500 hover:bg-orange-500/10'
                                    : 'border-green-500/30 text-green-500 hover:bg-green-500/10'"
                                class="rounded-lg border px-2.5 py-1.5 text-xs font-medium transition disabled:cursor-not-allowed disabled:opacity-40"
                                @click="toggleActive(user.id)"
                            >
                                <UserX v-if="user.active" class="h-3.5 w-3.5" />
                                <User v-else class="h-3.5 w-3.5" />
                            </button>

                            <!-- Delete -->
                            <button
                                :disabled="user.id === currentUserId"
                                :title="t('admin.users.deleteUser')"
                                class="rounded-lg border border-red-500/30 px-2.5 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-500/10 disabled:cursor-not-allowed disabled:opacity-40"
                                @click="destroy(user.id, user.name)"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </button>
                        </div>
                    </td>
                </tr>

                <tr v-if="filtered.length === 0">
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                        {{ t('admin.users.empty') }}
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</template>
