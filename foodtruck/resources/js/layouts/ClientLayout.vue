<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import { Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';

const { count } = useCart();
</script>

<template>
    <div class="flex min-h-screen flex-col bg-amber-50">
        <!-- Navbar -->
        <header class="sticky top-0 z-50 border-b border-amber-200 bg-white shadow-sm">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
                <Link href="/" class="flex items-center gap-2 text-xl font-bold text-amber-600">
                    🚚 FoodTruck
                </Link>
                <div class="flex items-center gap-6 text-sm font-medium text-gray-700">
                    <Link href="/" class="hover:text-amber-600">Inicio</Link>
                    <Link href="/menu" class="hover:text-amber-600">Menú</Link>
                    <Link href="/#location" class="hover:text-amber-600">Ubicación</Link>
                    <Link href="/#contact" class="hover:text-amber-600">Contacto</Link>
                </div>
                <div class="flex items-center gap-3">
                    <Link href="/cart" class="relative rounded-full p-2 hover:bg-amber-100">
                        <ShoppingCart class="h-5 w-5 text-amber-600" />
                        <span
                            v-if="count > 0"
                            class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-xs text-white"
                        >
                            {{ count }}
                        </span>
                    </Link>
                    <Link
                        href="/login"
                        class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600"
                    >
                        Entrar
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-amber-200 bg-white py-6 text-center text-sm text-gray-500">
            © {{ new Date().getFullYear() }} FoodTruck · Todos los derechos reservados
        </footer>
    </div>
</template>
