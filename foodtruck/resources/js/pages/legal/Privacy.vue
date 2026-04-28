<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });
</script>

<template>
    <Head title="Política de privacidad · FoodTruck" />
    <div class="mx-auto max-w-3xl px-4 py-14">
        <h1 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">Política de privacidad</h1>
        <p class="mb-8 text-sm text-gray-400">Última actualización: abril de 2026</p>

        <div class="prose prose-gray max-w-none dark:prose-invert space-y-6 text-gray-700 dark:text-gray-300">

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">1. Responsable del tratamiento</h2>
                <p>FoodTruck es el responsable del tratamiento de los datos personales que nos proporciones a través de este sitio web. Puedes contactarnos en: <a href="mailto:hola@foodtruck.es" class="text-amber-600 hover:underline">hola@foodtruck.es</a></p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">2. Datos que recopilamos</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Datos de registro:</strong> nombre y dirección de correo electrónico al crear una cuenta.</li>
                    <li><strong>Datos de pedidos:</strong> productos solicitados, importe total y estado del pedido.</li>
                    <li><strong>Datos de contacto:</strong> nombre, email y mensaje cuando nos escribes mediante el formulario.</li>
                    <li><strong>Datos técnicos:</strong> dirección IP, tipo de navegador y páginas visitadas (datos de sesión anonimizados).</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">3. Finalidad y base legal</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Gestión de pedidos:</strong> ejecución del contrato (art. 6.1.b RGPD).</li>
                    <li><strong>Atención al cliente:</strong> interés legítimo en responder a tus consultas (art. 6.1.f RGPD).</li>
                    <li><strong>Mejora del servicio:</strong> interés legítimo en analizar el uso de la plataforma (art. 6.1.f RGPD).</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">4. Conservación de datos</h2>
                <p>Los datos de cuenta se conservan mientras mantengas tu cuenta activa. Los datos de pedidos se conservan durante 5 años por obligaciones contables. Los mensajes de contacto se eliminan a los 12 meses.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">5. Tus derechos</h2>
                <p>Puedes ejercer tus derechos de acceso, rectificación, supresión, oposición, limitación y portabilidad escribiendo a <a href="mailto:hola@foodtruck.es" class="text-amber-600 hover:underline">hola@foodtruck.es</a>. También tienes derecho a reclamar ante la Agencia Española de Protección de Datos (AEPD).</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">6. Pagos</h2>
                <p>Los pagos se procesan a través de <strong>Stripe</strong>. No almacenamos datos de tarjeta. Consulta la política de privacidad de Stripe en <a href="https://stripe.com/es/privacy" target="_blank" rel="noopener noreferrer" class="text-amber-600 hover:underline">stripe.com/es/privacy</a>.</p>
            </section>

        </div>

        <div class="mt-10">
            <a href="/" class="text-amber-500 hover:underline">← Volver al inicio</a>
        </div>
    </div>
</template>
