<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-svh bg-gray-50 dark:bg-gray-950 flex flex-col">
        <!-- Header amber -->
        <div class="bg-amber-500 py-6 text-center shadow-md">
            <Link :href="home()" class="inline-flex items-center justify-center group">
                <img src="/logoFoodtruck.png" alt="FoodTruck" class="h-[40px] w-auto transition group-hover:opacity-80" />
            </Link>
        </div>

        <!-- Card -->
        <div class="flex flex-1 items-center justify-center p-6">
            <div class="w-full max-w-md">
                <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-xl overflow-hidden">
                    <!-- Card header -->
                    <div class="px-8 pt-8 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ title }}</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ description }}</p>
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
