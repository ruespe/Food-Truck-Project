<script setup lang="ts">
import MapLocation from '@/components/MapLocation.vue';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: ClientLayout });

defineProps<{
    featuredProducts?: Array<{ id: number; name: string; description: string; price: number; image: string | null }>;
    location?: { name: string; latitude: number; longitude: number; start_time: string; end_time: string } | null;
}>();

const contact = ref({ name: '', email: '', message: '' });
const contactSent = ref(false);

function submitContact() {
    router.post('/contact', contact.value, {
        onSuccess: () => {
            contactSent.value = true;
            contact.value = { name: '', email: '', message: '' };
        },
    });
}
</script>

<template>
    <Head title="FoodTruck - Comida en la calle" />

    <!-- Hero -->
    <section class="relative overflow-hidden bg-amber-500 py-24 text-white">
        <div class="mx-auto max-w-4xl px-4 text-center">
            <p class="mb-2 text-lg font-medium uppercase tracking-widest text-amber-100">Bienvenido a</p>
            <h1 class="mb-4 text-5xl font-extrabold leading-tight">FoodTruck</h1>
            <p class="mx-auto mb-8 max-w-xl text-xl text-amber-100">
                Comida fresca y artesanal en la calle. Bocadillos, hamburguesas, tapas y mucho más.
            </p>
            <a
                href="/menu"
                class="inline-block rounded-full bg-white px-8 py-3 font-bold text-amber-600 shadow-lg transition hover:bg-amber-50"
            >
                Ver el menú →
            </a>
        </div>
    </section>

    <!-- Menú destacado -->
    <section class="mx-auto max-w-6xl px-4 py-16">
        <h2 class="mb-8 text-center text-3xl font-bold text-gray-800">Lo más popular</h2>
        <div v-if="featuredProducts?.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="product in featuredProducts"
                :key="product.id"
                class="overflow-hidden rounded-2xl bg-white shadow-md transition hover:shadow-lg"
            >
                <div class="flex h-40 items-center justify-center bg-amber-100 text-5xl">
                    {{ product.image ? '' : '' }}
                    <img v-if="product.image" :src="product.image" class="h-full w-full object-cover" :alt="product.name" />
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800">{{ product.name }}</h3>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ product.description }}</p>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-lg font-bold text-amber-600">{{ parseFloat(String(product.price)).toFixed(2) }} €</span>
                        <a :href="`/menu`" class="rounded-full bg-amber-500 px-3 py-1 text-sm text-white hover:bg-amber-600">Ver menú</a>
                    </div>
                </div>
            </div>
        </div>
        <p v-else class="text-center text-gray-500">Cargando productos...</p>
    </section>

    <!-- Ubicación -->
    <section id="location" class="bg-white py-16">
        <div class="mx-auto max-w-4xl px-4">
            <h2 class="mb-2 text-center text-3xl font-bold text-gray-800">¿Dónde estamos hoy?</h2>
            <p v-if="location" class="mb-6 text-center text-gray-500">
                📍 {{ location.name }} · {{ location.start_time }} – {{ location.end_time }}
            </p>
            <p v-else class="mb-6 text-center text-gray-400">Ubicación no disponible hoy</p>
            <div class="h-72 overflow-hidden rounded-2xl shadow-md">
                <MapLocation
                    v-if="location"
                    :lat="location.latitude"
                    :lng="location.longitude"
                />
                <div v-else class="flex h-full items-center justify-center bg-gray-100 text-gray-400">
                    Sin ubicación activa
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto -->
    <section id="contact" class="bg-amber-50 py-16">
        <div class="mx-auto max-w-lg px-4">
            <h2 class="mb-6 text-center text-3xl font-bold text-gray-800">Contacto</h2>
            <div v-if="contactSent" class="rounded-xl bg-green-100 p-6 text-center text-green-700 font-medium">
                ✅ Mensaje enviado. ¡Gracias!
            </div>
            <form v-else class="space-y-4" @submit.prevent="submitContact">
                <input
                    v-model="contact.name"
                    type="text"
                    placeholder="Tu nombre"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-amber-400 focus:outline-none"
                />
                <input
                    v-model="contact.email"
                    type="email"
                    placeholder="Tu email"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-amber-400 focus:outline-none"
                />
                <textarea
                    v-model="contact.message"
                    placeholder="Tu mensaje"
                    required
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-amber-400 focus:outline-none"
                />
                <button
                    type="submit"
                    class="w-full rounded-lg bg-amber-500 py-3 font-bold text-white hover:bg-amber-600"
                >
                    Enviar mensaje
                </button>
            </form>
        </div>
    </section>
</template>

