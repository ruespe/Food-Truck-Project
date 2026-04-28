<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    FolderOpen,
    LayoutDashboard,
    LogOut,
    MapPin,
    Package,
    ShoppingBag,
    Truck,
} from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const currentUrl = computed(() => page.url);

const nav = [
    { label: 'Dashboard', href: '/admin', icon: LayoutDashboard },
    { label: 'Productos', href: '/admin/products', icon: Package },
    { label: 'Categorías', href: '/admin/categories', icon: FolderOpen },
    { label: 'Pedidos', href: '/admin/orders', icon: ShoppingBag },
    { label: 'Ubicación', href: '/admin/locations', icon: MapPin },
];

function isActive(href: string) {
    if (href === '/admin') return currentUrl.value === '/admin';
    return currentUrl.value.startsWith(href);
}

function logout() {
    router.post('/logout');
}

const user = computed(() => (page.props.auth as any)?.user as { name: string; email: string } | undefined);
</script>

<template>
    <div class="flex min-h-screen bg-slate-900">
        <!-- Sidebar -->
        <aside class="flex w-64 flex-col flex-shrink-0 bg-slate-950 border-r border-slate-800">
            <!-- Brand -->
            <div class="flex items-center gap-3 px-5 py-6 border-b border-slate-800">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 flex-shrink-0">
                    <Truck class="h-5 w-5 text-white" />
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white">Food Truck</p>
                    <p class="text-xs text-slate-500">Panel de administración</p>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 space-y-0.5 px-3 py-4">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all',
                        isActive(item.href)
                            ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                    ]"
                >
                    <component :is="item.icon" class="h-4 w-4 flex-shrink-0" />
                    {{ item.label }}
                </Link>
            </nav>

            <!-- User + Logout -->
            <div class="border-t border-slate-800 p-4 space-y-3">
                <div v-if="user" class="flex items-center gap-3 px-1">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-500/20 text-amber-400 text-sm font-bold flex-shrink-0">
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-white truncate">{{ user.name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                    </div>
                </div>
                <button
                    class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm text-slate-400 transition hover:bg-red-500/10 hover:text-red-400"
                    @click="logout"
                >
                    <LogOut class="h-4 w-4" />
                    Cerrar sesión
                </button>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex flex-1 flex-col min-w-0">
            <main class="flex-1 overflow-auto p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
