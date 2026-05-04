<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    src: string;
    alt: string;
    class?: string;
    imgClass?: string;
    sizes?: string;
    widths?: number[];
}>();

const WIDTHS = props.widths ?? [400, 800, 1200];
const SIZES =
    props.sizes ?? '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw';

function isCloudinary(url: string): boolean {
    return url.includes('res.cloudinary.com');
}

/**
 * Inserts Cloudinary transformations after "/upload/" in the URL.
 * Handles existing transformations by prepending the new ones.
 * e.g. .../upload/v123/img.jpg  →  .../upload/f_auto,q_auto,w_400/v123/img.jpg
 */
function buildCloudinaryUrl(url: string, width: number): string {
    const marker = '/upload/';
    const idx = url.indexOf(marker);

    if (idx === -1) {
        return url;
    }

    const base = url.slice(0, idx + marker.length);
    const rest = url.slice(idx + marker.length);

    // If there are already transformations (not starting with "v\d"), prepend
    return `${base}f_auto,q_auto,w_${width}/${rest}`;
}

const optimizedSrc = computed(() => {
    if (!props.src || !isCloudinary(props.src)) {
        return props.src;
    }

    return buildCloudinaryUrl(props.src, WIDTHS[0]);
});

const srcset = computed(() => {
    if (!props.src || !isCloudinary(props.src)) {
        return undefined;
    }

    return WIDTHS.map((w) => `${buildCloudinaryUrl(props.src, w)} ${w}w`).join(
        ', ',
    );
});
</script>

<template>
    <img
        :src="optimizedSrc"
        :srcset="srcset"
        :sizes="srcset ? SIZES : undefined"
        :alt="alt"
        :class="imgClass ?? props.class"
        loading="lazy"
        decoding="async"
    />
</template>
