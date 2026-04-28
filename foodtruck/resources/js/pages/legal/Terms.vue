<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ClientLayout from '@/layouts/ClientLayout.vue';

defineOptions({ layout: ClientLayout });
</script>

<template>
    <Head title="Términos de uso · FoodTruck" />
    <div class="mx-auto max-w-3xl px-4 py-14">
        <h1 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">Términos de uso</h1>
        <p class="mb-8 text-sm text-gray-400">Última actualización: abril de 2026</p>

        <div class="space-y-6 text-gray-700 dark:text-gray-300">

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">1. Aceptación de los términos</h2>
                <p>Al acceder y utilizar este sitio web, aceptas quedar vinculado por los presentes términos de uso. Si no estás de acuerdo con alguna de las condiciones, te rogamos que no utilices el servicio.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">2. Descripción del servicio</h2>
                <p>FoodTruck es una plataforma de pedidos en línea que permite a los usuarios consultar el menú, realizar pedidos y efectuar el pago a través de Stripe. El servicio se presta exclusivamente en el territorio donde opera el food truck.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">3. Registro y cuenta</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Para realizar pedidos debes crear una cuenta con datos verídicos.</li>
                    <li>Eres responsable de mantener la confidencialidad de tu contraseña.</li>
                    <li>FoodTruck se reserva el derecho a suspender cuentas que infrinjan estos términos.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">4. Pedidos y pagos</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Los pedidos son vinculantes una vez confirmados y pagados.</li>
                    <li>Los precios mostrados incluyen el IVA aplicable.</li>
                    <li>El pago se realiza íntegramente a través de Stripe antes de la preparación del pedido.</li>
                    <li>En caso de cancelación por nuestra parte, se reembolsará el importe íntegro.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">5. Disponibilidad del servicio</h2>
                <p>La disponibilidad del servicio puede variar en función de la ubicación diaria del food truck. FoodTruck no garantiza la disponibilidad continua del servicio y no se responsabiliza de las interrupciones por causas ajenas a su control.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">6. Propiedad intelectual</h2>
                <p>Todos los contenidos de este sitio web (textos, imágenes, logotipos y código) son propiedad de FoodTruck o de sus licenciantes y están protegidos por las leyes de propiedad intelectual.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">7. Legislación aplicable</h2>
                <p>Estos términos se rigen por la legislación española. Cualquier controversia se someterá a los juzgados y tribunales del domicilio del consumidor.</p>
            </section>

        </div>

        <div class="mt-10">
            <a href="/" class="text-amber-500 hover:underline">← Volver al inicio</a>
        </div>
    </div>
</template>
