<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { useI18n } from '@/composables/useI18n';
import { useCart } from '@/composables/useCart';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

defineOptions({ layout: ClientLayout });

defineProps<{
    order: { id: number; total_price: number };
}>();

const { t } = useI18n();
const { clear } = useCart();

onMounted(() => {
    clear();
});
</script>

<template>
    <Head title="Pago completado · FoodTruck" />
    <div class="flex min-h-[60vh] items-center justify-center px-4">
        <div class="rounded-3xl bg-white p-10 text-center shadow-xl dark:bg-gray-800">

            <!-- SVG Celebración -->
            <div class="celebration mb-6 flex justify-center">
                <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Círculo de fondo animado -->
                    <circle cx="60" cy="60" r="54" fill="#fef3c7" class="pulse-circle"/>

                    <!-- Check animado -->
                    <circle cx="60" cy="60" r="40" fill="#f59e0b"/>
                    <polyline points="40,62 54,76 82,46" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" class="check-mark"/>

                    <!-- Confetti partículas -->
                    <rect x="10" y="18" width="7" height="7" rx="1.5" fill="#ef4444" class="confetti c1"/>
                    <rect x="100" y="14" width="6" height="6" rx="1.5" fill="#3b82f6" class="confetti c2"/>
                    <rect x="104" y="88" width="8" height="8" rx="2" fill="#10b981" class="confetti c3"/>
                    <rect x="8" y="90" width="6" height="6" rx="1.5" fill="#a855f7" class="confetti c4"/>
                    <circle cx="24" cy="45" r="4" fill="#f59e0b" class="confetti c5"/>
                    <circle cx="96" cy="50" r="3.5" fill="#ec4899" class="confetti c6"/>
                    <circle cx="60" cy="10" r="3" fill="#06b6d4" class="confetti c7"/>
                    <rect x="52" y="105" width="5" height="5" rx="1" fill="#f97316" class="confetti c8"/>
                    <circle cx="30" cy="100" r="3" fill="#eab308" class="confetti c9"/>
                    <circle cx="92" cy="22" r="3" fill="#ef4444" class="confetti c10"/>
                </svg>
            </div>

            <h1 class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">{{ t('payment.success.title') }}</h1>
            <p class="mb-1 text-gray-500 dark:text-gray-400">{{ t('payment.success.order') }}{{ order.id }}</p>
            <p class="mb-6 text-xl font-bold text-amber-600">
                {{ parseFloat(String(order.total_price)).toFixed(2) }} €
            </p>
            <a href="/orders" class="rounded-full bg-amber-500 px-8 py-3 font-bold text-white hover:bg-amber-600">
                {{ t('payment.success.cta') }}
            </a>
        </div>
    </div>
</template>

<style scoped>
/* Círculo pulsante de fondo */
.pulse-circle {
    transform-origin: 60px 60px;
    animation: pulse 2s ease-in-out infinite;
}

/* Check dibujándose */
.check-mark {
    stroke-dasharray: 60;
    stroke-dashoffset: 60;
    animation: draw-check 0.6s ease-out 0.2s forwards;
}

/* Confetti flotando */
.confetti {
    animation: float-confetti 3s ease-in-out infinite;
}
.c1  { animation-delay: 0s;    animation-duration: 3.2s; }
.c2  { animation-delay: 0.3s;  animation-duration: 2.8s; }
.c3  { animation-delay: 0.6s;  animation-duration: 3.5s; }
.c4  { animation-delay: 0.9s;  animation-duration: 2.9s; }
.c5  { animation-delay: 0.15s; animation-duration: 3.1s; }
.c6  { animation-delay: 0.45s; animation-duration: 3.4s; }
.c7  { animation-delay: 0.75s; animation-duration: 2.7s; }
.c8  { animation-delay: 1.0s;  animation-duration: 3.3s; }
.c9  { animation-delay: 0.2s;  animation-duration: 3.0s; }
.c10 { animation-delay: 0.55s; animation-duration: 2.6s; }

@keyframes pulse {
    0%, 100% { transform: scale(1);    opacity: 1; }
    50%       { transform: scale(1.08); opacity: 0.75; }
}

@keyframes draw-check {
    to { stroke-dashoffset: 0; }
}

@keyframes float-confetti {
    0%, 100% { transform: translateY(0)   rotate(0deg);   opacity: 1; }
    50%       { transform: translateY(-8px) rotate(15deg); opacity: 0.7; }
}
</style>
