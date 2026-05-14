<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import CloudinaryImage from '@/components/CloudinaryImage.vue';
import ImagePlaceholder from '@/components/ImagePlaceholder.vue';
import { useCart } from '@/composables/useCart';
import { useI18n, td } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

type Product = {
    id: number;
    name: Record<string, string>;
    description: Record<string, string>;
    price: number;
    image: string | null;
    available: boolean;
    stock: boolean;
    category_id: number;
};

type Paginator = {
    data: Product[];
    current_page: number;
    last_page: number;
    total: number;
    links: Array<{ url: string | null; label: string; active: boolean }>;
};

const props = defineProps<{
    categories: Array<{ id: number; name: Record<string, string> }>;
    products: Paginator;
    selectedCategory?: number | null;
}>();

const bebidaCategoryId = computed(() =>
    props.categories?.find(c => (c.name as any)?.es === 'Bebidas')?.id
);

function isBebida(product: Product) {
    return product.category_id === bebidaCategoryId.value;
}

const { add, count } = useCart();
const { t } = useI18n();
const page = usePage();
const auth = computed(
    () =>
        (page.props as any).auth as {
            user: { name: string; role: string };
        } | null,
);

function selectCategory(id: number | null) {
    router.get('/menu', id ? { category: id } : {}, { preserveScroll: true });
}

const added = ref<Set<number>>(new Set());
const showLoginWarning = ref(false);
let loginWarningTimer: ReturnType<typeof setTimeout> | null = null;

function addToCart(product: Product) {
    if (!auth.value?.user) {
        showLoginWarning.value = true;

        if (loginWarningTimer) {
            clearTimeout(loginWarningTimer);
        }

        loginWarningTimer = setTimeout(() => {
            showLoginWarning.value = false;
        }, 3000);

        return;
    }

    add({
        id: product.id,
        name: product.name,
        price: product.price,
        image: product.image,
    });
    added.value.add(product.id);
    setTimeout(() => added.value.delete(product.id), 1500);
}
</script>

<template>
    <Head title="Menú · FoodTruck" />

    <!-- Aviso: debes iniciar sesión -->
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div
            v-if="showLoginWarning"
            class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded-xl bg-red-500 px-6 py-3 text-sm font-semibold text-white shadow-xl"
        >
            ⚠️ {{ t('menu.loginRequired') }}
            <a href="/login" class="ml-2 underline hover:text-red-100">{{
                t('nav.login')
            }}</a>
        </div>
    </Transition>

    <div class="mx-auto max-w-6xl px-4 py-10">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                {{ t('menu.title') }}
            </h1>
            <div class="mt-2 h-1 w-12 rounded-full bg-orange-500"></div>
        </div>

        <!-- Filtro categorías -->
        <div class="mb-8 flex flex-wrap gap-2">
            <button
                :class="[
                    'rounded-full px-4 py-1.5 text-sm font-medium transition',
                    !selectedCategory
                        ? 'bg-amber-500 text-white'
                        : 'border bg-white text-gray-600 hover:border-amber-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200',
                ]"
                @click="selectCategory(null)"
            >
                {{ t('menu.all') }}
            </button>
            <button
                v-for="cat in categories"
                :key="cat.id"
                :class="[
                    'rounded-full px-4 py-1.5 text-sm font-medium transition',
                    selectedCategory === cat.id
                        ? 'bg-amber-500 text-white'
                        : 'border bg-white text-gray-600 hover:border-amber-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200',
                ]"
                @click="selectCategory(cat.id)"
            >
                {{ td(cat.name) }}
            </button>
        </div>

        <!-- Grid productos -->
        <div
            v-if="products.data.length > 0"
            class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
        >
            <div
                v-for="product in products.data"
                :key="product.id"
                class="overflow-hidden rounded-2xl bg-white shadow-md transition hover:scale-[1.02] hover:shadow-xl dark:bg-gray-800"
            >
                <div
                    :class="[
                        'relative flex h-44 items-center justify-center',
                        isBebida(product) ? 'bg-white dark:bg-white' : 'bg-amber-100 dark:bg-amber-900/30'
                    ]"
                >
                    <CloudinaryImage
                        v-if="product.image"
                        :src="product.image"
                        :alt="td(product.name)"
                        :img-class="`h-full w-full transition ${isBebida(product) ? 'object-contain p-4' : 'object-cover'}`"
                        :class="
                            !product.stock ? 'brightness-50 saturate-0' : ''
                        "
                        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                    />
                    <span
                        v-else
                        class="flex items-center justify-center"
                        :class="!product.stock ? 'opacity-30' : ''"
                        ><ImagePlaceholder
                            class="h-14 w-14 text-amber-300 dark:text-amber-700"
                    /></span>
                    <!-- Overlay + badge sin stock -->
                    <div
                        v-if="!product.stock"
                        class="absolute inset-0 flex flex-col items-center justify-center bg-red-600/40"
                    >
                        <span
                            class="rounded-full bg-red-600 px-4 py-1 text-sm font-bold tracking-wide text-white uppercase shadow"
                            >{{ t('menu.outOfStock') }}</span
                        >
                    </div>
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
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-bold text-amber-600"
                            >{{
                                parseFloat(String(product.price)).toFixed(2)
                            }}
                            €</span
                        >
                        <button
                            v-if="product.available && product.stock"
                            class="rounded-full px-4 py-1.5 text-sm font-semibold transition"
                            :class="
                                added.has(product.id)
                                    ? 'bg-green-500 text-white'
                                    : 'bg-amber-500 text-white hover:bg-amber-600'
                            "
                            @click="addToCart(product)"
                        >
                            {{
                                added.has(product.id)
                                    ? t('menu.added')
                                    : t('menu.add')
                            }}
                        </button>
                        <span
                            v-else-if="!product.stock"
                            class="text-sm font-semibold text-red-400"
                            >{{ t('menu.outOfStock') }}</span
                        >
                        <span
                            v-else
                            class="text-sm text-gray-400 dark:text-gray-500"
                            >{{ t('menu.notAvailable') }}</span
                        >
                    </div>
                </div>
            </div>
        </div>
        <div
            v-else
            class="mt-4 rounded-2xl bg-white p-10 text-center text-gray-500 shadow dark:bg-gray-800 dark:text-gray-400"
        >
            {{ t('menu.noProducts') }}
        </div>

        <!-- Paginación -->
        <div
            v-if="products.last_page > 1"
            class="mt-10 flex items-center justify-center gap-2"
        >
            <template v-for="link in products.links" :key="link.label">
                <a
                    v-if="link.url"
                    :href="link.url"
                    :class="[
                        'flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm font-medium transition',
                        link.active
                            ? 'bg-amber-500 text-white'
                            : 'border border-gray-200 text-gray-600 hover:border-amber-400 dark:border-gray-600 dark:text-gray-300',
                    ]"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm text-gray-300 dark:text-gray-600"
                    v-html="link.label"
                />
            </template>
        </div>

        <!-- Ir al carrito -->
        <div v-if="count > 0" class="fixed right-6 bottom-6 z-50">
            <a
                href="/cart"
                class="flex items-center gap-2 rounded-full bg-amber-500 px-6 py-3 font-bold text-white shadow-xl transition hover:bg-amber-600"
            >
                <ShoppingCart class="h-5 w-5" />
                {{ t('menu.seeCart') }} ({{ count }})
            </a>
        </div>
    </div>
</template>
