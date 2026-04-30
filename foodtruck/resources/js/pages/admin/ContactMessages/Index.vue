<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    messages: Array<{
        id: number;
        name: string;
        email: string;
        message: string;
        read: boolean;
        created_at: string;
    }>;
}>();

const unread = computed(() => props.messages.filter((m) => !m.read).length);

function markRead(id: number) {
    router.patch(`/admin/contact/${id}/read`, {}, { preserveScroll: true });
}

function destroy(id: number) {
    if (confirm('¿Eliminar este mensaje?')) {
        router.delete(`/admin/contact/${id}`, { preserveScroll: true });
    }
}

function formatDate(iso: string) {
    return new Date(iso).toLocaleString('es-ES', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
}
</script>

<template>
    <Head title="Mensajes de contacto · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Mensajes de contacto</h1>
            <p class="mt-1 text-sm text-slate-400">
                {{ messages.length }} mensaje{{ messages.length !== 1 ? 's' : '' }} en total
                <span v-if="unread > 0" class="ml-2 rounded-full bg-amber-500 px-2 py-0.5 text-xs font-bold text-white">
                    {{ unread }} sin leer
                </span>
            </p>
        </div>
    </div>

    <!-- Sin mensajes -->
    <div v-if="messages.length === 0" class="rounded-2xl bg-slate-800 border border-slate-700/50 px-8 py-20 text-center">
        <p class="text-4xl mb-3">📭</p>
        <p class="text-slate-400">No hay mensajes de contacto aún</p>
    </div>

    <!-- Lista de mensajes -->
    <div v-else class="flex flex-col gap-4">
        <div
            v-for="msg in messages"
            :key="msg.id"
            class="rounded-2xl border transition"
            :class="msg.read
                ? 'border-slate-700/50 bg-slate-800'
                : 'border-amber-500/40 bg-slate-800 shadow-[0_0_0_1px_rgba(245,158,11,0.2)]'"
        >
            <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-start sm:justify-between">
                <!-- Info del remitente -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="font-semibold text-white">{{ msg.name }}</span>
                        <span v-if="!msg.read" class="rounded-full bg-amber-500 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Nuevo</span>
                        <span class="text-xs text-slate-500">{{ formatDate(msg.created_at) }}</span>
                    </div>
                    <a :href="`mailto:${msg.email}`" class="text-sm text-amber-400 hover:underline">{{ msg.email }}</a>
                    <p class="mt-3 whitespace-pre-wrap text-sm leading-relaxed text-slate-300">{{ msg.message }}</p>
                </div>

                <!-- Acciones -->
                <div class="flex shrink-0 gap-2 sm:flex-col sm:items-end">
                    <button
                        v-if="!msg.read"
                        class="rounded-lg bg-green-500/15 px-3 py-1.5 text-xs font-medium text-green-400 transition hover:bg-green-500/25"
                        @click="markRead(msg.id)"
                    >
                        ✓ Marcar leído
                    </button>
                    <span v-else class="text-xs text-slate-500 italic">Leído</span>
                    <button
                        class="rounded-lg bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-400 transition hover:bg-red-500/20"
                        @click="destroy(msg.id)"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
