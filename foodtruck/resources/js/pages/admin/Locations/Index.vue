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
const mapLat = ref(40.4153);
const mapLng = ref(-3.7074);

const form = useForm({
    name: '',
    latitude: 40.4153,
    longitude: -3.7074,
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
    <template #header><h1 class="text-lg font-semibold text-gray-800">Ubicación del truck</h1></template>

    <div class="mb-4 flex justify-end">
        <button class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600" @click="openCreate">
            + Nueva ubicación
        </button>
    </div>

    <!-- Formulario con mapa arrastrable -->
    <div v-if="showForm" class="mb-6 grid gap-6 rounded-2xl bg-white p-6 shadow lg:grid-cols-2">
        <form class="space-y-3" @submit.prevent="submit">
            <h2 class="mb-2 font-semibold text-gray-700">{{ editId ? 'Editar ubicación' : 'Nueva ubicación' }}</h2>
            <input v-model="form.name" type="text" placeholder="Nombre del lugar" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-gray-500">Latitud</label>
                    <input :value="parseFloat(String(form.latitude)).toFixed(7)" type="text" readonly class="w-full rounded-lg border bg-gray-50 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-xs text-gray-500">Longitud</label>
                    <input :value="parseFloat(String(form.longitude)).toFixed(7)" type="text" readonly class="w-full rounded-lg border bg-gray-50 px-3 py-2 text-sm" />
                </div>
            </div>
            <input v-model="form.date" type="date" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            <div class="grid grid-cols-2 gap-3">
                <input v-model="form.start_time" type="time" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
                <input v-model="form.end_time" type="time" required class="w-full rounded-lg border px-3 py-2 focus:border-amber-400 focus:outline-none" />
            </div>
            <p class="text-xs text-gray-400">Arrastra el pin en el mapa para ajustar la ubicación exacta.</p>
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-amber-500 px-5 py-2 text-sm font-bold text-white hover:bg-amber-600">Guardar</button>
                <button type="button" class="rounded-lg border px-5 py-2 text-sm text-gray-600 hover:bg-gray-50" @click="showForm = false">Cancelar</button>
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
    <div class="rounded-2xl bg-white shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500">
                    <th class="px-4 py-3">Lugar</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Horario</th>
                    <th class="px-4 py-3">Coordenadas</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr v-for="loc in locations" :key="loc.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ loc.name }}</td>
                    <td class="px-4 py-3">{{ loc.date }}</td>
                    <td class="px-4 py-3">{{ loc.start_time.slice(0,5) }} – {{ loc.end_time.slice(0,5) }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ loc.latitude }}, {{ loc.longitude }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button class="rounded px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200" @click="openEdit(loc)">Editar</button>
                            <button class="rounded px-3 py-1 text-xs font-medium bg-red-100 text-red-600 hover:bg-red-200" @click="destroy(loc.id)">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr v-if="locations.length === 0">
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">No hay ubicaciones registradas</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
