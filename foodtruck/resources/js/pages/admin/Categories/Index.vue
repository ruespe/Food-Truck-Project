<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

type Category = {
    id: number;
    name: Record<string, string>;
    description: Record<string, string> | null;
};

defineProps<{
    categories: Category[];
}>();

const showForm = ref(false);
const editId = ref<number | null>(null);

const form = useForm({
    name: { es: '', ca: '', en: '' },
    description: { es: '', ca: '', en: '' },
});

const page = usePage();
const deleteError = computed(
    () => (page.props.errors as any)?.category as string | undefined,
);

function openCreate() {
    editId.value = null;
    form.name = { es: '', ca: '', en: '' };
    form.description = { es: '', ca: '', en: '' };
    showForm.value = true;
}

function openEdit(cat: Category) {
    editId.value = cat.id;
    form.name = {
        es: cat.name?.es ?? '',
        ca: cat.name?.ca ?? '',
        en: cat.name?.en ?? '',
    };
    form.description = {
        es: cat.description?.es ?? '',
        ca: cat.description?.ca ?? '',
        en: cat.description?.en ?? '',
    };
    showForm.value = true;
}

function submit() {
    if (editId.value) {
        form.patch(`/admin/categories/${editId.value}`, {
            onSuccess: () => {
                showForm.value = false;
            },
        });
    } else {
        form.post('/admin/categories', {
            onSuccess: () => {
                showForm.value = false;
            },
        });
    }
}

function destroy(id: number) {
    if (confirm('¿Eliminar esta categoría?')) {
        router.delete(`/admin/categories/${id}`);
    }
}

const inputClass =
    'w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:outline-none dark:border-slate-600 dark:bg-slate-700 dark:text-white';
</script>

<template>
    <Head title="Categorías · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Categorías
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Organiza los productos por categoría
            </p>
        </div>
        <button
            class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-white transition hover:bg-amber-600"
            @click="openCreate"
        >
            + Nueva categoría
        </button>
    </div>

    <!-- Error al eliminar -->
    <div
        v-if="deleteError"
        class="mb-5 flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-400"
    >
        ⚠️
        {{ deleteError }}
    </div>

    <!-- Formulario inline -->
    <div
        v-if="showForm"
        class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
    >
        <h2 class="mb-4 font-semibold text-slate-900 dark:text-white">
            {{ editId ? 'Editar categoría' : 'Nueva categoría' }}
        </h2>
        <form class="space-y-5" @submit.prevent="submit">
            <!-- Nombre multilingüe -->
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                    >Nombre</label
                >
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span
                            class="w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                            >ES</span
                        >
                        <input
                            v-model="form.name.es"
                            type="text"
                            placeholder="Nombre en español"
                            required
                            :class="inputClass"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                            >CA</span
                        >
                        <input
                            v-model="form.name.ca"
                            type="text"
                            placeholder="Nom en català"
                            :class="inputClass"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                            >EN</span
                        >
                        <input
                            v-model="form.name.en"
                            type="text"
                            placeholder="Name in English"
                            :class="inputClass"
                        />
                    </div>
                </div>
            </div>

            <!-- Descripción multilingüe -->
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                    >Descripción
                    <span class="font-normal text-slate-400"
                        >(opcional)</span
                    ></label
                >
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span
                            class="w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                            >ES</span
                        >
                        <input
                            v-model="form.description.es"
                            type="text"
                            placeholder="Descripción en español"
                            :class="inputClass"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                            >CA</span
                        >
                        <input
                            v-model="form.description.ca"
                            type="text"
                            placeholder="Descripció en català"
                            :class="inputClass"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="w-6 shrink-0 text-center text-xs font-bold text-slate-400"
                            >EN</span
                        >
                        <input
                            v-model="form.description.en"
                            type="text"
                            placeholder="Description in English"
                            :class="inputClass"
                        />
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button
                    type="submit"
                    class="rounded-xl bg-amber-500 px-5 py-2 text-sm font-bold text-white transition hover:bg-amber-600"
                >
                    Guardar
                </button>
                <button
                    type="button"
                    class="rounded-xl border border-slate-200 px-5 py-2 text-sm text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700"
                    @click="showForm = false"
                >
                    Cancelar
                </button>
            </div>
        </form>
    </div>

    <!-- Mobile cards -->
    <div class="sm:hidden space-y-3">
        <div v-if="categories.length === 0" class="rounded-2xl border border-slate-200 bg-white dark:border-[#66c0f4]/50 dark:bg-slate-800 px-6 py-10 text-center text-slate-500">
            No hay categorías aún
        </div>
        <div
            v-for="cat in categories"
            :key="cat.id"
            class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800 p-4 space-y-2"
        >
            <p class="font-medium text-slate-900 dark:text-white">{{ cat.name?.es ?? cat.name }}</p>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ cat.description?.es ?? '—' }}</p>
            <div class="flex gap-2 pt-1">
                <button
                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600"
                    @click="openEdit(cat)"
                >Editar</button>
                <button
                    class="rounded-lg bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-400 transition hover:bg-red-500/20"
                    @click="destroy(cat.id)"
                >Eliminar</button>
            </div>
        </div>
    </div>

    <!-- Desktop table -->
    <div
        class="hidden sm:block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-[#66c0f4]/50 dark:bg-slate-800"
    >
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 dark:border-[#66c0f4]">
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                    >
                        Nombre
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                    >
                        Descripción
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400"
                    >
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                <tr
                    v-for="cat in categories"
                    :key="cat.id"
                    class="transition hover:bg-slate-50 dark:hover:bg-slate-700/30"
                >
                    <td
                        class="px-6 py-4 font-medium text-slate-900 dark:text-white"
                    >
                        {{ cat.name?.es ?? cat.name }}
                    </td>
                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                        {{ cat.description?.es ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button
                                class="rounded-lg border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600"
                                @click="openEdit(cat)"
                            >
                                Editar
                            </button>
                            <button
                                class="rounded-lg bg-red-500/10 px-3 py-1 text-xs font-medium text-red-400 transition hover:bg-red-500/20"
                                @click="destroy(cat.id)"
                            >
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="categories.length === 0">
                    <td
                        colspan="3"
                        class="px-6 py-10 text-center text-slate-500"
                    >
                        No hay categorías aún
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
