<script setup lang="ts">
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { onMounted, ref, watch } from 'vue';

// Fix Leaflet default marker icon with Vite
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete (L.Icon.Default.prototype as any)._getIconUrl;
L.Icon.Default.mergeOptions({
    iconUrl: markerIcon,
    iconRetinaUrl: markerIcon2x,
    shadowUrl: markerShadow,
});

const props = withDefaults(
    defineProps<{
        lat: number;
        lng: number;
        draggable?: boolean;
        zoom?: number;
        scrollZoom?: boolean;
    }>(),
    { draggable: false, zoom: 15, scrollZoom: true },
);

const emit = defineEmits<{
    (e: 'update:lat', value: number): void;
    (e: 'update:lng', value: number): void;
}>();

const mapEl = ref<HTMLElement | null>(null);
let map: L.Map | null = null;
let marker: L.Marker | null = null;

onMounted(() => {
    if (!mapEl.value) return;

    map = L.map(mapEl.value, {
        scrollWheelZoom: props.scrollZoom,
    }).setView([props.lat, props.lng], props.zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    marker = L.marker([props.lat, props.lng], { draggable: props.draggable }).addTo(map);

    if (props.draggable) {
        marker.on('dragend', () => {
            const latlng = marker!.getLatLng();
            emit('update:lat', latlng.lat);
            emit('update:lng', latlng.lng);
        });
    }
});

watch(
    () => [props.lat, props.lng] as [number, number],
    ([lat, lng]) => {
        if (map && marker) {
            map.setView([lat, lng]);
            marker.setLatLng([lat, lng]);
        }
    },
);
</script>

<template>
    <div ref="mapEl" class="h-full w-full rounded-lg" style="min-height: 300px" />
</template>
