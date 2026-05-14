<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { X } from 'lucide-vue-next';
import CloudinaryImage from '@/components/CloudinaryImage.vue';
import ImagePlaceholder from '@/components/ImagePlaceholder.vue';
import { useCart } from '@/composables/useCart';
import { useI18n, td } from '@/composables/useI18n';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });

const { items, total, remove, updateQty, clear } = useCart();
const { t } = useI18n();

const page = usePage();
const closedError = computed(() => (page.props.errors as Record<string, string>)?.closed ?? null);

function checkout() {
    router.post(
        '/orders',
        {
            items: items.value.map((i) => ({ id: i.id, quantity: i.quantity })),
        },
        {
            onSuccess: () => clear(),
        },
    );
}
</script>

<template>
    <Head title="Carrito · FoodTruck" />
    <div class="mx-auto max-w-2xl px-4 py-10">
        <h1 class="mb-6 text-3xl font-bold text-gray-800 dark:text-white">
            {{ t('cart.title') }}
        </h1>

        <!-- Error de horario: truck cerrado -->
        <div
            v-if="closedError"
            class="mb-5 flex items-start gap-3 rounded-2xl border border-red-300 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-sm dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-400"
        >
            <span class="text-lg leading-none">🔒</span>
            <span>{{ closedError }}</span>
        </div>

        <div
            v-if="items.length === 0"
            class="rounded-2xl bg-white p-10 text-center text-gray-500 shadow dark:bg-gray-800 dark:text-gray-400"
        >
            {{ t('cart.empty') }}
            <a href="/menu" class="text-amber-500 underline">{{
                t('cart.seeMenu')
            }}</a>
        </div>

        <div v-else>
            <ul
                class="mb-6 divide-y rounded-2xl bg-white shadow dark:divide-gray-700 dark:bg-gray-800"
            >
                <li
                    v-for="item in items"
                    :key="item.id"
                    class="flex items-center gap-4 p-4"
                >
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-2xl dark:bg-amber-900/30"
                    >
                        <CloudinaryImage
                            v-if="item.image"
                            :src="item.image"
                            img-class="h-full w-full rounded-xl object-cover"
                            :alt="td(item.name)"
                            sizes="80px"
                            :widths="[80, 160]"
                        />
                        <span v-else
                            ><ImagePlaceholder
                                class="h-8 w-8 text-amber-300 dark:text-amber-700"
                        /></span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ td(item.name) }}
                        </p>
                        <p class="text-sm text-amber-600">
                            {{ parseFloat(String(item.price)).toFixed(2) }}
                            {{ t('cart.unit') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            class="rounded-full bg-gray-100 px-2 py-1 text-sm font-bold hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                            @click="updateQty(item.id, item.quantity - 1)"
                        >
                            −
                        </button>
                        <span
                            class="w-6 text-center text-sm font-semibold dark:text-white"
                            >{{ item.quantity }}</span
                        >
                        <button
                            class="rounded-full bg-gray-100 px-2 py-1 text-sm font-bold hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                            @click="updateQty(item.id, item.quantity + 1)"
                        >
                            +
                        </button>
                    </div>
                    <p
                        class="w-16 text-right font-bold text-gray-700 dark:text-gray-200"
                    >
                        {{
                            (
                                parseFloat(String(item.price)) * item.quantity
                            ).toFixed(2)
                        }}
                        €
                    </p>
                    <button
                        class="ml-2 rounded-full p-1 text-red-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                        @click="remove(item.id)"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </li>
            </ul>

            <div
                class="flex items-center justify-between rounded-2xl bg-white p-5 shadow dark:bg-gray-800"
            >
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ t('cart.total') }}
                    </p>
                    <p class="text-2xl font-extrabold text-amber-600">
                        {{ parseFloat(String(total)).toFixed(2) }} €
                    </p>
                </div>
                <button
                    class="rounded-full bg-amber-500 px-8 py-3 font-bold text-white shadow hover:bg-amber-600"
                    @click="checkout"
                >
                    {{ t('cart.checkout') }}
                </button>
            </div>
        </div>
    </div>
</template>
