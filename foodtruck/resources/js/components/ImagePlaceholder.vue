<script setup lang="ts">
defineProps<{ class?: string }>();
</script>

<template>
    <svg
        :class="$props.class ?? 'h-12 w-12 text-gray-300 dark:text-gray-600'"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        aria-hidden="true"
    >
        <rect width="24" height="24" rx="3" fill="currentColor" opacity="0.15" />
        <path
            d="M4 4h16v16H4z"
            fill="none"
        />
        <path
            d="M21 15l-5-5L9 16"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
        <path
            d="M3 9l4 4"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
            opacity="0.5"
        />
        <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" />
        <rect
            x="2" y="2" width="20" height="20" rx="3"
            stroke="currentColor"
            stroke-width="1.5"
            fill="none"
        />
    </svg>
</template>
