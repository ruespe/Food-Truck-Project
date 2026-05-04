import { ref } from 'vue';

export type Locale = 'es' | 'ca' | 'en';

const messages = {
    es: {
        // Navbar
        'nav.home': 'Inicio',
        'nav.menu': 'Menú',
        'nav.location': 'Ubicación',
        'nav.contact': 'Contacto',
        'nav.login': 'Entrar',
        'nav.myOrders': 'Mis pedidos',
        'footer.rights': 'Todos los derechos reservados',
        'footer.tagline': 'Comida fresca y artesanal en la calle.',
        'footer.contactTitle': 'Contacto',
        'footer.followTitle': 'Síguenos',
        'footer.legalTitle': 'Legal',
        'footer.privacy': 'Política de privacidad',
        'footer.terms': 'Términos de uso',
        'footer.cookies': 'Política de cookies',
        // Theme
        'theme.light': 'Claro',
        'theme.dark': 'Oscuro',
        // Lang selector
        'lang.es': 'Español',
        'lang.ca': 'Català',
        'lang.en': 'English',
        // Welcome – Hero
        'home.welcome': 'Bienvenido a',
        'home.subtitle': 'Comida fresca y artesanal en la calle. Bocadillos, hamburguesas, tapas y mucho más.',
        'home.cta': 'Ver el menú →',
        // Welcome – Popular
        'home.popular': 'Lo más popular',
        'home.loading': 'Cargando productos...',
        'home.noProducts': 'No hay productos disponibles en este momento.',
        'home.seeMenu': 'Ver menú',
        // Welcome – Location
        'home.where': '¿Dónde estamos hoy?',
        'home.noLocation': 'Ubicación no disponible hoy',
        'home.noLocationAlt': 'Sin ubicación activa',
        // Welcome – Contact
        'home.contact': 'Contacto',
        'home.name': 'Tu nombre',
        'home.email': 'Tu email',
        'home.message': 'Tu mensaje',
        'home.send': 'Enviar mensaje',
        'home.sent': 'Mensaje enviado. ¡Gracias!',
        // Menu
        'menu.title': 'Nuestro menú',
        'menu.all': 'Todos',
        'menu.add': 'Añadir',
        'menu.added': '✓ Añadido',
        'menu.notAvailable': 'No disponible',
        'menu.outOfStock': 'Sin stock',
        'menu.noProducts': 'No hay productos en esta categoría.',
        'menu.seeCart': 'Ver carrito',
        'menu.loginRequired': 'Debes iniciar sesión para añadir productos al carrito.',
        // Cart
        'cart.title': 'Tu carrito',
        'cart.empty': 'El carrito está vacío.',
        'cart.seeMenu': 'Ver menú',
        'cart.total': 'Total',
        'cart.checkout': 'Pagar con Stripe →',
        'cart.unit': '€ / ud',
        // Orders
        'orders.title': 'Mis pedidos',
        'orders.empty': 'Aún no has realizado ningún pedido.',
        'orders.seeMenu': 'Ver menú',
        'orders.order': 'Pedido #',
        'orders.total': 'Total:',
        // Statuses
        'status.pending': 'Pendiente',
        'status.confirmed': 'Confirmado',
        'status.preparing': 'Preparando',
        'status.ready': 'Listo',
        'status.delivered': 'Entregado',
        'status.cancelled': 'Cancelado',
        // Order detail
        'orderDetail.title': 'Pedido #',
        'orderDetail.total': 'Total',
        'orderDetail.status': 'Estado',
        'orderDetail.back': '← Volver a mis pedidos',
        'orderDetail.cancel': 'Cancelar pedido',
        'orderDetail.cancelConfirm': '¿Seguro que quieres cancelar este pedido?',
        // Payment success
        'payment.success.title': '¡Pago completado!',
        'payment.success.order': 'Pedido #',
        'payment.success.cta': 'Ver mis pedidos',
        // Payment cancel
        'payment.cancel.title': 'Pago cancelado',
        'payment.cancel.text': 'El pedido no ha sido pagado.',
        'payment.cancel.retry': 'Reintentar pago',
        'payment.cancel.backMenu': 'Volver al menú',
        // Navbar user dropdown
        'nav.profile': 'Mi perfil',
        'nav.adminPanel': 'Panel de administración',
        'nav.logout': 'Cerrar sesión',
        // Footer contact button
        'footer.contactBtn': 'Escríbenos',
        // Settings – Profile
        'settings.password.title': 'Cambiar contraseña',
        'settings.password.subtitle': 'Usa una contraseña larga y segura',
        'settings.password.current': 'Contraseña actual',
        'settings.password.new': 'Nueva contraseña',
        'settings.password.confirm': 'Confirmar contraseña',
        'settings.password.save': 'Guardar contraseña',
        'settings.password.saved': 'Contraseña actualizada',
        'settings.delete.danger': 'Zona de peligro',
        'settings.delete.irreversible': 'Esta acción es irreversible',
        'settings.delete.warning': 'Aviso',
        'settings.delete.warningText': 'Por favor, procede con cuidado, esta acción no se puede deshacer.',
        'settings.delete.btn': 'Eliminar cuenta',
        'settings.delete.confirmTitle': '¿Seguro que quieres eliminar tu cuenta?',
        'settings.delete.confirmDesc': 'Una vez eliminada, todos los datos serán borrados permanentemente. Introduce tu contraseña para confirmar.',
        'settings.delete.passwordPlaceholder': 'Contraseña',
        'settings.delete.cancel': 'Cancelar',
        'settings.delete.confirm': 'Eliminar cuenta',
        // Legal pages – common
        'legal.back': '← Volver al inicio',
        'legal.updated': 'Última actualización: abril de 2026',
        // Legal – Cookies
        'cookies.title': 'Política de cookies',
        'cookies.s1.title': '¿Qué son las cookies?',
        'cookies.s1.text': 'Las cookies son pequeños archivos de texto que los sitios web almacenan en tu dispositivo para recordar información sobre tu visita, como tus preferencias de idioma o si has iniciado sesión.',
        'cookies.s2.title': 'Cookies que utilizamos',
        'cookies.s2.col.name': 'Nombre',
        'cookies.s2.col.type': 'Tipo',
        'cookies.s2.col.duration': 'Duración',
        'cookies.s2.col.purpose': 'Finalidad',
        'cookies.s2.essential': 'Técnica / esencial',
        'cookies.s2.security': 'Técnica / seguridad',
        'cookies.s2.preference': 'Preferencia',
        'cookies.s2.thirdParty': 'Terceros (Stripe)',
        'cookies.s2.durSession': 'Sesión',
        'cookies.s2.durYear': '1 año',
        'cookies.s2.durVariable': 'Variable',
        'cookies.s2.pSession': 'Mantener la sesión de usuario autenticado.',
        'cookies.s2.pXsrf': 'Protección contra ataques CSRF.',
        'cookies.s2.pLocale': 'Recordar el idioma seleccionado.',
        'cookies.s2.pAppearance': 'Recordar el tema visual (claro/oscuro).',
        'cookies.s2.pStripe': 'Procesamiento seguro de pagos.',
        'cookies.s3.title': 'Cookies de terceros',
        'cookies.s3.pre': 'Utilizamos',
        'cookies.s3.mid': 'para procesar pagos de forma segura. Stripe puede instalar sus propias cookies técnicas necesarias para el funcionamiento del pago. Consulta la política de cookies de Stripe en',
        'cookies.s4.title': 'Cómo gestionar las cookies',
        'cookies.s4.text': 'Puedes configurar tu navegador para rechazar o eliminar cookies. Ten en cuenta que deshabilitar las cookies técnicas puede impedir el correcto funcionamiento de la plataforma (inicio de sesión, carrito, pagos).',
        // Legal – Privacy
        'privacy.title': 'Política de privacidad',
        'privacy.s1.title': '1. Responsable del tratamiento',
        'privacy.s1.pre': 'FoodTruck es el responsable del tratamiento de los datos personales que nos proporciones a través de este sitio web. Puedes contactarnos en:',
        'privacy.s2.title': '2. Datos que recopilamos',
        'privacy.s2.item1': '<strong>Datos de registro:</strong> nombre y dirección de correo electrónico al crear una cuenta.',
        'privacy.s2.item2': '<strong>Datos de pedidos:</strong> productos solicitados, importe total y estado del pedido.',
        'privacy.s2.item3': '<strong>Datos de contacto:</strong> nombre, email y mensaje cuando nos escribes mediante el formulario.',
        'privacy.s2.item4': '<strong>Datos técnicos:</strong> dirección IP, tipo de navegador y páginas visitadas (datos de sesión anonimizados).',
        'privacy.s3.title': '3. Finalidad y base legal',
        'privacy.s3.item1': '<strong>Gestión de pedidos:</strong> ejecución del contrato (art. 6.1.b RGPD).',
        'privacy.s3.item2': '<strong>Atención al cliente:</strong> interés legítimo en responder a tus consultas (art. 6.1.f RGPD).',
        'privacy.s3.item3': '<strong>Mejora del servicio:</strong> interés legítimo en analizar el uso de la plataforma (art. 6.1.f RGPD).',
        'privacy.s4.title': '4. Conservación de datos',
        'privacy.s4.text': 'Los datos de cuenta se conservan mientras mantengas tu cuenta activa. Los datos de pedidos se conservan durante 5 años por obligaciones contables. Los mensajes de contacto se eliminan a los 12 meses.',
        'privacy.s5.title': '5. Tus derechos',
        'privacy.s5.pre': 'Puedes ejercer tus derechos de acceso, rectificación, supresión, oposición, limitación y portabilidad escribiendo a',
        'privacy.s5.mid': 'También tienes derecho a reclamar ante la Agencia Española de Protección de Datos (AEPD).',
        'privacy.s6.title': '6. Pagos',
        'privacy.s6.pre': 'Los pagos se procesan a través de',
        'privacy.s6.mid': 'No almacenamos datos de tarjeta. Consulta la política de privacidad de Stripe en',
        // Legal – Terms
        'terms.title': 'Términos de uso',
        'terms.s1.title': '1. Aceptación de los términos',
        'terms.s1.text': 'Al acceder y utilizar este sitio web, aceptas quedar vinculado por los presentes términos de uso. Si no estás de acuerdo con alguna de las condiciones, te rogamos que no utilices el servicio.',
        'terms.s2.title': '2. Descripción del servicio',
        'terms.s2.text': 'FoodTruck es una plataforma de pedidos en línea que permite a los usuarios consultar el menú, realizar pedidos y efectuar el pago a través de Stripe. El servicio se presta exclusivamente en el territorio donde opera el food truck.',
        'terms.s3.title': '3. Registro y cuenta',
        'terms.s3.item1': 'Para realizar pedidos debes crear una cuenta con datos verídicos.',
        'terms.s3.item2': 'Eres responsable de mantener la confidencialidad de tu contraseña.',
        'terms.s3.item3': 'FoodTruck se reserva el derecho a suspender cuentas que infrinjan estos términos.',
        'terms.s4.title': '4. Pedidos y pagos',
        'terms.s4.item1': 'Los pedidos son vinculantes una vez confirmados y pagados.',
        'terms.s4.item2': 'Los precios mostrados incluyen el IVA aplicable.',
        'terms.s4.item3': 'El pago se realiza íntegramente a través de Stripe antes de la preparación del pedido.',
        'terms.s4.item4': 'En caso de cancelación por nuestra parte, se reembolsará el importe íntegro.',
        'terms.s5.title': '5. Disponibilidad del servicio',
        'terms.s5.text': 'La disponibilidad del servicio puede variar en función de la ubicación diaria del food truck. FoodTruck no garantiza la disponibilidad continua del servicio y no se responsabiliza de las interrupciones por causas ajenas a su control.',
        'terms.s6.title': '6. Propiedad intelectual',
        'terms.s6.text': 'Todos los contenidos de este sitio web (textos, imágenes, logotipos y código) son propiedad de FoodTruck o de sus licenciantes y están protegidos por las leyes de propiedad intelectual.',
        'terms.s7.title': '7. Legislación aplicable',
        'terms.s7.text': 'Estos términos se rigen por la legislación española. Cualquier controversia se someterá a los juzgados y tribunales del domicilio del consumidor.',
    },
    en: {
        // Navbar
        'nav.home': 'Home',
        'nav.menu': 'Menu',
        'nav.location': 'Location',
        'nav.contact': 'Contact',
        'nav.login': 'Login',
        'nav.myOrders': 'My orders',
        'nav.profile': 'My profile',
        'nav.adminPanel': 'Admin panel',
        'nav.logout': 'Log out',
        'home.welcome': 'Welcome to',
        'footer.rights': 'All rights reserved',
        'footer.tagline': 'Fresh handcrafted street food.',
        'footer.contactTitle': 'Contact',
        'footer.followTitle': 'Follow us',
        'footer.legalTitle': 'Legal',
        'footer.privacy': 'Privacy policy',
        'footer.terms': 'Terms of use',
        'footer.cookies': 'Cookie policy',
        // Theme
        'theme.light': 'Light',
        'theme.dark': 'Dark',
        // Lang selector
        'lang.es': 'Español',
        'lang.ca': 'Català',
        'lang.en': 'English',
        'home.subtitle': 'Fresh handcrafted street food. Sandwiches, burgers, tapas and much more.',
        'home.cta': 'See the menu →',
        // Welcome – Popular
        'home.popular': 'Most popular',
        'home.loading': 'Loading products...',
        'home.noProducts': 'No products available at this time.',
        'home.seeMenu': 'See menu',
        // Welcome – Location
        'home.where': 'Where are we today?',
        'home.noLocation': 'Location not available today',
        'home.noLocationAlt': 'No active location',
        // Welcome – Contact
        'home.contact': 'Contact',
        'home.name': 'Your name',
        'home.email': 'Your email',
        'home.message': 'Your message',
        'home.send': 'Send message',
        'home.sent': 'Message sent. Thank you!',
        // Menu
        'menu.title': 'Our menu',
        'menu.all': 'All',
        'menu.add': 'Add',
        'menu.added': '✓ Added',
        'menu.notAvailable': 'Not available',
        'menu.outOfStock': 'Out of stock',
        'menu.noProducts': 'No products in this category.',
        'menu.seeCart': 'See cart',
        'menu.loginRequired': 'You must be logged in to add products to the cart.',
        // Cart
        'cart.title': 'Your cart',
        'cart.empty': 'Your cart is empty.',
        'cart.seeMenu': 'See menu',
        'cart.total': 'Total',
        'cart.checkout': 'Pay with Stripe →',
        'cart.unit': '€ / unit',
        // Orders
        'orders.title': 'My orders',
        'orders.empty': "You haven't placed any orders yet.",
        'orders.seeMenu': 'See menu',
        'orders.order': 'Order #',
        'orders.total': 'Total:',
        // Statuses
        'status.pending': 'Pending',
        'status.confirmed': 'Confirmed',
        'status.preparing': 'Preparing',
        'status.ready': 'Ready',
        'status.delivered': 'Delivered',
        'status.cancelled': 'Cancelled',
        // Order detail
        'orderDetail.title': 'Order #',
        'orderDetail.total': 'Total',
        'orderDetail.status': 'Status',
        'orderDetail.back': '← Back to my orders',
        'orderDetail.cancel': 'Cancel order',
        'orderDetail.cancelConfirm': 'Are you sure you want to cancel this order?',
        // Payment success
        'payment.success.title': 'Payment complete!',
        'payment.success.order': 'Order #',
        'payment.success.cta': 'View my orders',
        // Payment cancel
        'payment.cancel.title': 'Payment cancelled',
        'payment.cancel.text': 'The order has not been paid.',
        'payment.cancel.retry': 'Retry payment',
        'payment.cancel.backMenu': 'Back to menu',
        // Footer contact button
        'footer.contactBtn': 'Get in touch',
        // Settings – Profile
        'settings.password.title': 'Change password',
        'settings.password.subtitle': 'Use a long, secure password',
        'settings.password.current': 'Current password',
        'settings.password.new': 'New password',
        'settings.password.confirm': 'Confirm password',
        'settings.password.save': 'Save password',
        'settings.password.saved': 'Password updated',
        'settings.delete.danger': 'Danger zone',
        'settings.delete.irreversible': 'This action is irreversible',
        'settings.delete.warning': 'Warning',
        'settings.delete.warningText': 'Please proceed with caution, this cannot be undone.',
        'settings.delete.btn': 'Delete account',
        'settings.delete.confirmTitle': 'Are you sure you want to delete your account?',
        'settings.delete.confirmDesc': 'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.',
        'settings.delete.passwordPlaceholder': 'Password',
        'settings.delete.cancel': 'Cancel',
        'settings.delete.confirm': 'Delete account',
        // Legal pages – common
        'legal.back': '← Back to home',
        'legal.updated': 'Last updated: April 2026',
        // Legal – Cookies
        'cookies.title': 'Cookie policy',
        'cookies.s1.title': 'What are cookies?',
        'cookies.s1.text': 'Cookies are small text files that websites store on your device to remember information about your visit, such as your language preferences or whether you are logged in.',
        'cookies.s2.title': 'Cookies we use',
        'cookies.s2.col.name': 'Name',
        'cookies.s2.col.type': 'Type',
        'cookies.s2.col.duration': 'Duration',
        'cookies.s2.col.purpose': 'Purpose',
        'cookies.s2.essential': 'Technical / essential',
        'cookies.s2.security': 'Technical / security',
        'cookies.s2.preference': 'Preference',
        'cookies.s2.thirdParty': 'Third-party (Stripe)',
        'cookies.s2.durSession': 'Session',
        'cookies.s2.durYear': '1 year',
        'cookies.s2.durVariable': 'Variable',
        'cookies.s2.pSession': 'Maintain authenticated user session.',
        'cookies.s2.pXsrf': 'Protection against CSRF attacks.',
        'cookies.s2.pLocale': 'Remember the selected language.',
        'cookies.s2.pAppearance': 'Remember the visual theme (light/dark).',
        'cookies.s2.pStripe': 'Secure payment processing.',
        'cookies.s3.title': 'Third-party cookies',
        'cookies.s3.pre': 'We use',
        'cookies.s3.mid': "to securely process payments. Stripe may install its own technical cookies required for payment processing. Check Stripe's cookie policy at",
        'cookies.s4.title': 'How to manage cookies',
        'cookies.s4.text': 'You can configure your browser to reject or delete cookies. Please note that disabling technical cookies may prevent the platform from working correctly (login, cart, payments).',
        // Legal – Privacy
        'privacy.title': 'Privacy policy',
        'privacy.s1.title': '1. Data controller',
        'privacy.s1.pre': 'FoodTruck is the data controller for the personal data you provide through this website. You can contact us at:',
        'privacy.s2.title': '2. Data we collect',
        'privacy.s2.item1': '<strong>Registration data:</strong> name and email address when creating an account.',
        'privacy.s2.item2': '<strong>Order data:</strong> requested products, total amount and order status.',
        'privacy.s2.item3': '<strong>Contact data:</strong> name, email and message when you write to us via the form.',
        'privacy.s2.item4': '<strong>Technical data:</strong> IP address, browser type and pages visited (anonymised session data).',
        'privacy.s3.title': '3. Purpose and legal basis',
        'privacy.s3.item1': '<strong>Order management:</strong> performance of a contract (art. 6.1.b GDPR).',
        'privacy.s3.item2': '<strong>Customer support:</strong> legitimate interest in responding to your queries (art. 6.1.f GDPR).',
        'privacy.s3.item3': '<strong>Service improvement:</strong> legitimate interest in analysing platform usage (art. 6.1.f GDPR).',
        'privacy.s4.title': '4. Data retention',
        'privacy.s4.text': 'Account data is kept while your account is active. Order data is retained for 5 years for accounting obligations. Contact messages are deleted after 12 months.',
        'privacy.s5.title': '5. Your rights',
        'privacy.s5.pre': 'You can exercise your rights of access, rectification, erasure, objection, restriction and portability by writing to',
        'privacy.s5.mid': 'You also have the right to lodge a complaint with the Spanish Data Protection Agency (AEPD).',
        'privacy.s6.title': '6. Payments',
        'privacy.s6.pre': 'Payments are processed through',
        'privacy.s6.mid': "We do not store card data. Check Stripe's privacy policy at",
        // Legal – Terms
        'terms.title': 'Terms of use',
        'terms.s1.title': '1. Acceptance of terms',
        'terms.s1.text': 'By accessing and using this website, you agree to be bound by these terms of use. If you do not agree with any of the conditions, please do not use the service.',
        'terms.s2.title': '2. Service description',
        'terms.s2.text': 'FoodTruck is an online ordering platform that allows users to browse the menu, place orders and make payments through Stripe. The service is provided exclusively in the territory where the food truck operates.',
        'terms.s3.title': '3. Registration and account',
        'terms.s3.item1': 'To place orders you must create an account with accurate information.',
        'terms.s3.item2': 'You are responsible for maintaining the confidentiality of your password.',
        'terms.s3.item3': 'FoodTruck reserves the right to suspend accounts that violate these terms.',
        'terms.s4.title': '4. Orders and payments',
        'terms.s4.item1': 'Orders are binding once confirmed and paid.',
        'terms.s4.item2': 'Prices shown include applicable VAT.',
        'terms.s4.item3': 'Payment is made entirely through Stripe before order preparation.',
        'terms.s4.item4': 'In case of cancellation on our part, the full amount will be refunded.',
        'terms.s5.title': '5. Service availability',
        'terms.s5.text': 'Service availability may vary depending on the daily location of the food truck. FoodTruck does not guarantee continuous service availability and is not responsible for interruptions due to causes beyond its control.',
        'terms.s6.title': '6. Intellectual property',
        'terms.s6.text': 'All content on this website (texts, images, logos and code) is the property of FoodTruck or its licensors and is protected by intellectual property laws.',
        'terms.s7.title': '7. Applicable law',
        'terms.s7.text': "These terms are governed by Spanish law. Any dispute shall be submitted to the courts of the consumer's place of residence.",
    },
    ca: {
        // Navbar
        'nav.home': 'Inici',
        'nav.menu': 'Menú',
        'nav.location': 'Ubicació',
        'nav.contact': 'Contacte',
        'nav.login': 'Entrar',
        'nav.myOrders': 'Les meves comandes',
        'nav.profile': 'El meu perfil',
        'nav.adminPanel': 'Tauler d\'administració',
        'nav.logout': 'Tancar sessió',
        'footer.rights': 'Tots els drets reservats',
        'footer.tagline': 'Menjar fresc i artesà al carrer.',
        'footer.contactTitle': 'Contacte',
        'footer.followTitle': 'Segueix-nos',
        'footer.legalTitle': 'Legal',
        'footer.privacy': 'Política de privacitat',
        'footer.terms': "Termes d'ús",
        'footer.cookies': 'Política de cookies',
        // Theme
        'theme.light': 'Clar',
        'theme.dark': 'Fosc',
        // Lang selector
        'lang.es': 'Español',
        'lang.ca': 'Català',
        'lang.en': 'English',
        // Welcome – Hero
        'home.welcome': 'Benvingut a',
        'home.subtitle': 'Menjar fresc i artesà al carrer. Entrepans, hamburgueses, tapes i molt més.',
        'home.cta': 'Veure el menú →',
        // Welcome – Popular
        'home.popular': 'El més popular',
        'home.loading': 'Carregant productes...',
        'home.noProducts': 'No hi ha productes disponibles en aquest moment.',
        'home.seeMenu': 'Veure el menú',
        // Welcome – Location
        'home.where': 'On som avui?',
        'home.noLocation': 'Ubicació no disponible avui',
        'home.noLocationAlt': 'Sense ubicació activa',
        // Welcome – Contact
        'home.contact': 'Contacte',
        'home.name': 'El teu nom',
        'home.email': 'El teu correu',
        'home.message': 'El teu missatge',
        'home.send': 'Enviar missatge',
        'home.sent': 'Missatge enviat. Gràcies!',
        // Menu
        'menu.title': 'El nostre menú',
        'menu.all': 'Tots',
        'menu.add': 'Afegir',
        'menu.added': '✓ Afegit',
        'menu.notAvailable': 'No disponible',
        'menu.outOfStock': 'Sense estoc',
        'menu.noProducts': 'No hi ha productes en aquesta categoria.',
        'menu.seeCart': 'Veure cistella',
        'menu.loginRequired': 'Has d\'iniciar sessió per afegir productes al carret',
        // Cart
        'cart.title': 'La teva cistella',
        'cart.empty': 'La cistella és buida.',
        'cart.seeMenu': 'Veure el menú',
        'cart.total': 'Total',
        'cart.checkout': 'Pagar amb Stripe →',
        'cart.unit': '€ / ut',
        // Orders
        'orders.title': 'Les meves comandes',
        'orders.empty': 'Encara no has fet cap comanda.',
        'orders.seeMenu': 'Veure el menú',
        'orders.order': 'Comanda #',
        'orders.total': 'Total:',
        // Statuses
        'status.pending': 'Pendent',
        'status.confirmed': 'Confirmat',
        'status.preparing': 'Preparant',
        'status.ready': 'Llest',
        'status.delivered': 'Entregat',
        'status.cancelled': 'Cancel·lat',
        // Order detail
        'orderDetail.title': 'Comanda #',
        'orderDetail.total': 'Total',
        'orderDetail.status': 'Estat',
        'orderDetail.back': '← Tornar a les meves comandes',
        'orderDetail.cancel': 'Cancel·lar comanda',
        'orderDetail.cancelConfirm': 'Segur que vols cancel·lar aquesta comanda?',
        // Payment success
        'payment.success.title': 'Pagament completat!',
        'payment.success.order': 'Comanda #',
        'payment.success.cta': 'Veure les meves comandes',
        // Payment cancel
        'payment.cancel.title': 'Pagament cancel·lat',
        'payment.cancel.text': 'La comanda no ha estat pagada.',
        'payment.cancel.retry': 'Reintentar el pagament',
        'payment.cancel.backMenu': 'Tornar al menú',
        // Footer contact button
        'footer.contactBtn': 'Escriu-nos',
        // Settings – Profile
        'settings.password.title': 'Canviar contrasenya',
        'settings.password.subtitle': 'Usa una contrasenya llarga i segura',
        'settings.password.current': 'Contrasenya actual',
        'settings.password.new': 'Nova contrasenya',
        'settings.password.confirm': 'Confirmar contrasenya',
        'settings.password.save': 'Desar contrasenya',
        'settings.password.saved': 'Contrasenya actualitzada',
        'settings.delete.danger': 'Zona de perill',
        'settings.delete.irreversible': 'Aquesta acció és irreversible',
        'settings.delete.warning': 'Avís',
        'settings.delete.warningText': 'Si us plau, procedeix amb cura, aquesta acció no es pot desfer.',
        'settings.delete.btn': 'Eliminar compte',
        'settings.delete.confirmTitle': 'Segur que vols eliminar el teu compte?',
        'settings.delete.confirmDesc': 'Un cop eliminat, totes les dades seran esborrades permanentment. Introdueix la teva contrasenya per confirmar.',
        'settings.delete.passwordPlaceholder': 'Contrasenya',
        'settings.delete.cancel': "Cancel·lar",
        'settings.delete.confirm': 'Eliminar compte',
        // Legal pages – common
        'legal.back': "← Tornar a l'inici",
        'legal.updated': 'Última actualització: abril de 2026',
        // Legal – Cookies
        'cookies.title': 'Política de cookies',
        'cookies.s1.title': 'Què són les cookies?',
        'cookies.s1.text': "Les cookies són petits fitxers de text que els llocs web emmagatzemen al teu dispositiu per recordar informació sobre la teva visita, com les teves preferències d'idioma o si has iniciat sessió.",
        'cookies.s2.title': 'Cookies que utilitzem',
        'cookies.s2.col.name': 'Nom',
        'cookies.s2.col.type': 'Tipus',
        'cookies.s2.col.duration': 'Durada',
        'cookies.s2.col.purpose': 'Finalitat',
        'cookies.s2.essential': 'Tècnica / essencial',
        'cookies.s2.security': 'Tècnica / seguretat',
        'cookies.s2.preference': 'Preferència',
        'cookies.s2.thirdParty': 'Tercers (Stripe)',
        'cookies.s2.durSession': 'Sessió',
        'cookies.s2.durYear': '1 any',
        'cookies.s2.durVariable': 'Variable',
        "cookies.s2.pSession": "Mantenir la sessió d'usuari autenticat.",
        'cookies.s2.pXsrf': 'Protecció contra atacs CSRF.',
        "cookies.s2.pLocale": "Recordar l'idioma seleccionat.",
        'cookies.s2.pAppearance': 'Recordar el tema visual (clar/fosc).',
        'cookies.s2.pStripe': 'Processament segur de pagaments.',
        'cookies.s3.title': 'Cookies de tercers',
        'cookies.s3.pre': 'Utilitzem',
        'cookies.s3.mid': 'per processar pagaments de forma segura. Stripe pot instal·lar les seves pròpies cookies tècniques necessàries per al funcionament del pagament. Consulta la política de cookies de Stripe a',
        'cookies.s4.title': 'Com gestionar les cookies',
        'cookies.s4.text': 'Pots configurar el teu navegador per rebutjar o eliminar cookies. Ten en compte que deshabilitar les cookies tècniques pot impedir el funcionament correcte de la plataforma (inici de sessió, cistella, pagaments).',
        // Legal – Privacy
        'privacy.title': 'Política de privacitat',
        'privacy.s1.title': '1. Responsable del tractament',
        "privacy.s1.pre": "FoodTruck és el responsable del tractament de les dades personals que ens proporcionis a través d'aquest lloc web. Pots contactar-nos a:",
        'privacy.s2.title': '2. Dades que recollim',
        'privacy.s2.item1': '<strong>Dades de registre:</strong> nom i adreça de correu electrònic en crear un compte.',
        'privacy.s2.item2': '<strong>Dades de comandes:</strong> productes sol·licitats, import total i estat de la comanda.',
        'privacy.s2.item3': '<strong>Dades de contacte:</strong> nom, correu i missatge quan ens escrius mitjançant el formulari.',
        'privacy.s2.item4': '<strong>Dades tècniques:</strong> adreça IP, tipus de navegador i pàgines visitades (dades de sessió anonimitzades).',
        'privacy.s3.title': '3. Finalitat i base legal',
        'privacy.s3.item1': '<strong>Gestió de comandes:</strong> execució del contracte (art. 6.1.b RGPD).',
        'privacy.s3.item2': '<strong>Atenció al client:</strong> interès legítim en respondre a les teves consultes (art. 6.1.f RGPD).',
        "privacy.s3.item3": "<strong>Millora del servei:</strong> interès legítim en analitzar l'ús de la plataforma (art. 6.1.f RGPD).",
        'privacy.s4.title': '4. Conservació de dades',
        "privacy.s4.text": "Les dades de compte es conserven mentre mantinguis el teu compte actiu. Les dades de comandes es conserven durant 5 anys per obligacions comptables. Els missatges de contacte s'eliminen als 12 mesos.",
        'privacy.s5.title': '5. Els teus drets',
        "privacy.s5.pre": "Pots exercir els teus drets d'accés, rectificació, supressió, oposició, limitació i portabilitat escrivint a",
        "privacy.s5.mid": "També tens dret a reclamar davant l'Agència Espanyola de Protecció de Dades (AEPD).",
        'privacy.s6.title': '6. Pagaments',
        'privacy.s6.pre': 'Els pagaments es processen a través de',
        'privacy.s6.mid': 'No emmagatzemem dades de targeta. Consulta la política de privacitat de Stripe a',
        // Legal – Terms
        "terms.title": "Termes d'ús",
        'terms.s1.title': '1. Acceptació dels termes',
        "terms.s1.text": "En accedir i utilitzar aquest lloc web, acceptes quedar vinculat pels presents termes d'ús. Si no estàs d'acord amb alguna de les condicions, et preguem que no utilitzis el servei.",
        'terms.s2.title': '2. Descripció del servei',
        'terms.s2.text': 'FoodTruck és una plataforma de comandes en línia que permet als usuaris consultar el menú, realitzar comandes i efectuar el pagament a través de Stripe. El servei es presta exclusivament en el territori on opera el food truck.',
        'terms.s3.title': '3. Registre i compte',
        'terms.s3.item1': 'Per realitzar comandes has de crear un compte amb dades verídiques.',
        'terms.s3.item2': 'Ets responsable de mantenir la confidencialitat de la teva contrasenya.',
        'terms.s3.item3': 'FoodTruck es reserva el dret a suspendre comptes que incompleixin aquests termes.',
        'terms.s4.title': '4. Comandes i pagaments',
        'terms.s4.item1': 'Les comandes són vinculants un cop confirmades i pagades.',
        "terms.s4.item2": "Els preus mostrats inclouen l'IVA aplicable.",
        'terms.s4.item3': 'El pagament es realitza íntegrament a través de Stripe abans de la preparació de la comanda.',
        "terms.s4.item4": "En cas de cancel·lació per la nostra part, es reemborsarà l'import íntegre.",
        'terms.s5.title': '5. Disponibilitat del servei',
        'terms.s5.text': 'La disponibilitat del servei pot variar en funció de la ubicació diària del food truck. FoodTruck no garanteix la disponibilitat contínua del servei i no es responsabilitza de les interrupcions per causes alienes al seu control.',
        "terms.s6.title": '6. Propietat intel·lectual',
        "terms.s6.text": "Tots els continguts d'aquest lloc web (textos, imatges, logotips i codi) són propietat de FoodTruck o dels seus llicenciants i estan protegits per les lleis de propietat intel·lectual.",
        'terms.s7.title': '7. Legislació aplicable',
        'terms.s7.text': 'Aquests termes es regeixen per la legislació espanyola. Qualsevol controvèrsia se sotmetrà als jutjats i tribunals del domicili del consumidor.',
    },
} as const;

