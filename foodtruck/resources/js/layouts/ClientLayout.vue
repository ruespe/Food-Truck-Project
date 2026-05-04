<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    Moon,
    ShoppingCart,
    Sun,
    ChevronDown,
    User,
    LayoutDashboard,
    LogOut,
} from 'lucide-vue-next';
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import LocaleFlag from '@/components/LocaleFlag.vue';
import { useAppearance } from '@/composables/useAppearance';
import { useCart } from '@/composables/useCart';
import { useI18n, localeNames } from '@/composables/useI18n';
import type { Locale } from '@/composables/useI18n';

const { count, clear: clearCart } = useCart();
const { t, locale, setLocale } = useI18n();
const { resolvedAppearance, updateAppearance } = useAppearance();
const page = usePage();

const auth = computed(
    () =>
        (page.props as any).auth as {
            user: { name: string; role: string };
        } | null,
);
const isAdmin = computed(() => auth.value?.user?.role === 'admin');

const locales: Locale[] = ['es', 'ca', 'en'];
const langOpen = ref(false);
const langRef = ref<HTMLElement | null>(null);
const userOpen = ref(false);
const userRef = ref<HTMLElement | null>(null);
const mobileOpen = ref(false);

function toggleTheme() {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
}

function selectLocale(l: Locale) {
    setLocale(l);
    langOpen.value = false;
}

function handleOutsideClick(e: MouseEvent) {
    if (langRef.value && !langRef.value.contains(e.target as Node)) {
        langOpen.value = false;
    }

    if (userRef.value && !userRef.value.contains(e.target as Node)) {
        userOpen.value = false;
    }
}

function logout() {
    clearCart();
    router.post('/logout');
}

onMounted(() => document.addEventListener('mousedown', handleOutsideClick));
onBeforeUnmount(() =>
    document.removeEventListener('mousedown', handleOutsideClick),
);

