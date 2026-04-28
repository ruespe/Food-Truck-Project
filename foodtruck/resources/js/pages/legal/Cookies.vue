<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });
</script>

<template>
    <Head title="Política de cookies · FoodTruck" />
    <div class="mx-auto max-w-3xl px-4 py-14">
        <h1 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">Política de cookies</h1>
        <p class="mb-8 text-sm text-gray-400">Última actualización: abril de 2026</p>

        <div class="space-y-6 text-gray-700 dark:text-gray-300">

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">¿Qué son las cookies?</h2>
                <p>Las cookies son pequeños archivos de texto que los sitios web almacenan en tu dispositivo para recordar información sobre tu visita, como tus preferencias de idioma o si has iniciado sesión.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Cookies que utilizamos</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Nombre</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Tipo</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Duración</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Finalidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">foodtruck_session</td>
                                <td class="px-4 py-3">Técnica / esencial</td>
                                <td class="px-4 py-3">Sesión</td>
                                <td class="px-4 py-3">Mantener la sesión de usuario autenticado.</td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">XSRF-TOKEN</td>
                                <td class="px-4 py-3">Técnica / seguridad</td>
                                <td class="px-4 py-3">Sesión</td>
                                <td class="px-4 py-3">Protección contra ataques CSRF.</td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">foodtruck_locale</td>
                                <td class="px-4 py-3">Preferencia</td>
                                <td class="px-4 py-3">1 año</td>
                                <td class="px-4 py-3">Recordar el idioma seleccionado.</td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">appearance</td>
                                <td class="px-4 py-3">Preferencia</td>
                                <td class="px-4 py-3">1 año</td>
                                <td class="px-4 py-3">Recordar el tema visual (claro/oscuro).</td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 font-mono text-xs">__stripe_*</td>
                                <td class="px-4 py-3">Terceros (Stripe)</td>
                                <td class="px-4 py-3">Variable</td>
                                <td class="px-4 py-3">Procesamiento seguro de pagos.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Cookies de terceros</h2>
                <p>Utilizamos <strong>Stripe</strong> para procesar pagos de forma segura. Stripe puede instalar sus propias cookies técnicas necesarias para el funcionamiento del pago. Consulta la política de cookies de Stripe en <a href="https://stripe.com/es/cookie-settings" target="_blank" rel="noopener noreferrer" class="text-amber-600 hover:underline">stripe.com/es/cookie-settings</a>.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Cómo gestionar las cookies</h2>
                <p>Puedes configurar tu navegador para rechazar o eliminar cookies. Ten en cuenta que deshabilitar las cookies técnicas puede impedir el correcto funcionamiento de la plataforma (inicio de sesión, carrito, pagos).</p>
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener noreferrer" class="text-amber-600 hover:underline">Chrome</a></li>
                    <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web" target="_blank" rel="noopener noreferrer" class="text-amber-600 hover:underline">Firefox</a></li>
                    <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" rel="noopener noreferrer" class="text-amber-600 hover:underline">Safari</a></li>
                </ul>
            </section>

        </div>

        <div class="mt-10">
            <a href="/" class="text-amber-500 hover:underline">← Volver al inicio</a>
        </div>
    </div>
</template>
