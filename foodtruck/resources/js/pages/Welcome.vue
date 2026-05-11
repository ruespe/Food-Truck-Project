<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CloudinaryImage from '@/components/CloudinaryImage.vue';
import ImagePlaceholder from '@/components/ImagePlaceholder.vue';
import MapLocation from '@/components/MapLocation.vue';
import { useI18n, td } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

type FeaturedProduct = {
    id: number;
    category_id: number;
    name: Record<string, string>;
    description: Record<string, string>;
    price: number;
    image: string | null;
    category?: { name: Record<string, string> };
};

const props = defineProps<{
    featuredProducts?: FeaturedProduct[];
    location?: {
        name: string;
        latitude: number;
        longitude: number;
        start_time: string;
        end_time: string;
    } | null;
}>();

const { t } = useI18n();

function isBebida(product: FeaturedProduct) {
    return (product.category?.name as any)?.es === 'Bebidas';
}
</script>

<template>
    <Head title="Sabor sobre Ruedas" />

    <!-- Hero -->
    <section
        class="relative overflow-hidden py-24 text-white"
        style="background-image: linear-gradient(rgba(38, 20, 0, 0.7), rgba(38, 20, 0, 0.7)), url('/background-hero.webp'); background-size: cover; background-position: 50% 40%;"
    >
        <div class="relative mx-auto max-w-4xl px-4 text-center">
            <p
                class="mb-2 text-lg font-medium tracking-widest text-amber-100 uppercase"
            >
                {{ t('home.welcome') }}
            </p>
            <h1 class="mb-4 text-5xl leading-tight font-extrabold">
                FoodTruck
            </h1>
            <p class="mx-auto mb-8 max-w-xl text-xl text-amber-100">
                {{ t('home.subtitle') }}
            </p>
            <a
                href="/menu"
                class="inline-block rounded-full bg-white px-8 py-3 font-bold text-amber-600 shadow-lg transition hover:bg-amber-50"
            >
                {{ t('home.cta') }}
            </a>
        </div>
    </section>

    <!-- Menú destacado -->
    <section class="mx-auto max-w-6xl px-4 py-16">
        <h2
            class="mb-8 text-center text-3xl font-bold text-gray-800 dark:text-white"
        >
            {{ t('home.popular') }}
        </h2>
        <div
            v-if="featuredProducts?.length"
            class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
        >
            <div
                v-for="product in featuredProducts"
                :key="product.id"
                class="overflow-hidden rounded-2xl bg-white shadow-md transition hover:scale-[1.02] hover:shadow-xl dark:bg-gray-800"
            >
                <div
                    :class="[
                        'flex h-40 items-center justify-center',
                        isBebida(product) ? 'bg-white dark:bg-white' : 'bg-amber-100 dark:bg-amber-900/30'
                    ]"
                >
                    <CloudinaryImage
                        v-if="product.image"
                        :src="product.image"
                        :img-class="`h-full w-full transition ${isBebida(product) ? 'object-contain p-4' : 'object-cover'}`"
                        :alt="td(product.name)"
                        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                    />
                    <span v-else class="flex items-center justify-center"
                        ><ImagePlaceholder
                            class="h-14 w-14 text-amber-300 dark:text-amber-700"
                    /></span>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 dark:text-white">
                        {{ td(product.name) }}
                    </h3>
                    <p
                        class="mt-1 line-clamp-2 text-sm text-gray-500 dark:text-gray-400"
                    >
                        {{ td(product.description) }}
                    </p>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-lg font-bold text-amber-600"
                            >{{
                                parseFloat(String(product.price)).toFixed(2)
                            }}
                            €</span
                        >
                        <a
                            href="/menu"
                            class="rounded-full bg-amber-500 px-3 py-1 text-sm text-white hover:bg-amber-600"
                            >{{ t('home.seeMenu') }}</a
                        >
                    </div>
                </div>
            </div>
        </div>
        <p v-else class="text-center text-gray-500 dark:text-gray-400">
            {{ t('home.noProducts') }}
        </p>
    </section>

    <!-- Ubicación -->
    <section id="location" class="bg-white py-16 dark:bg-gray-900">
        <div class="mx-auto max-w-4xl px-4">
            <h2
                class="mb-2 text-center text-3xl font-bold text-gray-800 dark:text-white"
            >
                {{ t('home.where') }}
            </h2>
            <p
                v-if="location"
                class="mb-6 text-center text-gray-500 dark:text-gray-400"
            >
                📍 {{ location.name }} · {{ location.start_time }} –
                {{ location.end_time }}
            </p>
            <p v-else class="mb-6 text-center text-gray-400">
                {{ t('home.noLocation') }}
            </p>
            <div class="isolate h-100 overflow-hidden rounded-2xl shadow-md">
                <MapLocation
                    v-if="location"
                    :lat="location.latitude"
                    :lng="location.longitude"
                />
                <div
                    v-else
                    class="flex h-full items-center justify-center bg-gray-100 text-gray-400 dark:bg-gray-800"
                >
                    {{ t('home.noLocationAlt') }}
                </div>
            </div>
        </div>
    </section>
</template>
