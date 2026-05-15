<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    FolderOpen,
    LayoutDashboard,
    LogOut,
    MapPin,
    Menu,
    MessageSquare,
    Moon,
    Package,
    ShoppingBag,
    Sun,
    ChevronDown,
    User,
    Users,
    X,
} from 'lucide-vue-next';
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import { useAppearance } from '@/composables/useAppearance';
import { useCart } from '@/composables/useCart';
import { useI18n, localeNames } from '@/composables/useI18n';
import type { Locale } from '@/composables/useI18n';
import LocaleFlag from '@/components/LocaleFlag.vue';

const page = usePage();
const currentUrl = computed(() => page.url);
const { resolvedAppearance, updateAppearance } = useAppearance();
const { t, locale, setLocale } = useI18n();

const locales: Locale[] = ['es', 'ca', 'en'];
const langOpen = ref(false);
const langRef = ref<HTMLElement | null>(null);
const mobileOpen = ref(false);

function selectLocale(l: Locale) {
    setLocale(l);
    langOpen.value = false;
}

const nav = computed(() => [
    { label: t('admin.nav.dashboard'), href: '/admin/', icon: LayoutDashboard },
    { label: t('admin.nav.products'), href: '/admin/products', icon: Package },
    {
        label: t('admin.nav.categories'),
        href: '/admin/categories',
        icon: FolderOpen,
    },
    { label: t('admin.nav.orders'), href: '/admin/orders', icon: ShoppingBag },
    { label: t('admin.nav.location'), href: '/admin/locations', icon: MapPin },
    {
        label: t('admin.nav.messages'),
        href: '/admin/contact',
        icon: MessageSquare,
    },
    { label: t('admin.nav.users'), href: '/admin/users', icon: Users },
]);

function isActive(href: string) {
    if (href === '/admin/') {
        return currentUrl.value === '/admin/' || currentUrl.value === '/admin';
    }

    return currentUrl.value.startsWith(href);
}

const { clear: clearCart } = useCart();

function logout() {
    clearCart();
    router.post('/logout');
}

const user = computed(
    () =>
        (page.props.auth as any)?.user as
            | { name: string; email: string }
            | undefined,
);
const unreadMessages = computed(
    () => ((page.props as any).unread_messages as number) ?? 0,
);

const userOpen = ref(false);
const userRef = ref<HTMLElement | null>(null);

function handleOutsideClick(e: MouseEvent) {
    if (userRef.value && !userRef.value.contains(e.target as Node)) {
        userOpen.value = false;
    }
    if (langRef.value && !langRef.value.contains(e.target as Node)) {
        langOpen.value = false;
    }
}

// Polling notifications
const pendingOrders = ref(0);
const newOrderToast = ref(false);
let pollingInterval: ReturnType<typeof setInterval> | null = null;

async function fetchNotifications() {
    try {
        const res = await fetch('/admin/api/notifications', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!res.ok) return;
        const data = await res.json();
        pendingOrders.value = data.pending_orders ?? 0;
        if ((data.new_orders ?? 0) > 0) {
            newOrderToast.value = true;
            setTimeout(() => { newOrderToast.value = false; }, 6000);
        }
    } catch {}
}

