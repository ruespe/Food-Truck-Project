<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
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

// Carrusel
const current = ref(0);
const total = computed(() => props.featuredProducts?.length ?? 0);
let autoTimer: ReturnType<typeof setInterval> | null = null;

function prev() {
    current.value = (current.value - 1 + total.value) % total.value;
}
function next() {
    current.value = (current.value + 1) % total.value;
}
function goTo(i: number) {
    current.value = i;
}

function startAuto() {
    autoTimer = setInterval(() => {
        if (total.value > 1) {
            next();
        }
    }, 4000);
}
function stopAuto() {
    if (autoTimer) {
        clearInterval(autoTimer);
        autoTimer = null;
    }
}

onMounted(startAuto);
onUnmounted(stopAuto);
</script>

<template>
    <Head title="Sabor sobre Ruedas" />

    <!-- Hero -->
    <section
        class="relative overflow-hidden py-28 text-white"
        style="
            background-image:
                linear-gradient(rgba(38, 20, 0, 0.68), rgba(38, 20, 0, 0.68)),
                url('/foodtruck_hero.webp');
            background-size: cover;
            background-position: 50% 40%;
        "
    >
        <div class="relative mx-auto max-w-4xl px-4 text-center">
            <p
                class="mb-2 text-lg font-medium tracking-widest text-amber-200 uppercase"
            >
                {{ t('home.welcome') }}
            </p>
            <h1
                class="mb-4 text-5xl leading-tight font-extrabold drop-shadow-lg"
            >
                Sabor sobre Ruedas
            </h1>
            <p class="mx-auto mb-10 max-w-xl text-xl text-amber-100">
                {{ t('home.subtitle') }}
            </p>
            <a
                href="/menu"
                class="inline-block rounded-full bg-amber-500 px-10 py-3.5 font-bold text-white shadow-xl transition hover:scale-105 hover:bg-amber-400"
            >
                {{ t('home.cta') }}
            </a>
        </div>
    </section>

    <!-- Features strip -->
    <section
        class="border-t border-b border-orange-300 bg-orange-100 dark:border-orange-900/40 dark:bg-gray-900"
    >
        <div
            class="mx-auto grid max-w-5xl grid-cols-1 gap-6 px-4 py-10 text-center sm:grid-cols-3"
        >
            <!-- Horario -->
            <div class="flex flex-col items-center gap-3">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-full bg-orange-500 dark:bg-orange-700"
                >
                    <svg
                        class="h-7 w-7 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <h3 class="font-bold text-orange-900 dark:text-orange-200">
                    {{ t('home.feat1Title') }}
                </h3>
                <p class="text-sm text-orange-900/70 dark:text-orange-200/60">
                    {{ t('home.feat1Desc') }}
                </p>
            </div>
            <!-- Comida -->
            <div class="flex flex-col items-center gap-3">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-full bg-orange-500 dark:bg-orange-700"
                >
                    <svg
                        class="h-7 w-7 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <!-- Cubiertos / fork & knife -->
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 2v6a3 3 0 006 0V2M6 8v14M15 2v20M19 2c0 4-2 6-2 9h4c0-3-2-5-2-9z"
                        />
                    </svg>
                </div>
                <h3 class="font-bold text-orange-900 dark:text-orange-200">
                    {{ t('home.feat2Title') }}
                </h3>
                <p class="text-sm text-orange-900/70 dark:text-orange-200/60">
                    {{ t('home.feat2Desc') }}
                </p>
            </div>
            <!-- Ubicación -->
            <div class="flex flex-col items-center gap-3">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-full bg-orange-500 dark:bg-orange-700"
                >
                    <svg
                        class="h-7 w-7 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <!-- Map pin -->
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </div>
                <h3 class="font-bold text-orange-900 dark:text-orange-200">
                    {{ t('home.feat3Title') }}
                </h3>
                <p class="text-sm text-orange-900/70 dark:text-orange-200/60">
                    {{ t('home.feat3Desc') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Carrusel de productos destacados -->
    <section class="mx-auto max-w-4xl px-4 py-16">
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                {{ t('home.popular') }}
            </h2>
            <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-orange-500"></div>
        </div>

        <div
            v-if="featuredProducts?.length"
            class="relative"
            @mouseenter="stopAuto"
            @mouseleave="startAuto"
        >
            <!-- Tarjeta visible -->
            <div class="overflow-hidden rounded-2xl">
                <transition name="slide" mode="out-in">
                    <div
                        :key="current"
                        class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-lg sm:flex-row dark:bg-gray-800"
                    >
                        <!-- Imagen -->
                        <div
                            :class="[
                                'flex h-56 flex-shrink-0 items-center justify-center sm:h-auto sm:w-64',
                                isBebida(featuredProducts[current])
                                    ? 'bg-white dark:bg-white'
                                    : 'bg-amber-100 dark:bg-amber-900/30',
                            ]"
                        >
                            <CloudinaryImage
                                v-if="featuredProducts[current].image"
                                :src="featuredProducts[current].image!"
                                :img-class="`h-full w-full transition ${isBebida(featuredProducts[current]) ? 'object-contain p-6' : 'object-cover'}`"
                                :alt="td(featuredProducts[current].name)"
                                sizes="(max-width: 640px) 100vw, 256px"
                            />
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <ImagePlaceholder
                                    class="h-16 w-16 text-amber-300 dark:text-amber-700"
                                />
                            </span>
                        </div>
                        <!-- Info -->
                        <div class="flex flex-col justify-center gap-4 p-8">
                            <span
                                class="text-xs font-semibold tracking-widest text-amber-500 uppercase"
                            >
                                {{
                                    td(
                                        featuredProducts[current].category
                                            ?.name ?? {},
                                    )
                                }}
                            </span>
                            <h3
                                class="text-2xl font-bold text-gray-800 dark:text-white"
                            >
                                {{ td(featuredProducts[current].name) }}
                            </h3>
                            <p
                                class="leading-relaxed text-gray-500 dark:text-gray-400"
                            >
                                {{ td(featuredProducts[current].description) }}
                            </p>
                            <div class="mt-2 flex items-center gap-4">
                                <span
                                    class="text-2xl font-extrabold text-amber-600"
                                >
                                    {{
                                        parseFloat(
                                            String(
                                                featuredProducts[current].price,
                                            ),
                                        ).toFixed(2)
                                    }}
                                    €
                                </span>
                                <a
                                    href="/menu"
                                    class="rounded-full bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow transition hover:bg-amber-400"
                                >
                                    {{ t('home.seeMenu') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>

            <!-- Flechas -->
            <button
                v-if="total > 1"
                @click="prev"
                class="absolute top-1/2 left-0 flex h-10 w-10 -translate-x-4 -translate-y-1/2 items-center justify-center rounded-full bg-white text-gray-600 shadow-md transition hover:bg-amber-500 hover:text-white dark:bg-gray-700 dark:text-white dark:hover:bg-amber-500"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </button>
            <button
                v-if="total > 1"
                @click="next"
                class="absolute top-1/2 right-0 flex h-10 w-10 translate-x-4 -translate-y-1/2 items-center justify-center rounded-full bg-white text-gray-600 shadow-md transition hover:bg-amber-500 hover:text-white dark:bg-gray-700 dark:text-white dark:hover:bg-amber-500"
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
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>

            <!-- Dots -->
            <div v-if="total > 1" class="mt-6 flex justify-center gap-2">
                <button
                    v-for="(_, i) in featuredProducts"
                    :key="i"
                    @click="goTo(i)"
                    :class="[
                        'h-2.5 rounded-full transition-all duration-300',
                        i === current
                            ? 'w-8 bg-amber-500'
                            : 'w-2.5 bg-gray-300 hover:bg-amber-300 dark:bg-gray-600',
                    ]"
                />
            </div>
        </div>

        <p v-else class="text-center text-gray-500 dark:text-gray-400">
            {{ t('home.noProducts') }}
        </p>
    </section>

    <!-- Ubicación -->
    <section id="location" class="bg-orange-50/60 py-16 dark:bg-gray-900">
        <div class="mx-auto max-w-4xl px-4">
            <div class="mb-2 text-center">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                    {{ t('home.where') }}
                </h2>
                <div
                    class="mx-auto mt-3 h-1 w-16 rounded-full bg-orange-500"
                ></div>
            </div>
            <p
                v-if="location"
                class="mb-6 text-center text-gray-500 dark:text-gray-400"
            >
                {{ location.name }} · {{ location.start_time }} –
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

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition:
        opacity 0.35s ease,
        transform 0.35s ease;
}
.slide-enter-from {
    opacity: 0;
    transform: translateX(40px);
}
.slide-leave-to {
    opacity: 0;
    transform: translateX(-40px);
}
</style>
