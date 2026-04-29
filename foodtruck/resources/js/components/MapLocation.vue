<script setup lang="ts">
import { onMounted, ref, watch, nextTick } from 'vue';

// Leaflet only works in the browser – never import it during SSR
let L: typeof import('leaflet') | null = null;

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
let map: import('leaflet').Map | null = null;
let marker: import('leaflet').Marker | null = null;

onMounted(async () => {
    await nextTick();
    if (!mapEl.value) return;

    // Dynamic import so Leaflet (which needs `window`) never runs on SSR
    const leafletModule = await import('leaflet');
    L = leafletModule;

    // Fix default marker icons broken by Vite
    const markerIcon2x = (await import('leaflet/dist/images/marker-icon-2x.png')).default;
    const markerIcon   = (await import('leaflet/dist/images/marker-icon.png')).default;
    const markerShadow = (await import('leaflet/dist/images/marker-shadow.png')).default;
    delete (L.Icon.Default.prototype as any)._getIconUrl;
    L.Icon.Default.mergeOptions({ iconUrl: markerIcon, iconRetinaUrl: markerIcon2x, shadowUrl: markerShadow });

    map = L.map(mapEl.value, {
        scrollWheelZoom: props.scrollZoom,
    }).setView([props.lat, props.lng], props.zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    marker = L.marker([props.lat, props.lng], { draggable: props.draggable }).addTo(map);

    setTimeout(() => map?.invalidateSize(), 300);

    if (props.draggable) {
        marker.on('dragend', () => {
            const latlng = marker!.getLatLng();
            emit('update:lat', latlng.lat);
            emit('update:lng', latlng.lng);
        });

        map.on('click', (e: import('leaflet').LeafletMouseEvent) => {
            marker!.setLatLng(e.latlng);
            emit('update:lat', e.latlng.lat);
            emit('update:lng', e.latlng.lng);
        });
    }
});

watch(
    () => [props.lat, props.lng] as [number, number],
    ([lat, lng]) => {
        if (map && marker && L) {
            map.setView([lat, lng]);
            marker.setLatLng([lat, lng]);
        }
    },
);
</script>

<template>
    <div ref="mapEl" class="w-full rounded-lg" style="height: 100%; min-height: 400px;" />
</template>
