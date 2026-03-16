<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import {
    BarChart3,
    ChevronRight,
    FolderOpen,
    LayoutDashboard,
    LogOut,
    MapPin,
    Package,
    ShoppingBag,
} from 'lucide-vue-next';

const nav = [
    { label: 'Dashboard', href: '/admin', icon: LayoutDashboard },
    { label: 'Productos', href: '/admin/products', icon: Package },
    { label: 'Categorías', href: '/admin/categories', icon: FolderOpen },
    { label: 'Pedidos', href: '/admin/orders', icon: ShoppingBag },
    { label: 'Ubicación', href: '/admin/locations', icon: MapPin },
    { label: 'Estadísticas', href: '/admin', icon: BarChart3 },
];

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="flex w-64 flex-col bg-gray-900 text-white">
            <div class="border-b border-gray-700 px-6 py-5 text-xl font-bold text-amber-400">
                🚚 Admin Panel
            </div>
            <nav class="flex-1 space-y-1 px-3 py-4">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.label }}
                    <ChevronRight class="ml-auto h-3 w-3 opacity-40" />
                </Link>
            </nav>
            <div class="border-t border-gray-700 p-4">
                <button
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white"
                    @click="logout"
                >
                    <LogOut class="h-4 w-4" />
                    Cerrar sesión
                </button>
            </div>
        </aside>

        <!-- Content -->
        <div class="flex flex-1 flex-col">
            <header class="border-b bg-white px-8 py-4 shadow-sm">
                <slot name="header">
                    <h1 class="text-lg font-semibold text-gray-800">Admin</h1>
                </slot>
            </header>
            <main class="flex-1 overflow-auto p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
