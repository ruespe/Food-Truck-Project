<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ShieldCheck, User, UserX, Trash2, RefreshCw } from 'lucide-vue-next';

defineOptions({ layout: AdminLayout });

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
    if (confirm(`¿Eliminar al usuario "${name}"? Esta acción no se puede deshacer.`)) {
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
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Usuarios</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gestiona los usuarios y sus permisos</p>
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
                {{ totalClients }} clientes
            </span>
            <span v-if="totalInactive > 0" class="rounded-full border border-red-500/30 bg-red-500/10 px-3 py-1 text-xs font-semibold text-red-500">
                {{ totalInactive }} inactivos
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-5 flex flex-wrap gap-3">
        <input
            v-model="search"
            type="search"
            placeholder="Buscar por nombre o email…"
            class="rounded-xl border border-slate-200 dark:border-[#66c0f4] bg-white dark:bg-slate-800 px-4 py-2 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 min-w-[220px]"
        />
        <select
            v-model="filterRole"
            class="rounded-xl border border-slate-200 dark:border-[#66c0f4] bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500"
        >
            <option value="all">Todos los roles</option>
            <option value="admin">Admin</option>
            <option value="client">Cliente</option>
        </select>
        <select
            v-model="filterActive"
            class="rounded-xl border border-slate-200 dark:border-[#66c0f4] bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500"
        >
            <option value="all">Todos los estados</option>
            <option value="active">Activos</option>
            <option value="inactive">Inactivos</option>
        </select>
    </div>

    <!-- Table -->
    <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-[#66c0f4]/50 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 dark:border-[#66c0f4] text-left">
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Usuario</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Rol</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Estado</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400 text-center">Pedidos</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Registro</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400 text-right">Acciones</th>
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
                                    <span v-if="user.id === currentUserId" class="ml-1 rounded-full bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400">Tú</span>
                                </p>
                                <p class="text-xs text-slate-400">{{ user.email }}</p>
                                <span v-if="!user.verified" class="inline-block mt-0.5 rounded-full bg-yellow-500/10 border border-yellow-500/30 px-1.5 py-0.5 text-[10px] font-semibold text-yellow-500">Sin verificar</span>
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
                            <option value="admin">Admin</option>
                            <option value="client">Cliente</option>
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
                            {{ user.active ? 'Activo' : 'Inactivo' }}
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
                                :title="user.active ? 'Desactivar cuenta' : 'Activar cuenta'"
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
                                title="Eliminar usuario"
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
                        No se encontraron usuarios con esos filtros
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