// Contact modal
const contactModalOpen = ref(false);
const contact = ref({ name: '', email: '', message: '' });
const contactSent = ref(false);
function openContactModal() {
    contactModalOpen.value = true;
    contactSent.value = false;
}
function closeContactModal() {
    contactModalOpen.value = false;
    contact.value = { name: '', email: '', message: '' };
    contactSent.value = false;
}
function submitContact() {
    router.post('/contact', contact.value, {
        preserveScroll: true,
        onSuccess: () => {
            contactSent.value = true;
            contact.value = { name: '', email: '', message: '' };
        },
    });
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-amber-50 dark:bg-gray-950">
        <!-- Navbar -->
        <header
            class="sticky top-0 z-50 border-b border-amber-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
        >
            <nav
                class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3"
            >
                <!-- Brand -->
                <Link href="/" class="flex items-center">
                    <img src="/logoFoodtruck.png" alt="FoodTruck" class="h-[75px] w-auto" />
                </Link>

                <!-- Nav links -->
                <div
                    class="hidden items-center gap-6 text-sm font-medium text-gray-700 sm:flex dark:text-gray-200"
                >
                    <Link href="/" class="transition hover:text-amber-600">{{
                        t('nav.home')
                    }}</Link>
                    <Link
                        href="/menu"
                        class="transition hover:text-amber-600"
                        >{{ t('nav.menu') }}</Link
                    >
                    <Link
                        href="/#location"
                        class="transition hover:text-amber-600"
                        >{{ t('nav.location') }}</Link
                    >
                    <a
                        href="#contact"
                        class="transition hover:text-amber-600"
                        >{{ t('nav.contact') }}</a
                    >
                </div>

                <!-- Controls -->
                <div class="flex items-center gap-2">
                    <!-- Hamburguesa (móvil) -->
                    <button
                        class="rounded-full p-2 text-gray-600 transition hover:bg-amber-100 dark:text-gray-300 dark:hover:bg-gray-800 sm:hidden"
                        @click="mobileOpen = !mobileOpen"
                        :aria-label="mobileOpen ? 'Cerrar menú' : 'Abrir menú'"
                    >
                        <X v-if="mobileOpen" class="h-5 w-5" />
                        <Menu v-else class="h-5 w-5" />
                    </button>
                    <!-- Dark/Light toggle -->
                    <button
                        class="rounded-full p-2 text-gray-600 transition hover:bg-amber-100 dark:text-gray-300 dark:hover:bg-gray-800"
                        :title="
                            resolvedAppearance === 'dark'
                                ? t('theme.light')
                                : t('theme.dark')
                        "
                        @click="toggleTheme"
                    >
                        <Sun
                            v-if="resolvedAppearance === 'dark'"
                            class="h-5 w-5"
                        />
                        <Moon v-else class="h-5 w-5" />
                    </button>

                    <!-- Language selector -->
                    <div ref="langRef" class="relative">
                        <button
                            class="flex items-center gap-1.5 rounded-full border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-amber-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                            @click="langOpen = !langOpen"
                        >
                            <LocaleFlag
                                :locale="locale"
                                flagClass="h-4 w-6 rounded-sm"
                            />
                            <span>{{ localeNames[locale] }}</span>
                            <svg
                                class="h-3 w-3 opacity-50"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>
                        <div
                            v-show="langOpen"
                            class="absolute right-0 mt-1.5 w-36 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
                        >
                            <button
                                v-for="l in locales"
                                :key="l"
                                class="flex w-full items-center gap-2 px-3 py-2 text-sm text-gray-700 transition hover:bg-amber-50 dark:text-gray-200 dark:hover:bg-gray-800"
                                :class="
                                    l === locale
                                        ? 'font-semibold text-amber-600 dark:text-amber-400'
                                        : ''
                                "
                                @click="selectLocale(l)"
                            >
                                <LocaleFlag
                                    :locale="l"
                                    flagClass="h-4 w-6 rounded-sm"
                                />
                                <span>{{ t(`lang.${l}` as any) }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Cart -->
                    <Link
                        href="/cart"
                        class="relative rounded-full p-2 transition hover:bg-amber-100 dark:hover:bg-gray-800"
                    >
                        <ShoppingCart class="h-5 w-5 text-amber-600" />
                        <span
                            v-if="count > 0"
                            class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-xs text-white"
                            >{{ count }}</span
                        >
                    </Link>

                    <!-- User: sin sesión -->
                    <Link
                        v-if="!auth?.user"
                        href="/login"
                        class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600"
                    >
                        {{ t('nav.login') }}
                    </Link>

                    <!-- User: con sesión (dropdown) -->
                    <div v-else ref="userRef" class="relative">
                        <button
                            class="flex items-center gap-2 rounded-full border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-amber-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                            @click="userOpen = !userOpen"
                        >
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white"
                            >
                                {{ auth.user.name.charAt(0).toUpperCase() }}
                            </span>
                            <span
                                class="hidden max-w-[100px] truncate sm:inline"
                                >{{ auth.user.name }}</span
                            >
                            <ChevronDown class="h-3 w-3 opacity-50" />
                        </button>

                        <div
                            v-show="userOpen"
                            class="absolute right-0 mt-2 w-48 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
                        >
                            <!-- Admin panel -->
                            <Link
                                v-if="isAdmin"
                                href="/admin/"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-amber-50 dark:text-gray-200 dark:hover:bg-gray-800"
                                @click="userOpen = false"
                            >
                                <LayoutDashboard
                                    class="h-4 w-4 text-amber-500"
                                />
                                {{ t('nav.adminPanel') }}
                            </Link>

                            <!-- Mis pedidos (solo clientes) -->
                            <Link
                                v-if="!isAdmin"
                                href="/orders"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-amber-50 dark:text-gray-200 dark:hover:bg-gray-800"
                                @click="userOpen = false"
                            >
                                <ShoppingCart class="h-4 w-4 text-gray-400" />
                                {{ t('nav.myOrders') }}
                            </Link>

                            <!-- Perfil -->
                            <Link
                                href="/settings/profile"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-amber-50 dark:text-gray-200 dark:hover:bg-gray-800"
                                @click="userOpen = false"
                            >
                                <User class="h-4 w-4 text-gray-400" />
                                {{ t('nav.profile') }}
                            </Link>

                            <div
                                class="border-t border-gray-100 dark:border-gray-700"
                            />

                            <!-- Cerrar sesión -->
                            <button
                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10"
                                @click="logout"
                            >
                                <LogOut class="h-4 w-4" />
                                {{ t('nav.logout') }}
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Menú móvil -->
        <div
            v-show="mobileOpen"
            class="border-b border-amber-200 bg-white px-4 pb-4 pt-2 shadow-md dark:border-gray-800 dark:bg-gray-900 sm:hidden"
        >
            <div class="flex flex-col gap-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                <Link href="/" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.home') }}</Link>
                <Link href="/menu" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.menu') }}</Link>
                <Link href="/#location" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.location') }}</Link>
                <a href="#contact" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.contact') }}</a>
                <div class="my-1 border-t border-gray-100 dark:border-gray-700" />
                <template v-if="auth?.user">
                    <Link v-if="isAdmin" href="/admin/" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.adminPanel') }}</Link>
                    <Link v-else href="/orders" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.myOrders') }}</Link>
                    <Link href="/settings/profile" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.profile') }}</Link>
                    <button class="rounded-lg px-3 py-2 text-left text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10" @click="logout">{{ t('nav.logout') }}</button>
                </template>
                <Link v-else href="/login" class="rounded-lg px-3 py-2 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-gray-800" @click="mobileOpen = false">{{ t('nav.login') }}</Link>
            </div>
        </div>

        <!-- Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer
            class="border-t border-amber-200 bg-white dark:border-gray-800 dark:bg-gray-900"
        >
            <div class="mx-auto max-w-6xl px-4 py-12">
                <div class="grid gap-10 sm:grid-cols-3">
                    <!-- Col 1: Brand + redes sociales -->
                    <div>
                        <img src="/logoFoodtruck.png" alt="FoodTruck" class="mb-3 h-[40px] w-auto" />
                        <p
                            class="mb-5 text-sm text-gray-500 dark:text-gray-400"
                        >
                            {{ t('footer.tagline') }}
                        </p>
                        <p
                            class="mb-3 text-xs font-semibold tracking-wide text-gray-400 uppercase"
                        >
                            {{ t('footer.followTitle') }}
                        </p>
                        <div class="flex gap-3">
                            <!-- Instagram -->
                            <a
                                href="https://instagram.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-amber-100 hover:text-amber-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-amber-500/10 dark:hover:text-amber-400"
                                aria-label="Instagram"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                                    />
                                </svg>
                            </a>
                            <!-- Facebook -->
                            <a
                                href="https://facebook.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-amber-100 hover:text-amber-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-amber-500/10 dark:hover:text-amber-400"
                                aria-label="Facebook"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                    />
                                </svg>
                            </a>
                            <!-- TikTok -->
                            <a
                                href="https://tiktok.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-amber-100 hover:text-amber-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-amber-500/10 dark:hover:text-amber-400"
                                aria-label="TikTok"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Col 2: Botón de contacto -->
                    <div id="contact">
                        <p
                            class="mb-3 text-xs font-semibold tracking-wide text-gray-400 uppercase"
                        >
                            {{ t('footer.contactTitle') }}
                        </p>
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:outline-none"
                            @click="openContactModal"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            {{ t('footer.contactBtn') }}
                        </button>
                    </div>

                    <!-- Col 3: Avisos legales -->
                    <div>
                        <p
                            class="mb-3 text-xs font-semibold tracking-wide text-gray-400 uppercase"
                        >
                            {{ t('footer.legalTitle') }}
                        </p>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link
                                    href="/legal/privacidad"
                                    class="text-gray-500 transition hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400"
                                >
                                    {{ t('footer.privacy') }}
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/legal/terminos"
                                    class="text-gray-500 transition hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400"
                                >
                                    {{ t('footer.terms') }}
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/legal/cookies"
                                    class="text-gray-500 transition hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400"
                                >
                                    {{ t('footer.cookies') }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Bottom bar -->
            <div
                class="border-t border-amber-200 py-4 text-center text-xs text-gray-400 dark:border-gray-800"
            >
                © {{ new Date().getFullYear() }} FoodTruck ·
                {{ t('footer.rights') }}
            </div>
        </footer>

        <!-- Contact modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="contactModalOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    role="dialog"
                    aria-modal="true"
                    @keydown.esc="closeContactModal"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                        @click="closeContactModal"
                    />

                    <!-- Panel -->
                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="contactModalOpen"
                            class="relative z-10 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900"
                        >
                            <!-- Header -->
                            <div class="mb-5 flex items-start justify-between">
                                <div>
                                    <h2
                                        class="text-xl font-bold text-gray-800 dark:text-white"
                                    >
                                        {{ t('footer.contactTitle') }}
                                    </h2>
                                    <p
                                        class="mt-0.5 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ t('footer.tagline') }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    class="ml-4 rounded-full p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    @click="closeContactModal"
                                    aria-label="Cerrar"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- Success -->
                            <div
                                v-if="contactSent"
                                class="rounded-xl bg-green-100 p-5 text-center dark:bg-green-900/30"
                            >
                                <p
                                    class="text-base font-semibold text-green-700 dark:text-green-400"
                                >
                                    {{ t('home.sent') }}
                                </p>
                                <button
                                    type="button"
                                    class="mt-4 rounded-lg bg-amber-500 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-600"
                                    @click="closeContactModal"
                                >
                                    Cerrar
                                </button>
                            </div>

                            <!-- Form -->
                            <form
                                v-else
                                class="space-y-3"
                                @submit.prevent="submitContact"
                            >
                                <input
                                    v-model="contact.name"
                                    type="text"
                                    :placeholder="t('home.name')"
                                    required
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:border-amber-400 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                />
                                <input
                                    v-model="contact.email"
                                    type="email"
                                    :placeholder="t('home.email')"
                                    required
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:border-amber-400 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                />
                                <textarea
                                    v-model="contact.message"
                                    :placeholder="t('home.message')"
                                    required
                                    rows="4"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:border-amber-400 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                />
                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-amber-500 py-2.5 text-sm font-bold text-white hover:bg-amber-600"
                                >
                                    {{ t('home.send') }}
                                </button>
                            </form>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