type Messages = typeof messages.es;
export type MessageKey = keyof Messages;

function getInitialLocale(): Locale {
    if (typeof window === 'undefined') {
return 'es';
}

    return (localStorage.getItem('foodtruck_locale') as Locale) ?? 'es';
}

// Shared reactive state (singleton)
const locale = ref<Locale>(getInitialLocale());

export const localeFlags: Record<Locale, string> = {
    es: '🇪🇸',
    ca: '🏴󠁥󠁳󠁣󠁴󠁿',
    en: '🇬🇧',
};

export const localeNames: Record<Locale, string> = {
    es: 'ES',
    ca: 'CA',
    en: 'EN',
};

/**
 * Translate a dynamic DB value stored as { es: '...', ca: '...', en: '...' }.
 * Falls back to 'es' or an empty string if the locale key is missing.
 */
export function td(value: Record<string, string> | string | null | undefined): string {
    if (!value) {
        return '';
    }

    if (typeof value === 'string') {
        return value;
    }

    return value[locale.value] ?? value['es'] ?? '';
}

export function useI18n() {
    function t(key: MessageKey): string {
        const dict = messages[locale.value] as Messages;

        return dict[key] ?? (messages.es as Messages)[key] ?? key;
    }

    function setLocale(l: Locale) {
        locale.value = l;

        if (typeof window !== 'undefined') {
            localStorage.setItem('foodtruck_locale', l);
            const maxAge = 60 * 60 * 24 * 365;
            document.cookie = `foodtruck_locale=${l};path=/;max-age=${maxAge};SameSite=Lax`;
        }
    }

    return { t, locale, setLocale };
}
