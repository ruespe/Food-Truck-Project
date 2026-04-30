<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import MapLocation from '@/components/MapLocation.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    locations: Array<{
        id: number;
        name: string;
        latitude: number;
        longitude: number;
        date: string;
        start_time: string;
        end_time: string;
    }>;
}>();

const showForm = ref(false);
const editId = ref<number | null>(null);
const mapLat = ref(41.5381);
const mapLng = ref(2.4449);

const form = useForm({
    name: '',
    latitude: 41.5381,
    longitude: 2.4449,
    date: new Date().toISOString().slice(0, 10),
    start_time: '12:00',
    end_time: '22:00',
});

function openCreate() {
    editId.value = null;
    form.reset();
    mapLat.value = 40.4153;
    mapLng.value = -3.7074;
    showForm.value = true;
}

function openEdit(loc: typeof props.locations[number]) {
    editId.value = loc.id;
    form.name = loc.name;
    form.latitude = loc.latitude;
    form.longitude = loc.longitude;
    form.date = loc.date;
    form.start_time = loc.start_time.slice(0, 5);
    form.end_time = loc.end_time.slice(0, 5);
    mapLat.value = loc.latitude;
    mapLng.value = loc.longitude;
    showForm.value = true;
}

function onPinMoved(field: 'latitude' | 'longitude', value: number) {
    form[field] = value;
    if (field === 'latitude') mapLat.value = value;
    else mapLng.value = value;
}

function submit() {
    if (editId.value) {
        form.patch(`/admin/locations/${editId.value}`, { onSuccess: () => { showForm.value = false; } });
    } else {
        form.post('/admin/locations', { onSuccess: () => { showForm.value = false; } });
    }
}

function destroy(id: number) {
    if (confirm('¿Eliminar esta ubicación?')) router.delete(`/admin/locations/${id}`);
}
</script>

<template>
    <Head title="Ubicaciones · Admin" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Ubicación del truck</h1>
            <p class="mt-1 text-sm text-slate-400">Programa dónde estará el food truck cada día</p>
        </div>
        <button class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600 transition" @click="openCreate">
            + Nueva ubicación
        </button>
    </div>

    <!-- Formulario con mapa arrastrable -->
    <div v-if="showForm" class="mb-6 grid gap-6 rounded-2xl bg-slate-800 border border-slate-700/50 p-6 lg:grid-cols-2">
        <form class="space-y-3" @submit.prevent="submit">
            <h2 class="mb-2 font-semibold text-white">{{ editId ? 'Editar ubicación' : 'Nueva ubicación' }}</h2>
            <input v-model="form.name" type="text" placeholder="Nombre del lugar" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white placeholder-slate-400 focus:border-amber-500 focus:outline-none" />
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-slate-400">Latitud</label>
                    <input :value="parseFloat(String(form.latitude)).toFixed(7)" type="text" readonly class="w-full rounded-xl border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-slate-300" />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Longitud</label>
                    <input :value="parseFloat(String(form.longitude)).toFixed(7)" type="text" readonly class="w-full rounded-xl border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-slate-300" />
                </div>
            </div>
            <input v-model="form.date" type="date" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white focus:border-amber-500 focus:outline-none" />
            <div class="grid grid-cols-2 gap-3">
                <input v-model="form.start_time" type="time" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white focus:border-amber-500 focus:outline-none" />
                <input v-model="form.end_time" type="time" required class="w-full rounded-xl border border-slate-600 bg-slate-700 px-3 py-2.5 text-white focus:border-amber-500 focus:outline-none" />
            </div>
            <p class="text-xs text-slate-500">Arrastra el pin en el mapa para ajustar la ubicación exacta.</p>
            <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2 text-sm font-bold text-white hover:bg-amber-600 transition">Guardar</button>
                <button type="button" class="rounded-xl border border-slate-600 px-5 py-2 text-sm text-slate-300 hover:bg-slate-700 transition" @click="showForm = false">Cancelar</button>
            </div>
        </form>

        <!-- Mapa con pin arrastrable -->
        <div class="h-72 overflow-hidden rounded-xl lg:h-auto">
            <MapLocation
                :lat="mapLat"
                :lng="mapLng"
                :draggable="true"
                @update:lat="onPinMoved('latitude', $event)"
                @update:lng="onPinMoved('longitude', $event)"
            />
        </div>
    </div>

    <!-- Listado de ubicaciones -->
    <div class="rounded-2xl bg-slate-800 border border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Lugar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Horario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Coordenadas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <tr v-for="loc in locations" :key="loc.id" class="transition hover:bg-slate-700/30">
                        <td class="px-6 py-4 font-medium text-white">{{ loc.name }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ loc.date }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ loc.start_time.slice(0,5) }} – {{ loc.end_time.slice(0,5) }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ loc.latitude }}, {{ loc.longitude }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button class="rounded-lg px-3 py-1 text-xs font-medium bg-slate-700 text-slate-200 hover:bg-slate-600 transition" @click="openEdit(loc)">Editar</button>
                                <button class="rounded-lg px-3 py-1 text-xs font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 transition" @click="destroy(loc.id)">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="locations.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">No hay ubicaciones registradas</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
