<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
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

type Review = {
    id: number;
    user_name: string;
    rating: number;
    comment: string | null;
    created_at: string;
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
    reviews?: Review[];
    canReview?: boolean;
    userReview?: { rating: number; comment: string | null; visible: boolean } | null;
}>();

const { t } = useI18n();
const page = usePage();
const isAuth = computed(() => !!(page.props.auth as any)?.user);

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

// ── Reviews carousel ─────────────────────────────────────────────────────────
const reviewCurrent = ref(0);
const reviewTotal = computed(() => props.reviews?.length ?? 0);
let reviewTimer: ReturnType<typeof setInterval> | null = null;

function reviewPrev() { reviewCurrent.value = (reviewCurrent.value - 1 + reviewTotal.value) % reviewTotal.value; }
function reviewNext() { reviewCurrent.value = (reviewCurrent.value + 1) % reviewTotal.value; }
function reviewGoTo(i: number) { reviewCurrent.value = i; }
function startReviewAuto() {
    reviewTimer = setInterval(() => { if (reviewTotal.value > 1) reviewNext(); }, 5000);
}
function stopReviewAuto() { if (reviewTimer) { clearInterval(reviewTimer); reviewTimer = null; } }

onMounted(startReviewAuto);
onUnmounted(stopReviewAuto);

// ── Review form ───────────────────────────────────────────────────────────────
const reviewForm = useForm({
    rating: props.userReview?.rating ?? 0,
    comment: props.userReview?.comment ?? '',
});
const hoverRating = ref(0);