onMounted(() => {
    document.addEventListener('mousedown', handleOutsideClick);
    fetchNotifications();
    pollingInterval = setInterval(fetchNotifications, 20000);
});
onBeforeUnmount(() => {
    document.removeEventListener('mousedown', handleOutsideClick);
    if (pollingInterval) clearInterval(pollingInterval);
});
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-100 dark:bg-slate-950">
        <!-- Top Navbar -->
        <header
            class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 shadow-sm backdrop-blur-md transition-all duration-300 dark:border-[#66c0f4]/30 dark:bg-[#1b2838]/80"
        >
            <nav class="flex items-center justify-between px-5 py-3">
                <!-- Brand -->
                <Link href="/" class="flex items-center gap-2.5">
                    <img
                        src="/logoFoodtruck.png"
                        alt="FoodTruck"
                        class="h-[40px] w-auto"
                    />
                    <span
                        class="rounded-md bg-amber-500/10 px-2 py-0.5 text-xs font-semibold text-amber-400"
                        >Admin</span
                    >
                </Link>

                <!-- Nav links (desktop) -->
                <div class="hidden items-center gap-1 min-[1134px]:flex">
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
                        <component
                            :is="item.icon"
                            class="h-4 w-4 flex-shrink-0"
                        />
                        {{ item.label }}
                        <!-- Badge mensajes no leídos -->
                        <span
                            v-if="
                                item.href === '/admin/contact' &&
                                unreadMessages > 0
                            "
                            class="ml-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white"
                            >{{ unreadMessages }}</span
                        >
                        <!-- Badge pedidos pendientes -->
                        <span
                            v-if="item.href === '/admin/orders' && pendingOrders > 0"
                            class="ml-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-amber-500 px-1 text-[10px] font-bold text-white"
                            >{{ pendingOrders }}</span
                        >
                    </Link>
                </div>

                <!-- Right controls -->
                <div class="flex items-center gap-2">
                    <!-- Hamburger (mobile / mid) -->
                    <button
                        class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 min-[1134px]:hidden dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                        @click="mobileOpen = true"
                    >
                        <Menu class="h-5 w-5" />
                    </button>

                    <button
                        class="rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                        @click="
                            updateAppearance(
                                resolvedAppearance === 'dark'
                                    ? 'light'
                                    : 'dark',
                            )
                        "
                    >
                        <Sun
                            v-if="resolvedAppearance === 'dark'"
                            class="h-4 w-4"
                        />
                        <Moon v-else class="h-4 w-4" />
                    </button>

                    <!-- Language selector -->
                    <div ref="langRef" class="relative hidden min-[1134px]:block">
                        <button
                            class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-500 transition hover:border-amber-500 hover:text-amber-600 dark:border-slate-700 dark:text-slate-400 dark:hover:border-amber-500 dark:hover:text-amber-400"
                            @click="langOpen = !langOpen"
                        >
                            <LocaleFlag :locale="locale" flagClass="h-4 w-6 rounded-sm" />
                            <span>{{ localeNames[locale] }}</span>
                            <svg class="h-3 w-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            v-show="langOpen"
                            class="absolute right-0 mt-1.5 w-36 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800"
                        >
                            <button
                                v-for="l in locales"
                                :key="l"
                                class="flex w-full items-center gap-2 px-3 py-2 text-sm text-gray-700 transition hover:bg-amber-50 hover:text-amber-600 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-amber-400"
                                :class="l === locale ? 'font-semibold text-amber-600 dark:text-amber-400' : ''"
                                @click="selectLocale(l)"
                            >
                                <LocaleFlag :locale="l" flagClass="h-4 w-6 rounded-sm" />
                                <span>{{ t(`lang.${l}` as any) }}</span>
                            </button>
                        </div>
                    </div>

                    <div v-if="user" ref="userRef" class="relative">
                        <button
                            class="flex items-center gap-2 rounded-full border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="userOpen = !userOpen"
                        >
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white"
                            >
                                {{ user.name.charAt(0).toUpperCase() }}
                            </span>
                            <span class="hidden max-w-24 truncate sm:inline">{{
                                user.name
                            }}</span>
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
                                <User
                                    class="h-4 w-4 text-gray-400 dark:text-slate-400"
                                />
                                {{ t('admin.nav.myProfile') }}
                            </Link>
                            <div
                                class="border-t border-gray-100 dark:border-slate-700"
                            />
                            <button
                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-400 transition hover:bg-red-500/10"
                                @click="logout"
                            >
                                <LogOut class="h-4 w-4" />
                                {{ t('admin.nav.logout') }}
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main content -->
        <main class="flex-1 overflow-auto p-4 sm:p-8">
            <slot />
        </main>

        <!-- Mobile offcanvas backdrop -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mobileOpen"
                class="fixed inset-0 z-40 bg-black/50 sm:hidden"
                @click="mobileOpen = false"
            />
        </Transition>

        <!-- Mobile offcanvas drawer -->
        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <div
                v-if="mobileOpen"
                class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white shadow-2xl sm:hidden dark:bg-slate-900"
            >
                <!-- Drawer header -->
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <Link href="/" class="flex items-center gap-2.5" @click="mobileOpen = false">
                        <img src="/logoFoodtruck.png" alt="FoodTruck" class="h-9 w-auto" />
                        <span class="rounded-md bg-amber-500/10 px-2 py-0.5 text-xs font-semibold text-amber-400">Admin</span>
                    </Link>
                    <button
                        class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-slate-800 dark:hover:text-white"
                        @click="mobileOpen = false"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <!-- Drawer nav -->
                <nav class="flex-1 overflow-y-auto px-3 py-4">
                    <Link
                        v-for="item in nav"
                        :key="item.href"
                        :href="item.href"
                        :class="[
                            'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition mb-1',
                            isActive(item.href)
                                ? 'bg-amber-500 text-white'
                                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white',
                        ]"
                        @click="mobileOpen = false"
                    >
                        <component :is="item.icon" class="h-4 w-4 flex-shrink-0" />
                        {{ item.label }}
                        <span
                            v-if="item.href === '/admin/contact' && unreadMessages > 0"
                            class="ml-auto flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white"
                        >{{ unreadMessages }}</span>
                        <span
                            v-if="item.href === '/admin/orders' && pendingOrders > 0"
                            class="ml-auto flex h-4 min-w-4 items-center justify-center rounded-full bg-amber-500 px-1 text-[10px] font-bold text-white"
                        >{{ pendingOrders }}</span>
                    </Link>
                </nav>

                <!-- Drawer footer -->
                <div class="border-t border-gray-100 px-3 py-4 dark:border-slate-700">
                    <!-- Language selector -->
                    <div class="mb-3 flex gap-2">
                        <button
                            v-for="l in locales"
                            :key="l"
                            class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-gray-200 py-1.5 text-xs font-medium transition dark:border-slate-600"
                            :class="l === locale ? 'border-amber-500 bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' : 'text-gray-500 hover:border-amber-400 hover:text-amber-600 dark:text-slate-400'"
                            @click="selectLocale(l)"
                        >
                            <LocaleFlag :locale="l" flagClass="h-3.5 w-5 rounded-sm" />
                            <span>{{ t(`lang.${l}` as any) }}</span>
                        </button>
                    </div>
                    <button
                        class="flex w-full items-center gap-2.5 rounded-xl px-4 py-2.5 text-sm text-red-400 transition hover:bg-red-500/10"
                        @click="logout"
                    >
                        <LogOut class="h-4 w-4" />
                        {{ t('admin.nav.logout') }}
                    </button>
                </div>
            </div>
        </Transition>

        <!-- Toast nuevo pedido -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="translate-y-4 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-4 opacity-0"
        >
            <div
                v-if="newOrderToast"
                class="fixed bottom-6 right-6 z-50 flex items-start gap-3 rounded-xl border border-amber-200 bg-white px-5 py-4 shadow-2xl dark:border-amber-500/30 dark:bg-slate-900"
            >
                <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-500/20">
                    <!-- SVG campana -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">¡Nuevo pedido!</p>
                    <p class="text-xs text-gray-500 dark:text-slate-400">Ha llegado un nuevo pedido confirmado.</p>
                    <a
                        href="/admin/orders"
                        class="mt-1 inline-block text-xs font-medium text-amber-600 hover:underline dark:text-amber-400"
                        @click="newOrderToast = false"
                    >Ver pedidos →</a>
                </div>
                <button
                    class="ml-1 mt-0.5 text-gray-400 transition hover:text-gray-600 dark:hover:text-slate-200"
                    @click="newOrderToast = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </Transition>
    </div>
</template>
