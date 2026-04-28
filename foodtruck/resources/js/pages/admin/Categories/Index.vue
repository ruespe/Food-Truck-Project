<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });

defineProps<{
    categories: Array<{ id: number; name: string; description: string | null }>;
}>();

const showForm = ref(false);
const editId = ref<number | null>(null);

const form = useForm({ name: '', description: '' });

function openCreate() {
    editId.value = null;
    form.reset();
    showForm.value = true;
}

function openEdit(cat: { id: number; name: string; description: string | null }) {
    editId.value = cat.id;
    form.name = cat.name;
    form.description = cat.description ?? '';
    showForm.value = true;
}

function submit() {
    if (editId.value) {
        form.patch(`/admin/categories/${editId.value}`, { onSuccess: () => { showForm.value = false; form.reset(); } });
    } else {
        form.post('/admin/categories', { onSuccess: () => { showForm.value = false; form.reset(); } });
    }
}

function destroy(id: number) {
    if (confirm('¿Eliminar esta categoría?')) router.delete(`/admin/categories/${id}`);
}
</script>

<template>
    <Head title="Categorías · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Categorías</h1>
            <p class="mt-1 text-sm text-slate-400">Organiza los productos por categoría</p>
        </div>
        <button class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600 transition" @click="openCreate">
            + Nueva categoría
        </button>
    </div>

    <!-- Formulario inline -->
    <div v-if="showForm" class="mb-6 rounded-2xl bg-slate-800 border border-slate-700/50 p-6">
        <h2 class="mb-4 font-semibold text-white">{{ editId ? 'Editar categoría' : 'Nueva categoría' }}</h2>
        <form class="space-y-3" @submit.prevent="submit">
            <input v-model="form.name" type="text" placeholder="Nombre" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white placeholder-slate-400 focus:border-amber-500 focus:outline-none" />
            <input v-model="form.description" type="text" placeholder="Descripción (opcional)" class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white placeholder-slate-400 focus:border-amber-500 focus:outline-none" />
            <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2 text-sm font-bold text-white hover:bg-amber-600 transition">Guardar</button>
                <button type="button" class="rounded-xl border border-slate-600 px-5 py-2 text-sm text-slate-300 hover:bg-slate-700 transition" @click="showForm = false">Cancelar</button>
            </div>
        </form>
    </div>

    <div class="rounded-2xl bg-slate-800 border border-slate-700/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-700">
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                <tr v-for="cat in categories" :key="cat.id" class="transition hover:bg-slate-700/30">
                    <td class="px-6 py-4 font-medium text-white">{{ cat.name }}</td>
                    <td class="px-6 py-4 text-slate-400">{{ cat.description ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button class="rounded-lg px-3 py-1 text-xs font-medium bg-slate-700 text-slate-200 hover:bg-slate-600 transition" @click="openEdit(cat)">Editar</button>
                            <button class="rounded-lg px-3 py-1 text-xs font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 transition" @click="destroy(cat.id)">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr v-if="categories.length === 0">
                    <td colspan="3" class="px-6 py-10 text-center text-slate-500">No hay categorías aún</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
