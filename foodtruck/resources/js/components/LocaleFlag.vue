<script setup lang="ts">
import type { Locale } from '@/composables/useI18n';

const props = defineProps<{ locale: Locale; flagClass?: string }>();
</script>

<template>
    <!-- Bandera de España -->
    <svg v-if="props.locale === 'es'" :class="props.flagClass ?? 'h-4 w-6'" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Español">
        <rect width="20" height="15" fill="#c60b1e"/>
        <rect y="3.75" width="20" height="7.5" fill="#ffc400"/>
    </svg>

    <!-- Bandera catalunya -->
    <svg v-else-if="props.locale === 'ca'" :class="props.flagClass ?? 'h-4 w-6'" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Català">
        <rect width="20" height="15" fill="#FCDD09"/>
        <rect y="1.667" width="20" height="1.667" fill="#DA121A"/>
        <rect y="5"     width="20" height="1.667" fill="#DA121A"/>
        <rect y="8.333" width="20" height="1.667" fill="#DA121A"/>
        <rect y="11.667" width="20" height="1.667" fill="#DA121A"/>
    </svg>

    <!-- Bandera del Reino Unido -->
    <svg v-else-if="props.locale === 'en'" :class="props.flagClass ?? 'h-4 w-6'" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="English">
        <rect width="20" height="15" fill="#012169"/>
        <line x1="0" y1="0"  x2="20" y2="15" stroke="white" stroke-width="3"/>
        <line x1="20" y1="0" x2="0"  y2="15" stroke="white" stroke-width="3"/>
        <line x1="0" y1="0"  x2="20" y2="15" stroke="#C8102E" stroke-width="1.8"/>
        <line x1="20" y1="0" x2="0"  y2="15" stroke="#C8102E" stroke-width="1.8"/>
        <rect x="8" y="0" width="4" height="15" fill="white"/>
        <rect x="0" y="5.5" width="20" height="4" fill="white"/>
        <rect x="8.8" y="0" width="2.4" height="15" fill="#C8102E"/>
        <rect x="0" y="6.3" width="20" height="2.4" fill="#C8102E"/>
    </svg>
</template>
