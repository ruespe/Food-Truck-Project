<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    reviews: Array<{
        id: number;
        user_name: string;
        user_email: string;
        rating: number;
        comment: string | null;
        visible: boolean;
        created_at: string;
    }>;
}>();

const page = usePage();
const flash = computed(() => (page.props as any).flash as { success?: string } | undefined);

const pending = computed(() => props.reviews.filter((r) => !r.visible).length);

function toggleVisible(id: number) {
    router.patch(`/admin/reviews/${id}/toggle-visible`, {}, { preserveScroll: true });
}

function destroy(id: number, name: string) {
    if (confirm(`¿Eliminar la reseña de ${name}?`)) {
        router.delete(`/admin/reviews/${id}`, { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Reseñas · Admin" />

    <!-- Flash -->
    <div
        v-if="flash?.success"
        class="mb-6 flex items-center gap-3 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-400"
    >
        {{ flash.success }}
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Reseñas</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ reviews.length }} reseña{{ reviews.length !== 1 ? 's' : '' }} en total
                <span
                    v-if="pending > 0"
                    class="ml-2 rounded-full bg-purple-500 px-2 py-0.5 text-xs font-bold text-white"
                >
                    {{ pending }} pendiente{{ pending !== 1 ? 's' : '' }}
                </span>
            </p>
        </div>
    </div>

    <!-- Sin reseñas -->
    <div
        v-if="reviews.length === 0"
        class="rounded-2xl border border-slate-200 bg-white px-8 py-20 text-center shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
    >
        <p class="mb-3 text-4xl">⭐</p>
        <p class="text-slate-500 dark:text-slate-400">No hay reseñas todavía</p>
    </div>

    <!-- Lista -->
    <div v-else class="flex flex-col gap-4">
        <div
            v-for="review in reviews"
            :key="review.id"
            class="rounded-2xl border transition"
            :class="
                review.visible
                    ? 'border-slate-200 bg-white shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800'
                    : 'border-purple-500/40 bg-white shadow-[0_0_0_1px_rgba(168,85,247,0.15)] dark:bg-slate-800'
            "
        >
            <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-start sm:justify-between">
                <!-- Contenido -->
                <div class="min-w-0 flex-1">
                    <div class="mb-1 flex flex-wrap items-center gap-2">
                        <!-- Avatar inicial -->
                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-amber-500/15 text-sm font-bold text-amber-600 dark:text-amber-400">
                            {{ review.user_name.charAt(0).toUpperCase() }}
                        </span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ review.user_name }}</span>
                        <span
                            v-if="!review.visible"
                            class="rounded-full bg-purple-500 px-2 py-0.5 text-[10px] font-bold tracking-wide text-white uppercase"
                        >
                            Pendiente
                        </span>
                        <span
                            v-else
                            class="rounded-full bg-green-500/20 px-2 py-0.5 text-[10px] font-bold tracking-wide text-green-500 uppercase"
                        >
                            Visible
                        </span>
                        <span class="text-xs text-slate-500">{{ review.created_at }}</span>
                    </div>
                    <a :href="`mailto:${review.user_email}`" class="mb-2 block text-sm text-amber-400 hover:underline">
                        {{ review.user_email }}
                    </a>
                    <!-- Estrellas -->
                    <div class="mb-2 flex gap-0.5">
                        <span
                            v-for="i in 5"
                            :key="i"
                            :class="i <= review.rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600'"
                            class="text-lg"
                        >★</span>
                    </div>
                    <p
                        v-if="review.comment"
                        class="text-sm leading-relaxed text-slate-600 dark:text-slate-300"
                    >
                        "{{ review.comment }}"
                    </p>
                    <p v-else class="text-sm italic text-slate-400">Sin comentario</p>
                </div>

                <!-- Acciones -->
                <div class="flex shrink-0 gap-2 sm:flex-col sm:items-end">
                    <button
                        class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                        :class="
                            review.visible
                                ? 'bg-slate-500/15 text-slate-500 hover:bg-slate-500/25'
                                : 'bg-green-500/15 text-green-400 hover:bg-green-500/25'
                        "
                        @click="toggleVisible(review.id)"
                    >
                        {{ review.visible ? '✕ Ocultar' : '✓ Aprobar' }}
                    </button>
                    <button
                        class="rounded-lg bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-400 transition hover:bg-red-500/20"
                        @click="destroy(review.id, review.user_name)"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
