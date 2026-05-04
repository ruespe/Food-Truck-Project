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
    },
} as const;

type Messages = typeof messages.es;
export type MessageKey = keyof Messages;

function getInitialLocale(): Locale {
    if (typeof window === 'undefined') return 'es';
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

export function useI18n() {
    function t(key: MessageKey): string {
        const dict = messages[locale.value] as Messages;
        return dict[key] ?? (messages.es as Messages)[key] ?? key;
    }

    function setLocale(l: Locale) {
        locale.value = l;
        if (typeof window !== 'undefined') {
            localStorage.setItem('foodtruck_locale', l);
        }
    }

    return { t, locale, setLocale };
}
