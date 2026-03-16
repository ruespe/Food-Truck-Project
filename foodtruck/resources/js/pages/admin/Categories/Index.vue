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
    <template #header><h1 class="text-lg font-semibold text-gray-800">Categorías</h1></template>

    <div class="mb-4 flex justify-end">
        <button class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600" @click="openCreate">
            + Nueva categoría
        </button>
    </div>

    <!-- Formulario inline -->
    <div v-if="showForm" class="mb-6 rounded-2xl bg-white p-6 shadow">
        <h2 class="mb-4 font-semibold text-gray-700">{{ editId ? 'Editar categoría' : 'Nueva categoría' }}</h2>
        <form class="space-y-3" @submit.prevent="submit">
            <input v-model="form.name" type="text" placeholder="Nombre" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            <input v-model="form.description" type="text" placeholder="Descripción (opcional)" class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-amber-500 px-5 py-2 text-sm font-bold text-white hover:bg-amber-600">Guardar</button>
                <button type="button" class="rounded-lg border px-5 py-2 text-sm text-gray-600 hover:bg-gray-50" @click="showForm = false">Cancelar</button>
            </div>
        </form>
    </div>

    <div class="rounded-2xl bg-white shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500">
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Descripción</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr v-for="cat in categories" :key="cat.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ cat.name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ cat.description ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button class="rounded px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200" @click="openEdit(cat)">Editar</button>
                            <button class="rounded px-3 py-1 text-xs font-medium bg-red-100 text-red-600 hover:bg-red-200" @click="destroy(cat.id)">Eliminar</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