function submitReview() {
    reviewForm.post('/reviews', { preserveScroll: true });
}</script>

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
        class="bg-orange-100 dark:bg-gray-900"
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
    <section class="bg-orange-50/70 py-16 dark:bg-orange-900/10">
        <div class="mx-auto max-w-4xl px-4">
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
        </div>
    </section>

    <!-- Reseñas de clientes -->
    <section v-if="reviews && reviews.length > 0" class="border-t-2 border-b-2 border-amber-500 bg-amber-50/80 py-16 dark:border-amber-600 dark:bg-amber-900/10">
        <div class="mx-auto max-w-4xl px-4">
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ t('reviews.title') }}</h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ t('reviews.subtitle') }}</p>
            <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-orange-500"></div>
        </div>

        <!-- Carrusel de reseñas aprobadas -->
        <div
            v-if="reviews && reviews.length > 0"
            class="relative mb-12"
            @mouseenter="stopReviewAuto"
            @mouseleave="startReviewAuto"
        >
            <div class="overflow-hidden rounded-2xl">
                <transition name="slide" mode="out-in">
                    <div
                        :key="reviewCurrent"
                        class="flex flex-col items-center rounded-2xl bg-white p-8 shadow-lg dark:bg-gray-800 sm:p-10"
                    >
                        <!-- Estrellas -->
                        <div class="mb-4 flex gap-1">
                            <span
                                v-for="i in 5"
                                :key="i"
                                :class="i <= reviews[reviewCurrent].rating ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600'"
                                class="text-2xl"
                            >★</span>
                        </div>
                        <!-- Comentario -->
                        <p
                            v-if="reviews[reviewCurrent].comment"
                            class="mb-6 max-w-xl text-center text-lg leading-relaxed text-gray-600 italic dark:text-gray-300"
                        >
                            "{{ reviews[reviewCurrent].comment }}"
                        </p>
                        <!-- Autor -->
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-500/15 text-base font-bold text-amber-600 dark:text-amber-400">
                                {{ reviews[reviewCurrent].user_name.charAt(0).toUpperCase() }}
                            </span>
                            <div class="text-left">
                                <p class="font-semibold text-gray-800 dark:text-white">{{ reviews[reviewCurrent].user_name }}</p>
                                <p class="text-xs text-gray-400">{{ reviews[reviewCurrent].created_at }}</p>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>

            <!-- Flechas -->
            <button
                v-if="reviewTotal > 1"
                @click="reviewPrev"
                class="absolute top-1/2 left-0 flex h-10 w-10 -translate-x-4 -translate-y-1/2 items-center justify-center rounded-full bg-white text-gray-600 shadow-md transition hover:bg-amber-500 hover:text-white dark:bg-gray-700 dark:text-white dark:hover:bg-amber-500"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                v-if="reviewTotal > 1"
                @click="reviewNext"
                class="absolute top-1/2 right-0 flex h-10 w-10 translate-x-4 -translate-y-1/2 items-center justify-center rounded-full bg-white text-gray-600 shadow-md transition hover:bg-amber-500 hover:text-white dark:bg-gray-700 dark:text-white dark:hover:bg-amber-500"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Dots -->
            <div v-if="reviewTotal > 1" class="mt-6 flex justify-center gap-2">
                <button
                    v-for="(_, i) in reviews"
                    :key="i"
                    @click="reviewGoTo(i)"
                    :class="['h-2.5 rounded-full transition-all duration-300',
                        i === reviewCurrent ? 'w-8 bg-amber-500' : 'w-2.5 bg-gray-300 hover:bg-amber-300 dark:bg-gray-600']"
                />
            </div>
        </div>

        <!-- Formulario de reseña -->
        <div class="mx-auto max-w-lg">
            <!-- Usuario con pedido entregado -->
            <div v-if="canReview" class="rounded-2xl border border-orange-200 bg-orange-50 p-6 dark:border-orange-900/30 dark:bg-gray-800">
                <h3 class="mb-4 text-center text-lg font-bold text-gray-800 dark:text-white">
                    {{ userReview ? t('reviews.editTitle') : t('reviews.formTitle') }}
                </h3>

                <!-- Aviso reseña pendiente -->
                <div
                    v-if="userReview && !userReview.visible"
                    class="mb-4 rounded-lg border border-purple-500/30 bg-purple-500/10 px-4 py-2.5 text-center text-sm text-purple-400"
                >
                    {{ t('reviews.pending') }}
                </div>

                <form @submit.prevent="submitReview" class="space-y-4">
                    <!-- Estrellas interactivas -->
                    <div class="flex flex-col items-center gap-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ t('reviews.ratingLabel') }}</label>
                        <div class="flex gap-1">
                            <button
                                v-for="i in 5"
                                :key="i"
                                type="button"
                                @click="reviewForm.rating = i"
                                @mouseenter="hoverRating = i"
                                @mouseleave="hoverRating = 0"
                                :class="i <= (hoverRating || reviewForm.rating) ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600'"
                                class="text-3xl transition-colors"
                            >★</button>
                        </div>
                        <p v-if="reviewForm.errors.rating" class="text-xs text-red-400">{{ reviewForm.errors.rating }}</p>
                    </div>

                    <!-- Comentario -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">{{ t('reviews.commentLabel') }}</label>
                        <textarea
                            v-model="reviewForm.comment"
                            :placeholder="t('reviews.commentPlaceholder')"
                            rows="3"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="reviewForm.rating === 0 || reviewForm.processing"
                        class="w-full rounded-xl bg-amber-500 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-amber-400 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ userReview ? t('reviews.update') : t('reviews.submit') }}
                    </button>
                </form>
            </div>

            <!-- No autenticado -->
            <div
                v-else-if="!isAuth"
                class="rounded-2xl border border-gray-200 bg-gray-50 px-6 py-5 text-center text-sm text-gray-500 dark:border-slate-700 dark:bg-gray-800 dark:text-slate-400"
            >
                <a href="/login" class="font-medium text-amber-600 hover:underline dark:text-amber-400">{{ t('reviews.loginRequired') }}</a>
            </div>
        </div>
        </div>
    </section>

    <!-- Ubicación -->
    <section id="location" class="bg-orange-50/60 py-16 dark:bg-gray-900">
        <div class="mx-auto max-w-4xl px-4">
            <div class="mb-2 text-center">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                    {{ t('home.where') }}
                </h2>
                <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-orange-500"></div>
            </div>
            <p v-if="location" class="mb-6 text-center text-gray-500 dark:text-gray-400">
                {{ location.name }} · {{ location.start_time }} – {{ location.end_time }}
            </p>
            <p v-else class="mb-6 text-center text-gray-400">{{ t('home.noLocation') }}</p>
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
