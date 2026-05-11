<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col bg-gray-50 dark:bg-gray-950">
        <!-- Header amber -->
        <div class="bg-amber-500 py-6 text-center shadow-md">
            <Link
                href="/"
                class="group inline-flex items-center justify-center"
            >
                <img
                    src="/logoFoodtruck.png"
                    alt="FoodTruck"
                    class="h-[40px] w-auto transition group-hover:opacity-80"
                />
            </Link>
        </div>

        <!-- Card -->
        <div class="flex flex-1 items-center justify-center p-6">
            <div class="w-full max-w-md">
                <div
                    class="overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-gray-900"
                >
                    <!-- Card header -->
                    <div
                        class="border-b border-gray-100 px-8 pt-8 pb-6 dark:border-gray-800"
                    >
                        <h1
                            class="text-2xl font-bold text-gray-800 dark:text-white"
                        >
                            {{ title }}
                        </h1>
                        <p
                            class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                        >
                            {{ description }}
                        </p>
                    </div>
                    <!-- Card body -->
                    <div class="px-8 py-8">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
