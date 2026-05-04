<script setup lang="ts">
import { Link, router, usePage } from "@inertiajs/vue3";
import {
    FolderOpen,
    LayoutDashboard,
    LogOut,
    MapPin,
    MessageSquare,
    Moon,
    Package,
    ShoppingBag,
    Sun,
    ChevronDown,
    User,
    Users,
} from "lucide-vue-next";
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { useAppearance } from "@/composables/useAppearance";
import { useCart } from "@/composables/useCart";

const page = usePage();
const currentUrl = computed(() => page.url);
const { resolvedAppearance, updateAppearance } = useAppearance();

const nav = [
    { label: "Dashboard", href: "/admin/", icon: LayoutDashboard },
    { label: "Productos", href: "/admin/products", icon: Package },
    { label: "Categorias", href: "/admin/categories", icon: FolderOpen },
    { label: "Pedidos", href: "/admin/orders", icon: ShoppingBag },
    { label: "Ubicacion", href: "/admin/locations", icon: MapPin },
    { label: "Mensajes", href: "/admin/contact", icon: MessageSquare },
    { label: "Usuarios", href: "/admin/users", icon: Users },
];

function isActive(href: string) {
    if (href === "/admin/") return currentUrl.value === "/admin/" || currentUrl.value === "/admin";
    return currentUrl.value.startsWith(href);
}

const { clear: clearCart } = useCart();

function logout() {
    clearCart();
    router.post("/logout");
}

const user = computed(() => (page.props.auth as any)?.user as { name: string; email: string } | undefined);
const unreadMessages = computed(() => (page.props as any).unread_messages as number ?? 0);

const userOpen = ref(false);
const userRef = ref<HTMLElement | null>(null);

function handleOutsideClick(e: MouseEvent) {
    if (userRef.value && !userRef.value.contains(e.target as Node)) {
        userOpen.value = false;
    }
}
onMounted(() => document.addEventListener("mousedown", handleOutsideClick));
onBeforeUnmount(() => document.removeEventListener("mousedown", handleOutsideClick));
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-100 dark:bg-slate-900">

        <!-- Top Navbar -->
        <header class="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <nav class="flex items-center justify-between px-5 py-3">
                <!-- Brand -->
                <Link href="/" class="flex items-center gap-2.5">
                    <img src="/logoFoodtruck.png" alt="FoodTruck" class="h-[40px] w-auto" />
                    <span class="rounded-md bg-amber-500/10 px-2 py-0.5 text-xs font-semibold text-amber-400">Admin</span>
                </Link>

                <!-- Nav links -->
                <div class="hidden items-center gap-1 sm:flex">
                    <Link
                        v-for="item in nav"
                        :key="item.href"
                        :href="item.href"
                        :class="[
                            'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition',
                            isActive(item.href)
                                ? 'bg-amber-500 text-white'
                                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white',
                        ]"
                    >
                        <component :is="item.icon" class="h-4 w-4 flex-shrink-0" />
                        {{ item.label }}
                        <!-- Badge mensajes no leídos -->
                        <span
                            v-if="item.href === '/admin/contact' && unreadMessages > 0"
                            class="ml-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white"
                        >{{ unreadMessages }}</span>
                    </Link>
                </div>

                <!-- Right controls -->
                <div class="flex items-center gap-2">
                    <button
                        class="rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                        @click="updateAppearance(resolvedAppearance === 'dark' ? 'light' : 'dark')"
                    >
                        <Sun v-if="resolvedAppearance === 'dark'" class="h-4 w-4" />
                        <Moon v-else class="h-4 w-4" />
                    </button>

                    <Link
                        href="/"
                        class="hidden rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-500 transition hover:border-amber-500 hover:text-amber-600 dark:border-slate-700 dark:text-slate-400 dark:hover:border-amber-500 dark:hover:text-amber-400 sm:flex items-center gap-1.5"
                    >
                        Ver web
                    </Link>

                    <div v-if="user" ref="userRef" class="relative">
                        <button
                            class="flex items-center gap-2 rounded-full border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="userOpen = !userOpen"
                        >
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </span>
                            <span class="hidden sm:inline max-w-24 truncate">{{ user.name }}</span>
                            <ChevronDown class="h-3 w-3 opacity-50" />
                        </button>

                        <div
                            v-show="userOpen"
                            class="absolute right-0 mt-2 w-48 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-900"
                        >
                            <Link
                                href="/settings/profile"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-800"
                                @click="userOpen = false"
                            >
                                <User class="h-4 w-4 text-gray-400 dark:text-slate-400" />
                                Mi perfil
                            </Link>
                            <div class="border-t border-gray-100 dark:border-slate-700" />
                            <button
                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-400 transition hover:bg-red-500/10"
                                @click="logout"
                            >
                                <LogOut class="h-4 w-4" />
                                Cerrar sesion
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main content -->
        <main class="flex-1 overflow-auto p-8">
            <slot />
        </main>
    </div>
</template>
